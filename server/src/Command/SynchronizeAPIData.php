<?php

namespace App\Command;

use App\Entity\Cottages;
use App\Lib\CleaningCreator;
use App\Lib\ReservationCreator;
use App\Repository\CottagesRepository;
use App\Service\CottageService;
use App\Service\CottagesFromApiParserService;
use App\Service\EventsService;
use App\Service\ImportFromAPIService;
use App\Service\ReservationService;
use App\Service\ReservationsFromApiParserService;
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
            ->setDescription('Import cottages and reservations from remote API and save it to the database.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to import/update cottages and reservations...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
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
            $output->writeln($exception->getMessage());
            $this->logger->critical($exception->getMessage());
        }

        return 0;

    }

    /**
     * Process of import reservations from API to the system.
     *
     *
     * @param $reservationsFromAPI
     */
    protected function processReservations($reservationsFromAPI)
    {
        $eventService = new EventsService($this->em);
        $reservationService = new ReservationService($this->em);
        $reservationsFromAPIParser = new ReservationsFromApiParserService();

        $reservationsFromAPI = json_decode($reservationsFromAPI, true);
        $reservations = $reservationsFromAPI['result']['reservations'];

        foreach ($reservations as $reservation) {

            $itemsToReservation = $reservation['items'];

            if (isset($reservation['client'])) {
                $client = $reservation['client'];
            } else {
                $client = array();
            }

            foreach ($itemsToReservation as $item) {

                $reservationData = $reservationsFromAPIParser->parseApiDataToSystemFormat($this->em, $item,
                    $reservation, $client);

                // continue if was a problem with parsing data
                if (!is_array($reservationData)) {
                    continue;
                }

                $reservationInSystem = $reservationService->getReservationByExternalId($reservationData['external_id']);
                try {

                    if ($reservationInSystem) {
                        $reservationData['event'] = $reservationInSystem->getEvent();
                        $reservationData['reservation'] = $reservationInSystem;
                    }

                    $this->em->beginTransaction();
                    $eventService->createEvent(new ReservationCreator($this->em), $reservationData);

                    // add cleaning event if reservation is active
                    if ($reservationData['is_active'] === true) {
                        $eventService->createEvent(new CleaningCreator($this->em), $reservationData);
                    }

                    $this->em->commit();

                } catch (\Exception $exception) {
                    $this->logger->info($exception->getMessage());
                    $this->em->rollback();
                }

                $this->logger->info('OK');

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
        $cottageService = new CottageService($this->em);

        $parsedCottages = $cottageParser->parseCottagesToSystemFormat($cottagesFromAPI);
        $externalCottagesIds = $cottageService->getAllIdsOfExternalCottages();

        foreach ($parsedCottages as $parsedCottage) {
            $externalId = $parsedCottage['external_id'];

            $cottageRepository = $this->em->getRepository(Cottages::class);
            $cottage = null;
            if ($cottageRepository instanceof CottagesRepository) {
                $cottage = $cottageRepository->findByExternalId($externalId);
            }

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

                $cottagesRepository = $this->em->getRepository(Cottages::class);

                if ($cottagesRepository instanceof CottagesRepository) {
                    $cottage = $cottagesRepository->findByExternalId($externalCottagesId);
                    $cottageService->deleteCottage($cottage);
                }

            }
        }
    }
}