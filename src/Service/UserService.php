<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ApiTokenService
     */
    private $tokenService;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, ApiTokenService $tokenService){
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->tokenService = $tokenService;
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
     * @param int $userId
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUserBy($field, $value): User
    {
        $user = $this->userRepository->findOneBy(array($field => $value));
        if (!$user) {
            throw new EntityNotFoundException('User with field '. $field . ' containing value ' .$value.' does not exist!');
        }

        return $user;
    }


    /**
     * @return User[]
     */
    public function getAllUsers()
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


    public function deleteUser(int $userId): void
    {
        $user = $this->getUser($userId);
        $this->tokenService->deleteApiTokensForUser($user);

        if ($user) {
            $this->userRepository->delete($user);
        }
    }

}