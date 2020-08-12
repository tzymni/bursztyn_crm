<?php

namespace App\Tests\Controller;

use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class ReservationsControllerTest extends BaseTestCase
{

    public function testGetReservationListGroupedByCottages()
    {
        $token = $this->getValidToken();

        $response = $this->client->get(
            "/reservation/list/groupBy/cottages",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );
        $responseData = json_decode($response->getBody(), true);

        print_r($responseData);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertArrayHasKey("cottages", $responseData);

        $this->assertNotNull($responseData['cottages']);

        $this->assertArrayHasKey("name", $responseData['cottages'][0]);
        foreach ($responseData['cottages'] as $data) {
            $this->assertIsNumeric($data['id']);
            $this->assertNotNull($data['name']);
            $this->assertNotNull($data['id']);
        }
    }

}