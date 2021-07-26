<?php

namespace App\Command;

use App\Entity\CottagesCleaningEvents;
use App\Entity\UserPresences;
use App\Lib\MailerMiddleware;
use App\Service\CottagesCleaningEventsService;
use App\Service\EventsService;
use App\Service\UsersService;
use AsciiTable\Exception\BuilderException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * Class to generate and send email notifications about cleanings.
 *
 * @package App\Command
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CleaningNotificationCommand extends Command
{

    protected static $defaultName = 'cleaning-notification';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var UsersService
     */
    protected $usersService;

    /**
     * @var EventsService
     */
    protected $eventsService;

    /**
     * @var CottagesCleaningEventsService
     */
    protected $cottagesCleaningEventsService;

    /**
     * @var ContainerBagInterface
     */
    protected $containerBag;

    /**
     * CleaningNotificationCommand constructor.
     */
    public function __construct(
        UsersService $usersService,
        EventsService $eventsService,
        CottagesCleaningEventsService $cottagesCleaningEventsService,
        ContainerBagInterface $containerBag
    ) {
        $this->containerBag = $containerBag;
        $this->usersService = $usersService;
        $this->eventsService = $eventsService;
        $this->cottagesCleaningEventsService = $cottagesCleaningEventsService;
        parent::__construct();
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws BuilderException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $users = $this->usersService->getUsersWithEnabledCleaningNotifications();

        foreach ($users as $user) {

            $email = $user['email'];
            $daysToCleaning = $user['days_before_notification'];
            $notificationCleaningDay = gmdate("Y-m-d", strtotime("+" . $daysToCleaning . " day"));

            $cleaningEvent = $this->eventsService->getActiveEventsByStartDate($notificationCleaningDay,
                CottagesCleaningEvents::EVENT_TYPE);

            if (empty($cleaningEvent)) {
                continue;
            }
            $cleaningEvent = current($cleaningEvent);
            $title = $this->createTitle($cleaningEvent);
            $body = $this->createBody($cleaningEvent, $daysToCleaning);

            $mailer = new MailerMiddleware($this->containerBag);
            $mailer->addSubject($title);
            $mailer->enableHTML();

            $body = (join("<br/>", $body));
            $mailer->addBody($body);
            $mailer->addAddress($email, $user['first_name']);
            $mailer->send();

        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }

    /**
     * @param $cleaningEvent
     * @return string
     */
    protected function createTitle($cleaningEvent): string
    {
        $cleaningStartDate = substr($cleaningEvent->getDateFrom(), 0, 10);
        return sprintf("Przypomnienie o zmianach (%s)", $cleaningStartDate);
    }

    /**
     * @param $cleaningEvent
     * @param $daysToCleaning
     * @return array
     * @throws BuilderException
     */
    protected function createBody($cleaningEvent, $daysToCleaning): array
    {

        $body = array();
        $cleaningStartDate = substr($cleaningEvent->getDateFrom(), 0, 10);
        $body[] = "Witaj! <br/>";
        $body[] = sprintf("Otrzymujesz tego maila w ramach przypomnienia, że za %d dni (%s) będą zmiany w Bursztynie. Poniżej szczegóły: ",
            $daysToCleaning, $cleaningStartDate);
        $body[] = $this->prepareUserPresenceInformation($cleaningStartDate);
        $body[] = $this->prepareCleaningDetails($cleaningEvent);

        return $body;
    }

    /**
     * @param $cleaningStartDate
     * @return string
     */
    protected function prepareUserPresenceInformation($cleaningStartDate): string
    {
        $presenceEvents = $this->eventsService->getActiveEventsBetweenDate(UserPresences::EVENT_TYPE,
            $cleaningStartDate);

        $tmpBody = "<br/>Kto będzie:<br/>";

        if (empty($presenceEvents)) {
            $tmpBody .= "Nikt nie potwierdził obecności :( <br/>";
        } else {

            foreach ($presenceEvents as $presenceEvent) {
                $tmpBody .= "- " . $presenceEvent->getTitle() . " <br/>";
            }

        }

        return $tmpBody;
    }

    /**
     * @param $cleaningEvent
     * @return string
     * @throws BuilderException
     */
    protected function prepareCleaningDetails($cleaningEvent): string
    {

        $cleaningDetails = $this->cottagesCleaningEventsService->generateCottageCleaningEventDetails($cleaningEvent->getId());

        $cleaningDetails = array_map(
            function (array $elem) {
                unset($elem['cottage_id']);        // modify $elem
                unset($elem['next_reservation_event_id']);        // modify $elem
                unset($elem['next_reservation_id']);

                $elem['next_reservation_period'] = intval($elem['next_reservation_period']);

                return $elem;             // and return it to be put into the result
            },
            $cleaningDetails
        );

        $cleaningDetails = array_map(function ($tag) {
            return array(
                'Domek' => $tag['cottage_name'],
                'Data następnej rezerwacji' => $tag['next_reservation_date'],
                'Liczba dni następnej rezerwacji' => $tag['next_reservation_period']
            );
        }, $cleaningDetails);

        return $this->buildTable($cleaningDetails);
    }

    /**
     * Build HTML table from PHP array.
     *
     * @param $array
     * @return string
     */
    public function buildTable($array): string
    {
        // start table
        $html = '<table>';
        // header row
        $html .= '<tr>';
        foreach ($array[0] as $key => $value) {
            $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }
        $html .= '</tr>';

        // data rows
        foreach ($array as $key => $value) {
            $html .= '<tr>';
            foreach ($value as $key2 => $value2) {
                $html .= '<td>' . htmlspecialchars($value2) . '</td>';
            }
            $html .= '</tr>';
        }

        // finish table and return it

        $html .= '</table>';
        return $html;
    }
}
