<?php

namespace App\Tests\Controller;

use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CotagesControllerTest
 * @package App\Tests\Controller
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CottagesControllerTest extends BaseTestCase
{

    public function testGetActiveCottages__WhenTokenProvided__ResponseSuccess()
    {
        $token = $this->getValidToken();

        $response = $this->client->get(
            "/api/cottages",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );
        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        if(!empty($responseData)) {

        }

    }


    /**
     * Check if http_code for invalid token equals 403.
     *
     */
    public function testGetActiveCottages__whenTokenInvalid__returnErrorResponse()
    {
        $token = sha1(time());
        $response = $this->client->get(
            "/api/users",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

}