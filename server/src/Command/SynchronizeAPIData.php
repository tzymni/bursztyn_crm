<?php

namespace App\Command;

use App\Service\CottageService;
use App\Service\CottagesFromApiParserService;
use App\Service\ImportFromAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SynchronizeAPIData
 * @package App\Command
 *
 */
class SynchronizeAPIData extends Command
{

    protected static $defaultName = 'app:synchronize-api-data';

    private $importFromApiService;

    private $em;

    /**
     * ImportCottages constructor.
     * @param ImportFromAPIService $importFromApiService
     * @param EntityManagerInterface $em
     */
    public function __construct(ImportFromAPIService $importFromApiService, EntityManagerInterface $em)
    {
        $this->importFromApiService = $importFromApiService;
        $this->em = $em;

        parent::__construct();
    }

    /**
     *
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln('Getting cottages from API...');
        $cottagesFromAPI = $this->importFromApiService->importCottages();
        $cottagesFromAPI = json_decode($cottagesFromAPI, true);
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

        $output->writeln('Getting reservations from API...');

        $reservationsFromAPI = $this->importFromApiService->importReservations();

        print_r($reservationsFromAPI);
        return 0;

    }

}