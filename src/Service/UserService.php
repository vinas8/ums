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
     * @var ApiTokenRepository
     */
    private $tokenRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, ApiTokenRepository $tokenRepository){
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->em = $em;
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

//    /**
//     * @param int $userId
//     * @throws EntityNotFoundException
//     */
//    public function deleteUser(User $user): void
//    {
////        $this->em->remove($token);
//        $this->getUserBy('id', $user);
//
//        if ($user) {
//            $em = $this->em;
//            $em->remove($user);
//            $em->flush();
//        }
//
////        if (!$user) {
////            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
////        }
//
////        $this->userRepository->delete($user);
//    }

    public function deleteUser(int $userId): void
    {
        $user = $this->getUser($userId);

        if ($user) {
            $this->userRepository->delete($user);
        }


    }

    /**
     * @param int $articleId
     * @throws EntityNotFoundException
     */
    public function deleteArticle(int $articleId): void
    {
        $article = $this->articleRepository->findById($articleId);
        if (!$article) {
            throw new EntityNotFoundException('Article with id '.$articleId.' does not exist!');
        }

        $this->articleRepository->delete($article);
    }

}