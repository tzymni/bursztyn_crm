<?php

namespace App\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class UsersService.
 *
 * @package App\Service
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class UsersService
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
            $rep = $this->em->getRepository('App:Users');

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
        $user = $this->em->getRepository('App:Users')->findBy(
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
        $user = $this->em->getRepository('App:Users')->findBy(
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
     *      'name' => (string) Users name. Required.
     *      'password' => (string) Users (plain) password. Required.
     *    ]
     * @return Users|string Users entity or string in case of error
     */
    public function createUser($data)
    {
        $email = $data['email'];
        $plainPassword = $data['password'];
        /** @var TYPE_NAME $isActive */
        $isActive = isset($data['is_active']) ? $data['is_active'] : true;
        $firstName = isset($data['first_name']) ? $data['first_name'] : '';
        $lastName = isset($data['last_name']) ? $data['last_name'] : '';

        $user = new Users();
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
            return sprintf("Users with email %s already exist!", $email);
        } catch (\Exception $ex) {
            return "Error with creating user";
        }
    }

    /**
     * Update user.
     *
     * @param Users $user
     * @param array $data
     * @return Users|string
     */
    public function updateUser(Users $user, array $data)
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
            return "Users with given name already exists";
        } catch (\Exception $ex) {
            return "Unable to update user";
        }
    }

    /**
     * Soft delete user (change is_active = 0).
     *
     * @param Users $user
     * @return Users|string
     */
    public function deleteUser(Users $user)
    {
        $user->setIsActive(false);

        try {
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        } catch (Exception $ex) {
            return "Cant remove user!";
        }
    }

}
