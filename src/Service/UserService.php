<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class UserService
 * @package App\Application\Service
 */
final class UserService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $userId
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUser(int $userId): User
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }

        return $user;
    }

    /**
     * @return array|null
     */
    public function getAllUsers(): ?array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param string $title
     * @return User
     */
    public function addUser(string $title): User
    {
        $user = new User();
        $user->setFirstName($title);
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @param int $userId
     * @param string $title
     * @param string $content
     * @return User
     * @throws EntityNotFoundException
     */
    public function updateUser(int $userId, string $title, string $content): User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }

        $user->setFirstName($title);
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @param int $userId
     * @throws EntityNotFoundException
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }

        $this->userRepository->delete($user);
    }

}