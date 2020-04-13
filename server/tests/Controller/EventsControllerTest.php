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
            "/event/addReservation",
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
            "/event/addReservation",
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
            "/event/addReservation",
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
            'date_to' => '2020-04-18',
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
            "/event/addReservation",
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
            "/event/addReservation",
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
            "/event/addReservation",
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
            "/event/addReservation",
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

}