<?php

namespace App\Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * Middleware between PHPMailer library and project code.
 *
 * @package App\Lib
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class MailerMiddleware
{

    /**
     * @var PHPMailer
     */
    public $mailer;

    /**
     * @var ContainerBagInterface
     */
    public $containerBag;

    /**
     * MailerMiddleware constructor.
     */
    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->containerBag = $containerBag;
        $this->mailer = new PHPMailer();
        $this->mailer->CharSet = "UTF-8";
        $this->configure();
    }

    /**
     * Configure SMTP.
     */
    private function configure()
    {

        $host = $this->containerBag->get('smtp_host');
        $port = $this->containerBag->get('smtp_port');
        $login = $this->containerBag->get('smtp_login');
        $password = $this->containerBag->get('smtp_password');

        $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->mailer->isSMTP();                                            //Send using SMTP
        $this->mailer->Host = $host;                     //Set the SMTP server to send through
        $this->mailer->SMTPAuth = true;                                   //Enable SMTP authentication
        $this->mailer->Username = $login;                     //SMTP username
        $this->mailer->Password = $password;                               //SMTP password
//        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->mailer->Port = $port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    }

    /**
     * Enable HTML.
     */
    public function enableHTML()
    {
        $this->mailer->isHTML(true);
    }

    /**
     * Add addresses.
     *
     * @param $address
     * @param $name
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function addAddress($address, $name)
    {
        $this->mailer->addAddress($address, $name);
    }

    /**
     * Add subject.
     *
     * @param $subject
     */
    public function addSubject($subject)
    {
        $this->mailer->Subject = $subject;
    }

    /**
     * Add email body.
     *
     * @param $body
     */
    public function addBody($body)
    {
        $this->mailer->Body = $body;
    }

    /**
     * Send email.
     *
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send()
    {
        $mail = $this->mailer;

        try {
            $mail->setFrom('admin@bursztyn-wicie.pl', 'Bursztyn Wicie');
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}