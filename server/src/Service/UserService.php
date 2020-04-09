<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class UserService.
 *
 * @package App\Service
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class UserService
{

    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * Find active user by email.
     *
     * @param $email
     * @return object|string
     */
    public function getUserByEmail($email)
    {
        try {
            $rep = $this->em->getRepository('App:User');

            $user = $rep->findBy(
                array("is_active" => true, "email" => $email),
                array(),
                array(1)
            );
        } catch (\Exception $exception) {
                return $exception->getMessage();
        }

        if (isset($user) && isset($user[0])) {
            return $user[0];
        } else {
            return sprintf("Can't find user with email %s", $email);
        }
    }

    /**
     * Find active user by id.
     *
     * @param $id
     * @return object|string
     */
    public function getActiveUserById($id)
    {
        $user = $this->em->getRepository('App:User')->findBy(
            array("is_active" => true, "id" => $id),
            array(),
            array(1)
        );

        if (isset($user) && isset($user[0])) {
            return $user[0];
        } else {
            return sprintf("Can't find user!");
        }
    }

    /**
     * Get user by id no matter of status is_active.
     *
     * @param $id
     * @return object|string
     */
    public function getUserById($id)
    {
        $user = $this->em->getRepository('App:User')->findBy(
            array("id" => $id),
            array(),
            array(1)
        );

        if (isset($user) && isset($user[0])) {
            return $user[0];
        } else {
            return sprintf("Can't find user!");
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
    public function createUser($data)
    {
        $email = $data['email'];
        $plainPassword = $data['password'];
        /** @var TYPE_NAME $isActive */
        $isActive = isset($data['is_active']) ? $data['is_active'] : true;
        $firstName = isset($data['first_name']) ? $data['first_name'] : '';
        $lastName = isset($data['last_name']) ? $data['last_name'] : '';

        $user = new User();
        $user->setEmail($email);
        $encoded = password_hash($plainPassword, PASSWORD_DEFAULT);
        $user->setPassword($encoded);
        $user->setIsActive($isActive);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);

        try {
            $this->em->persist($user);
            $this->em->flush();

            return $user;
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
            return sprintf("User with email %s already exist!", $email);
        } catch (\Exception $ex) {
            return "Error with creating user";
        }
    }

    /**
     * Update user.
     *
     * @param User $user
     * @param array $data
     * @return User|string
     */
    public function updateUser(User $user, array $data)
    {
        try {
            if (isset($data['first_name']) && !empty($data['first_name'])) {
                $user->setFirstName($data['first_name']);
            }

            if (isset($data['last_name']) && !empty($data['last_name'])) {
                $user->setLastName($data['last_name']);
            }

            if (isset($data['password']) && !empty($data['password'])) {
                $encoded = password_hash($data['password'], PASSWORD_DEFAULT);
                $user->setPassword($encoded);
            }

            $this->em->persist($user);
            $this->em->flush();

            return $user;
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
            return "User with given name already exists";
        } catch (\Exception $ex) {
            return "Unable to update user";
        }
    }

    /**
     * Soft delete user (change is_active = 0).
     *
     * @param User $user
     * @return User|string
     */
    public function deleteUser(User $user)
    {
        $user->setIsActive(false);

        try {
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        } catch (Exception $ex) {
            return "Nie udało się usunąć użytkownika";
        }
    }

}
