<?php

namespace App\Command;

use App\Entity\Events;
use App\Service\CottageService;
use App\Service\CottagesFromApiParserService;
use App\Service\EventsService;
use App\Service\ImportFromAPIService;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SynchronizeAPIData
 * @package App\Command
 */
class SynchronizeAPIData extends Command
{

    protected static $defaultName = 'app:synchronize-api-data';

    /**
     * @var ImportFromAPIService
     */
    private $importFromApiService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ImportCottages constructor.
     * @param ImportFromAPIService $importFromApiService
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     */
    public function __construct(
        ImportFromAPIService $importFromApiService,
        EntityManagerInterface $em,
        LoggerInterface $logger
    ) {
        $this->importFromApiService = $importFromApiService;
        $this->em = $em;
        $this->logger = $logger;
        parent::__construct();
    }

    /**
     * Configure description for the task.
     */
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Import cottages from remote API and save it to the database.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln('Getting cottages from API...');

        try {

            $cottagesFromAPI = $this->importFromApiService->getCottagesFromApi();
            $cottagesFromAPI = json_decode($cottagesFromAPI, true);

            if (!empty($cottagesFromAPI)) {
                $this->processCottages($cottagesFromAPI);
            } else {
                $this->logger->warning("Cottages not found!");
            }

            $output->writeln('Getting reservations from API...');

            $reservationsFromAPI = $this->importFromApiService->getReservationsFromApi();

            if (!empty($reservationsFromAPI)) {
                $this->processReservations($reservationsFromAPI);
            }

        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }

        return 0;

    }

    protected function processReservations($reservationsFromAPI)
    {
        $reservationsFromAPI = json_decode($reservationsFromAPI, true);

        $reservations = $reservationsFromAPI['result']['reservations'];
        $cottageService = new CottageService($this->em);
        $eventService = new EventsService($this->em);

        print_r($reservations);
        foreach ($reservations as $reservation) {

            $itemsToReservation = $reservation['items'];

            if (isset($reservation['client'])) {
                $client = $reservation['client'];
            } else {
                $client = array();
            }

            foreach ($itemsToReservation as $item) {

                $reservationEvent = array();

                $externalId = $reservation['id'];
                $reservationDetails = $reservation['reservationDetails'];
                $dateFrom = $reservationDetails['dateFrom'];
                $dateTo = $reservationDetails['dateTo'];

                $reservationEvent['external_id'] = $externalId;
                $reservationEvent['date_from'] = $dateFrom;
                $reservationEvent['date_to'] = $dateTo;
                $cottage = $cottageService->getCottageByExternalId($item['objectItemId']);
                $cottageId = $cottage->getId();
                $reservationEvent['cottage_id'] = $cottageId;
                $reservationEvent['user_id'] = 1;
                $reservationEvent['guest_first_name'] = isset($client['firstName']) ? $client['firstName'] : 'Unregistered';
                $reservationEvent['guest_last_name'] = isset($client['lastName']) ? $client['lastName'] : 'Unregistered';
                $reservationEvent['guest_phone_number'] = isset($client['phone']) ? $client['phone'] : 'Unregistered';
                $reservationEvent['advance_payment'] = true;
                $reservationEvent['status'] = $reservationDetails['status'];
                $reservationEvent['date_add'] = $reservationDetails['dateAdd'];
                $reservationEvent['type'] = EventsService::RESERVATION_EVENT;

                $result = $eventService->createEvent($reservationEvent);

                if ($result instanceof Events) {

                    print_r($result->getId());
                } else {
                    print_r($result);
                }

            }
        }
    }

    /**
     * Process imported cottages to the system.
     *
     * @param $cottagesFromAPI
     */
    protected function processCottages(
        $cottagesFromAPI
    ) {
        $cottageParser = new CottagesFromApiParserService($this->em);
        $parsedCottages = $cottageParser->parseCottagesToSystemFormat($cottagesFromAPI);
        $cottageService = new CottageService($this->em);

        $externalCottagesIds = $cottageService->getAllIdsOfExternalCottages();

        foreach ($parsedCottages as $parsedCottage) {
            $externalId = $parsedCottage['external_id'];

            $cottage = $cottageService->getCottageByExternalId($externalId);

            if (empty($cottage)) {
                $parsedCottage['color'] = $cottageService->getUnusedColor();
                $cottageService->createCottage($parsedCottage);
            } else {

                if (in_array($cottage->getId(), $externalCottagesIds)) {
                    $key = array_search($cottage->getId(), $externalCottagesIds);
                    unset($externalCottagesIds[$key]);
                }

                $cottageService->updateCottage($cottage, $parsedCottage);
            }
        }

        if (!empty($externalCottagesIds)) {

            foreach ($externalCottagesIds as $externalCottagesId) {

                $cottage = $cottageService->getCottageById($externalCottagesId);
                $cottageService->deleteCottage($cottage);

            }
        }
    }
}