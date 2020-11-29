<?php


namespace App\Service;


use App\Service\interfaces\DecorateEventInterface;
use GuzzleHttp\Client;
use Monolog\Handler\AmqpHandler;
use PHPUnit\Runner\Exception;
use Psr\Log\LoggerInterface;

/**
 * Class ImportFromAPIService
 * @package App\Service
 */
class ImportFromAPIService implements DecorateEventInterface
{
    const API_PASSWORD = "aF676hbEFAf7";

    const API_LOGIN = "tomzym";

    const GET_COTTAGES_URL = "https://client8940.idosell.com/api/objects/getAll/21/json";

    const GET_RESERVATIONS_URL = "https://client8940.idosell.com/api/reservations/get/21/json";

    public $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getEventDetails(int $eventId)
    {
        // TODO: Implement getEventDetails() method.
    }

    /**
     * Import cottages from API and return them in JSON format.
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function importCottages()
    {
        $this->logger->info("Started importing");
        echo "Importuje domki";

        try {

            $client = new Client();

            $requestJson = $this->generateBody();

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json;charset=UTF-8'
            );

            $response = $client->request("GET", self::GET_COTTAGES_URL,
                ['body' => $requestJson, 'headers' => $headers]);
            return $response->getBody()->getContents();

        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

    }

    protected function generateBody()
    {
        $request = array();
        $request['authenticate'] = array();
        $request['authenticate']['systemKey'] = sha1(date('Ymd') . sha1(self::API_PASSWORD));
        $request['authenticate']['systemLogin'] = self::API_LOGIN;
        $request['authenticate']['lang'] = 'eng';
        $request['result'] = array();
        $request['result']['page'] = 1;
        $request['result']['number'] = 2;

        return json_encode($request);

    }

    public function importReservations()
    {

        echo "Importuje rezerwacje";

        $client = new Client();

        $requestJson = $this->generateBody();

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json;charset=UTF-8'
        );

        try {
            $response = $client->request("GET", self::GET_RESERVATIONS_URL,
                ['body' => $requestJson, 'headers' => $headers]);

        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

        return $response->getBody()->getContents();
    }
}