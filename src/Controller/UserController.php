<?php

namespace App\Controller;

use App\Api\ApiMessage;
use App\Entity\User;
use App\Form\UsersType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class UserController
 * @Rest\Route("/api")
 */
class UserController extends BaseController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserService
     */
    private $userService;


    public function __construct(
        EntityManagerInterface $entityManager,
        UserService $userService
    ) {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
    }

    /**
     * As an admin I can add users. A user has a name.
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     type="string",
     *     description="User name"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="User created successfuly",
     *     @SWG\Schema(
     *          @SWG\Property(
     *              property="status",
     *              type="string",
     *              default="success"
     *          ),
     *          @SWG\Property(
     *              property="message",
     *              type="string",
     *              default="User created successfuly"
     *          ),
     *      )
     * )
     *
     * @param  Request $request
     * @return View
     */
    public function postUserAction(Request $request)
    {
        $form = $this->createForm(UsersType::class, new User());

        $form->submit(
            $request->request->all()
        );

        if (!$form->isValid()) {
            return $this->view($form);
        }

        $this->entityManager->persist($form->getData());
        $this->entityManager->flush();

        return ApiMessage::userCreated();
    }


    /**
     * - As an admin I can delete users.
     *
     *  Removes the User resource
     */
    public function deleteUserAction(int $userId): View
    {
        try {
            $user = $this->userService->getUser($userId);
        } catch (EntityNotFoundException $e) {
            ApiMessage::userNotFound();
        }

        if (isset($user)) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        return ApiMessage::userDeleted();
    }











    /**
     * @Rest\Get("api/account", name="api_account")
     */
    public function userInfo()
    {
        $user = $this->getUser();

        return $this->json($user);
    }


    /**
     * @Rest\Get("api/user", name="api_get_all_users")
     */
    public function getAllUsersAction()
    {
        return $this->json(
            $this->userService->getAllUsers(),
            200,
            [],
            [
                'groups' => ['main'],
            ]
        );

    }
}
