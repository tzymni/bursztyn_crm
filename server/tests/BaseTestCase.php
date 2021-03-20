<?php

namespace App\Tests;

use App\Entity\Cottages;
use App\Entity\Events;
use App\Entity\FootballLeague;
use App\Entity\FootballTeam;
use App\Entity\Users;
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
    /**
     * Test password for a test user.
     */
    public const TEST_USER_PASSWORD = "test";

    /**
     * Test email for a test user.
     */
    public const TEST_USER_EMAIL = "test@gmail.com";

    /**
     * Test password for a test (inactive) user.
     */
    public const TEST_INACTIVE_USER_PASSWORD = "testInactive";

    /**
     * Test email for a inactive user.
     */
    public const TEST_INACTIVE_USER_EMAIL = "test-inactive@gmail.com";

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Users
     */
    protected $testUser;

    /**
     * @var Users
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
    public const testCottageName = 'CottageTest';

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

        $this->createNewTestObjects();
    }

    /**
     * Clean up database from test records.
     *
     */
    public function cleanUpDatabaseFromTestRecords()
    {
        $reservationIds = $this->getTestReservationIds();
        $this->truncateTestReservations($reservationIds);
        $eventIds = $this->getTestEventIds();
        $this->truncateTestEvents($eventIds);
        $this->truncateTestCottages(self::testCottageName);
        $this->truncateTestUsers();
    }

    /**
     * Create new test objects required for unit tests.
     */
    public function createNewTestObjects()
    {
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

    /**
     * Get test reservation ids.
     *
     * @return array
     */
    protected function getTestReservationIds(): array
    {
        $reservations = $this->em->createQueryBuilder('p')
            ->select(
                'p.id as reservation_id, Events.id as event_id '
            )
            ->from('App:Reservations', 'p')
            ->andWhere('p.guest_first_name = :guest_first_name')
            ->setParameter('guest_first_name', self::TEST_USER_EMAIL)
            ->leftJoin('p.event', 'Events')
            ->leftJoin('Events.created_by', 'Users')
            ->getQuery()->execute();

        $reservationIds = array();

        foreach ($reservations as $reservation) {
            $reservationIds[] = $reservation['reservation_id'];
        }

        return $reservationIds;
    }

    /**
     * Get test event ids (find by test user email).
     *
     * @return array
     */
    protected function getTestEventIds(): array
    {
        $events = $this->em->createQueryBuilder('p')
            ->select(
                'Event.id as event_id, Event.date_from, Users.id as user_id, Users.email, Event.date_to'
            )
            ->from('App:Events', 'Event')
            ->leftJoin('Event.created_by', 'Users')
            ->andWhere('Users.email = :email')
            ->setParameter('email', self::TEST_USER_EMAIL)
            ->getQuery()->execute();

        $eventIds = array();
        foreach ($events as $event) {
            $eventIds[] = $event['event_id'];
        }

        return $eventIds;
    }

    /**
     * Remove test reservations from database.
     *
     * @param array $reservationIds
     */
    protected function truncateTestReservations(array $reservationIds): void
    {
        $em = $this->em;

        if (empty($reservationIds)) {
            return;
        }

        $reservationIds = join(",", $reservationIds);
        $query = $em->createQuery(
            "DELETE from App:Reservations r WHERE r.id IN (" . $reservationIds . ")"
        );
        $query->execute();

        parent::tearDown();
    }

    /**
     * Remove test cottages from database.
     *
     * @param $testCottageName
     */
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
    }

    /**
     * Remove test events from database.
     *
     * @param array $eventIds
     */
    protected function truncateTestEvents(array $eventIds): void
    {
        if (empty($eventIds)) {
            return;
        }

        $this->em->createQueryBuilder('p')
            ->select(
                'Event.id as event_id'
            )
            ->from('App:Events', 'Event')
            ->andWhere('Event.id IN (:eventIds)')
            ->setParameter('eventIds', $eventIds)
            ->delete()
            ->getQuery()->execute();

        parent::tearDown();
    }

    /**
     * Remove test users from database.
     */
    protected function truncateTestUsers(): void
    {
        $em = $this->em;
        $testEmails = array(self::TEST_USER_EMAIL, self::TEST_INACTIVE_USER_EMAIL);

        $testEmailsString = "'" . implode("','", $testEmails) . "'";
        $query = $em->createQuery(
            "DELETE App:Users u WHERE u.email IN ({$testEmailsString})  OR u.email LIKE '%@test-create.pl'"
        );
        $query->execute();
        parent::tearDown();
    }

    /**
     * Create a test reservation.
     *
     * @return mixed
     */
    protected function createTestReservation()
    {

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

    /**
     * Create test user.
     *
     * @param string $email
     * @param string $password
     * @return mixed
     */
    protected function createTestUser($email = self::TEST_USER_EMAIL, $password = self::TEST_USER_PASSWORD)
    {
        $container = $this->getPrivateContainer();
        $userService = $container
            ->get('App\Service\UsersService');

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
     * Create test cottage.
     *
     * @param $cottageData
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
     * @param Users|null $user
     * @return string Valid JWT token to use for REST API endpoints authentication
     */
    protected function getValidToken(Users $user = null)
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
        $this->cleanUpDatabaseFromTestRecords();
    }

}