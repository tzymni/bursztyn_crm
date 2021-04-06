<?php

namespace App\Service;

use App\Service\interfaces\DecorateEventInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * Class to get objects from API.
 *
 *
 * @package App\Service
 */
class ImportFromAPIService implements DecorateEventInterface
{

    /**
     * Login to API for authentication.
     *
     * @var string
     */
    public $apiLogin = "";

    /**
     * Password to API for authentication.
     *
     * @var string
     */
    public $apiPassword = "";

    /**
     * URL to get all cottages from API.
     *
     * @var string
     */
    public $getCottagesUrl = '';

    /**
     * URL to get all reservations from API.
     *
     * @var string
     */
    public $getReservationsUrl = '';

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * Interface to get configuration from config directory.
     *
     * @var ContainerBagInterface
     */
    public $containerBag;

    public function __construct(LoggerInterface $logger, ContainerBagInterface $containerBag)
    {
        $this->logger = $logger;
        $this->containerBag = $containerBag;
        $this->getApiConfiguration();
    }

    /**
     * Prepare API configuration.
     */
    protected function getApiConfiguration()
    {
        $this->getCottagesUrl = $this->containerBag->get('url_get_all_objects');
        $this->getReservationsUrl = $this->containerBag->get('url_get_all_reservations');
        $this->apiLogin = $this->containerBag->get('login');
        $this->apiPassword = $this->containerBag->get('password');

    }

    /**
     * Import cottages from API and return them in JSON format.
     *
     * @throws GuzzleException
     */
    public function getCottagesFromApi()
    {
        $this->logger->info("Started importing");

        try {

            $client = new Client();

            $requestJson = $this->generateRequestBody();

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json;charset=UTF-8'
            );

            $response = $client->request("GET", $this->getCottagesUrl,
                ['body' => $requestJson, 'headers' => $headers]);
            return $response->getBody()->getContents();

        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

    }

    /**
     * Generate password to use Idosell API.
     *
     * @return string
     */
    private function generateIdosellPassword(): string
    {
        return sha1(date('Ymd') . sha1($this->apiPassword));
    }

    /**
     * Generate request body.
     *
     * @return false|string
     */
    protected function generateRequestBody()
    {
        $request = array();
        $request['authenticate'] = array();
        $request['authenticate']['systemKey'] = $this->generateIdosellPassword();
        $request['authenticate']['systemLogin'] = $this->apiLogin;
        $request['authenticate']['lang'] = 'eng';

        return json_encode($request);

    }

    /**
     * Import reservations from API.
     *
     * @return string
     * @throws GuzzleException
     */
    public function getReservationsFromApi(): string
    {

        $client = new Client();

        $requestJson = $this->generateRequestBody();

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json;charset=UTF-8'
        );

        try {
            $response = $client->request("GET", $this->getReservationsUrl,
                ['body' => $requestJson, 'headers' => $headers]);
            return $response->getBody()->getContents();

        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

    }

    public function getEventDetails(int $eventId)
    {
        // TODO: Implement getEventDetails() method.
    }
}