<?php

namespace App\Tests;

use App\Entity\Cottages;
use App\Entity\Events;
use App\Entity\FootballLeague;
use App\Entity\FootballTeam;
use App\Entity\User;
use App\Service\EventsService;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class BaseTestCase
 * @package App\Tests
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class BaseTestCase extends KernelTestCase
{
    const TEST_USER_PASSWORD = "test";
    const TEST_USER_EMAIL = "test@gmail.com";

    const TEST_INACTIVE_USER_PASSWORD = "testInactive";
    const TEST_INACTIVE_USER_EMAIL = "test-inactive@gmail.com";

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var User
     */
    protected $testUser;

    /**
     * @var User
     */
    protected $testInactiveUser;

    /**
     * @var Cottages
     */
    protected $testCottage;

    /**
     * @var Cottages
     */
    protected $testInactiveCottage;
    /**
     * @var array
     */
    protected $testReservation;
    /**
     * @var string
     */
    const testCottageName = 'CottageTest';

    public function setUp()
    {
        $this->client = new \GuzzleHttp\Client(
            [
                'base_uri' => 'http://localhost:8000/api/',
                'exceptions' => false
            ]
        );

        $container = $this->getPrivateContainer();

        $this->em = $container
            ->get('doctrine')
            ->getManager();

        $this->truncateTestReservations();
        $this->truncateTestCottages(self::testCottageName);
        $this->truncateTestEvents();
        $this->truncateTestUsers();


        $this->testUser = $this->createTestUser();
        $this->testInactiveUser = $this->createTestUser(
            self::TEST_INACTIVE_USER_EMAIL,
            self::TEST_INACTIVE_USER_PASSWORD
        );

        $this->testCottage = $this->createTestCottage(array('name' => self::testCottageName, 'color' => '#f8fc00'));
        $this->testInactiveCottage = $this->createTestCottage(
            array('name' => self::testCottageName, 'color' => '#f8fc00', 'is_active' => false)
        );

        $this->testReservation = $this->createTestReservation();
    }

    protected function truncateTestReservations()
    {
        $em = $this->em;

        $query = $em->createQuery(
//            "DELETE App:Reservations u WHERE u.guest_first_name Like '" . self::TEST_USER_EMAIL . "'"
            "DELETE from App:Reservations"
        );
        $query->execute();
        parent::tearDown();
    }

    protected function truncateTestCottages($testCottageName)
    {
        $em = $this->em;
        $testNames = array($testCottageName);

        $testNamesString = "'" . implode("','", $testNames) . "'";
        $query = $em->createQuery(
            "DELETE App:Cottages u WHERE u.name IN ({$testNamesString})"
        );
        $query->execute();
        parent::tearDown();
//
//        $this->em->close();
//        $this->em = null; // avoid memory leaks

    }

    protected function truncateTestEvents()
    {
        $em = $this->em;

        $query = $em->createQuery(
//            "DELETE from App:Events u WHERE u.created_by IS NOT NULL "
            "DELETE from App:Events "
        );
        $query->execute();
        parent::tearDown();
    }

    protected function truncateTestUsers()
    {
        $em = $this->em;
        $testEmails = array(self::TEST_USER_EMAIL, self::TEST_INACTIVE_USER_EMAIL);

        $testEmailsString = "'" . implode("','", $testEmails) . "'";
        $query = $em->createQuery(
            "DELETE App:User u WHERE u.email IN ({$testEmailsString})  OR u.email LIKE '%@test-create.pl'"
        );
        $query->execute();
        parent::tearDown();
    }

    protected function createTestReservation() {

        $data = array(
            'user_id' => $this->testUser->getId(),
            'cottage_id' => $this->testCottage->getId(),
            'date_from' => strtotime("+3 weeks"),
            'date_to' => strtotime("+5 weeks"),
            'type' => EventsService::RESERVATION_EVENT,
            'guest_first_name' => self::TEST_USER_EMAIL,
            'guest_last_name' => 'Test last name',
            'guest_phone_number' => '111 222 333',
            'guests_number' => rand(0, 6),
            'advance_payment' => true,
            'extra_info' => 'Piesek'
        );
        $container = $this->getPrivateContainer();
        $eventsService = $container
            ->get('App\Service\EventsService');

        return $eventsService->createEvent($data);
    }

    protected function createTestUser($email = self::TEST_USER_EMAIL, $password = self::TEST_USER_PASSWORD)
    {
        $container = $this->getPrivateContainer();
        $userService = $container
            ->get('App\Service\UserService');

        if ($email == self::TEST_INACTIVE_USER_EMAIL) {
            $conditions = [
                'email' => $email,
                'password' => $password,
                'is_active' => false
            ];
        } else {
            $conditions = [
                'email' => $email,
                'password' => $password,
                'first_name' => 'Test user name',
                'last_name' => 'Test user last name'
            ];
        }

        return $userService->createUser($conditions);
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function createTestCottage($cottageData)
    {
        $container = $this->getPrivateContainer();

        $cottageService = $container->get('App\Service\CottageService');

        return $cottageService->createCottage($cottageData);
    }



    /**
     * Create valid JWT token for given (if any) or this user
     *
     * @param User|null $user
     * @return string Valid JWT token to use for REST API endpoints authentication
     */
    protected function getValidToken(User $user = null)
    {
        if (!$user) {
            $user = $this->testUser;
        }

        $container = $this->getPrivateContainer();
        $authService = $container
            ->get('App\Service\AuthService');

        $jwt = $authService->authenticate(
            [
                'email' => $user->getEmail()
            ]
        );

        return $jwt;
    }

    protected function getPrivateContainer()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

}