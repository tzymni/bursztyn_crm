<?php

namespace App\Tests\Controller;

use App\Service\EventsService;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class EventsControllerTest extends BaseTestCase
{

    public function testCreateReservationEvent__When_All_Data_Is_Provided___Returns_Success()
    {
        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => time(),
            'date_to' => strtotime("+1 week"),
            'type' => EventsService::RESERVATION_EVENT,
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'guests_number' => rand(0, 6),
            'advance_payment' => true,
            'extra_info' => 'Piesek'
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEmpty($responseData);
    }

    public function testCreateReservationEvent__WhenUserIsInactive__ReturnsError()
    {
        $data = array(
            'user_id' => $this->testInactiveUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => time(),
            'date_to' => strtotime("+1 week"),
            'type' => EventsService::RESERVATION_EVENT,
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'guests_number' => rand(0, 6),
            'advance_payment' => true,
            'extra_info' => 'Piesek'
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals($responseData['error']['message'], "Can't find user!");
    }

    public function testCreateReservationEvent__WhenCottageIsInactive__ReturnsError()
    {
        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testInactiveCottage->getId(),
            'date_from' => time(),
            'date_to' => strtotime("+1 week"),
            'type' => EventsService::RESERVATION_EVENT,
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'guests_number' => rand(0, 6),
            'advance_payment' => true,
            'extra_info' => 'Piesek'
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals($responseData['error']['message'], "Can't find cottage!");
    }

    public function testCreateReservationEvent__WhenDateIsInString__ReturnsError()
    {
        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => '2020-04-11',
            'date_to' => 'DUPKA',
            'type' => EventsService::RESERVATION_EVENT,
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'guests_number' => rand(0, 6),
            'advance_payment' => true,
            'extra_info' => 'Piesek'
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals($responseData['error']['message'], "Wrong date format!");
    }

    public function testCreateReservationEvent__WhenDataGuestsEmpty__ReturnsError()
    {
        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => time(),
            'date_to' => strtotime("+1 week"),
            'type' => EventsService::RESERVATION_EVENT,
            'guest_first_name' => '',
            'guest_last_name' => '',
            'guest_phone_number' => '111 222 333',
            'guests_number' => rand(0, 6),
            'advance_payment' => true,
            'extra_info' => 'Piesek'
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals($responseData['error']['message'], "Invalid data!");
    }

    public function testCreateReservationEvent__WhenNotRequiredDataIsNotProvided__ReturnsSuccess()
    {
        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => time(),
            'date_to' => strtotime("+1 week"),
            'type' => EventsService::RESERVATION_EVENT,
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'advance_payment' => true,
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertEmpty($responseData);
    }

    public function testCreateReservationEvent__WhenWrongType__ReturnsError()
    {
        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => time(),
            'date_to' => strtotime("+1 week"),
            'type' => 'OTHER',
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'advance_payment' => true,
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals($responseData['error']['message'], 'Wrong type!');
    }

    public function testGetActiveEvents__WhenValidToken__ReturnSuccess()
    {
        $this->testCreateReservationEvent__When_All_Data_Is_Provided___Returns_Success();

        $token = $this->getValidToken();

        $response = $this->client->get(
            "/event/list",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey("id", $responseData[0]);
        $this->assertArrayHasKey("title", $responseData[0]);
        $this->assertArrayHasKey("color", $responseData[0]);
        $this->assertArrayHasKey("date_from", $responseData[0]);
        $this->assertArrayHasKey("date_to", $responseData[0]);

        foreach ($responseData as $data) {
            $this->assertIsNumeric($data['id']);
        }
    }

    public function testAddEvents__WhenSameDateAndSameCottage__ReturnsError()
    {
        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => '2020-04-18',
            'date_to' => '2020-04-25',
            'type' => 'reservation',
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'advance_payment' => true,
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => '2020-04-18',
            'date_to' => '2020-04-25',
            'type' => 'reservation',
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'advance_payment' => true,
        );

        $token = $this->getValidToken();
        $response = $this->client->post(
            "/event/reservation",
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $this->assertEquals(
            $responseData['error']['message'],
            'There is a reservation between 2020-04-18 and 2020-04-25 for cottage CottageTest'
        );
    }

    public function testGetReservationByIdEvent__WhenIdIsProvided__ReturnSuccess()
    {
        $testReservation = $this->testReservation;
        $token = $this->getValidToken();

        $response = $this->client->get(
            "/event/" . $testReservation->getId() . "/type/" . EventsService::RESERVATION_EVENT,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('event', $responseData);
        $this->assertArrayHasKey('event_id', $responseData['event']);
        $this->assertArrayHasKey('date_from_unix_utc', $responseData['event']);
        $this->assertArrayHasKey('date_from', $responseData['event']);
        $this->assertArrayHasKey('date_to', $responseData['event']);
        $this->assertArrayHasKey('date_to_unix_utc', $responseData['event']);
        $this->assertArrayHasKey('reservation_id', $responseData['details']);
        $this->assertArrayHasKey('guest_first_name', $responseData['details']);
        $this->assertArrayHasKey('guest_last_name', $responseData['details']);
        $this->assertArrayHasKey('guest_phone_number', $responseData['details']);
        $this->assertArrayHasKey('guests_number', $responseData['details']);
        $this->assertArrayHasKey('advance_payment', $responseData['details']);
        $this->assertArrayHasKey('extra_info', $responseData['details']);
        $this->assertArrayHasKey('cottage_id', $responseData['details']);
        $this->assertNotNull($responseData['details']['cottage_id']);
        $this->assertNotNull($responseData['details']['reservation_id']);
        $this->assertEquals($testReservation->getId(), $responseData['event']['event_id']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateEvent__WhenAllDataIsProvided__ReturnSuccess()
    {
        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => '2020-05-31',
            'date_to' => '2020-06-06',
            'type' => 'reservation',
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'advance_payment' => true,
        );

        $testReservation = $this->testReservation;

        $token = $this->getValidToken();
        $response = $this->client->put(
            "/event/reservation/" . $testReservation->getId(),
            [
                'body' => json_encode($data),
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

}