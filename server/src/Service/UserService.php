<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class UserService {

    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function getUser($email) {
        $user = $this->em->getRepository('App:User')
                ->findOneBy(['email' => $email]);

        if ($user) {
            return $user;
        } else {
            return sprintf("Can't find user with email %s", $email);
        }
    }

    /**
     * @param $data
     *    $data = [
     *      'name' => (string) User name. Required.
     *      'password' => (string) User (plain) password. Required.
     *    ]
     * @return User|string User entity or string in case of error
     */
    public function createUser($data) {
        $email = $data['email'];
        $plainPassword = $data['password'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];

        $user = new User();
        $user->setEmail($email);
        $encoded = password_hash($plainPassword, PASSWORD_DEFAULT);
        $user->setPassword($encoded);
        $user->setIsActive(1);
        $user->setFirstName($first_name);
        $user->setLastName($last_name);

        try {
            $this->em->persist($user);
            $this->em->flush();

            return $user;
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
            return "Użytkownik z takim adresem email już istnieje";
        } catch (\Exception $ex) {
            return "Nie udało się stworzyć użytkownika";
        }
    }

    public function updateUser(User $user, array $data) {

        try {
            if (isset($data['first_name'])) {
                $user->setFirstName($data['first_name']);
            }

            if (isset($data['last_name'])) {
                $user->setLastName($data['last_name']);
            }

            $this->em->persist($user);
            $this->em->flush();

            return $user;
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
            return "user with given name already exists";
        } catch (\Exception $ex) {
            return "Unable to update user";
        }
    }

    public function deleteUser(User $user) {

        $user->setIsActive(0);

        try {
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        } catch (Exception $ex) {
            return "Nie udało się usunąć użytkownika";
        }
    }

}
