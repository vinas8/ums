<?php
/**
 * Created by PhpStorm.
 * User: zilvinasnavickas
 * Date: 2018-12-12
 * Time: 00:13
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class UserController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
    UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @Rest\Get("api/users", name="api_users")
     */
    public function getUsersAction()
    {
        $data = $this->userRepository->findAll();
        dd($data);
//        dd($data);// get data, in this case list of users.
        $view = $this->view($data, 200)
            ->setTemplate("MyBundle:Users:getUsers.html.twig")
            ->setTemplateVar('users')
        ;


        $repository = $this->getDoctrine()->getRepository('AppBundle:Car');
        $carEntities = $repository->findAll();
        $dtos = [];
        foreach ($carEntities as $car) {
            $dtos[] = CarDtoAssembler::createFromEntity($car);
        }
        return $this->view($dtos, Response::HTTP_OK);

        return $this->handleView($view);
    }


    public function redirectAction()
    {
        $view = $this->redirectView($this->generateUrl('some_route'), 301);
        // or
        $view = $this->routeRedirectView('some_route', array(), 301);

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("api/account", name="api_account")
     */
    public function whoIsLoggedIn() {
        $user = $this->getUser();
        return $this->json($user);
    }

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
}