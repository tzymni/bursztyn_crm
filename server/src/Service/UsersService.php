<?php

namespace App\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UsersService.
 *
 * @package App\Service
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class UsersService
{

    /**
     * @var EntityManagerInterface
     */
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
        $isActive = $data['is_active'] ?? true;
        $firstName = $data['first_name'] ?? '';
        $lastName = $data['last_name'] ?? '';
        $daysBeforeNotification = $data['days_before_notification'] ?? 0;

        $user = new Users();
        $user->setEmail($email);
        $encoded = password_hash($plainPassword, PASSWORD_DEFAULT);
        $user->setPassword($encoded);
        $user->setIsActive($isActive);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setDaysBeforeNotification($daysBeforeNotification);

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

            if (isset($data['days_before_notification'])) {
                $user->setDaysBeforeNotification($data['days_before_notification']);
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

    /**
     * Find all active users.
     *
     * @return array
     */
    public function getActiveUsers(): array
    {

        $userRepository = $this->em->getRepository(Users::class);
        $users = null;
        if ($userRepository instanceof UsersRepository) {
            $users = $userRepository->findAllActiveUsers();
        } else {
            $users = array();
        }

        return $users;
    }
    /**
     * Find all active users with enabled cleaning notifications.
     *
     * @return array
     */
    public function getUsersWithEnabledCleaningNotifications(): array
    {

        $userRepository = $this->em->getRepository(Users::class);
        $users = null;
        if ($userRepository instanceof UsersRepository) {
            $users = $userRepository->findUsersWithEnabledNotifications();
        } else {
            $users = array();
        }

        return $users;
    }
}
