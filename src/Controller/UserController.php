<?php

namespace App\Controller;

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserGroup;
use App\Form\UsersType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


//- As an admin I can add users. A user has a name.
//- As an admin I can assign users to a group they aren’t already part of.
//

class UserController extends FOSRestController
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



    //TODO: NOT USED
    public function redirectAction() {
        $view = $this->redirectView($this->generateUrl('some_route'), 301);
        // or
        $view = $this->routeRedirectView('some_route', array(), 301);

        return $this->handleView($view);
    }



    /**
     * @Rest\Get("api/account", name="api_account")
     */
    public function userInfo() {
        $user = $this->getUser();
        return $this->json($user);
    }


    /**
     * @Rest\Get("api/user", name="api_get_all_users")
     */
    public function getAllUsersAction() {

        //PROBLEM: json_encode on object. encodes only public properties PHP. NO PUBLIC PROPERTIES
        //        $user = $this->userService->getUser(73);
        //        $user = $this->serializer->serialize($user, 'json');
        //        $user = $this->getUser();
        //TODO: circular reference userRepository->findAll();
        //MANY TO MANY USER->userGroups?
        //SOLVED by limiting results with * @Groups("main") in User Entity.
        //WHAT IF we need to get userGroups?
        //SOLVED with same approach

        //        $users = $this->json($this->userService->getAllUsers());
        //        dd($users);

        return $this->json($this->userService->getAllUsers(), 200, [], [
                'groups' => ['main']]
        );

    }


    //TODO: Pabandyti forma grazinti per api
    //TODO: 1. Reikia sukurti UsersType formos klasę
    public function postAction(
        Request $request
    ) {
        $form = $this->createForm(UsersType::class, new User());

        $form->submit($request->request->all());

        if (false === $form->isValid()) {

            return $this->handleView(
                $this->view($form)
            );
        }

        $this->entityManager->persist($form->getData());
        $this->entityManager->flush();

        return $this->handleView(
            $this->view(
                [
                    'status' => 'ok',
                ],
                Response::HTTP_CREATED
            )
        );
    }

//
//- As an admin I can assign users to a group they aren’t already part of.
//- As an admin I can create groups.
////- As an admin I can delete groups when they no longer have members.
//    /**
//     * - As an admin I can delete users.
//     * @Rest\Delete("api/user/{username}", name="api_delete_users")
//     */
//    public function deleteAction($username) {
//
//        $this->userService->deleteUser($username);
//
//
//
//        return new Response('Successfully removed', 204);
//    }

    /**
     * - As an admin I can delete users.
     *
     * Removes the User resource
     * @Rest\Delete("api/users/{userId}")
     */
    public function deleteUserAction(int $userId): View
    {
        $this->userService->deleteUser($userId);
        // In case our DELETE was a success we need to return a 204 HTTP NO CONTENT response. The object is deleted.
        return View::create([], Response::HTTP_NO_CONTENT);
    }
//
//
//    /**
//     * Creates an Article resource
//     * @Rest\Post("/articles")
//     * @param Request $request
//     * @return View
//     */
//    public function postArticle(Request $request): View
//    {
//        $article = new Article();
//        $article->setTitle($request->get('title'));
//        $article->setContent($request->get('content'));
//        $this->articleRepository->save($article);
//        // In case our POST was a success we need to return a 201 HTTP CREATED response
//        return View::create($article, Response::HTTP_CREATED);
//    }


}
