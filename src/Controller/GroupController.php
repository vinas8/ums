<?php
/**
 * Created by PhpStorm.
 * User: zilvinasnavickas
 * Date: 2018-12-14
 * Time: 08:58
 */

namespace App\Controller;


use App\Entity\UserGroup;
use App\Service\GroupService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


//- As an admin I can remove users from a group.
//- As an admin I can create groups.
//- As an admin I can delete groups when they no longer have members.

/**
 * Class GroupController
 * @IsGranted("ROLE_ADMIN")
 */
class GroupController extends FOSRestController
{

    private $groupService;
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        EntityManagerInterface $entityManager,
        GroupService $groupService,
        UserService $userService
    ) {
        $this->entityManager = $entityManager;
        $this->groupService = $groupService;
        $this->userService = $userService;
    }


    //As an admin I can assign users to a group they aren’t already part of.
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=UserGroup::class, groups={"full"}))
     *     )
     * )
     * @Rest\Post("api/group/{group}/addUser/{user}", name="api_add_user_to_group")
     */
    public function addUser(Request $request, $user, $group) {
        if (!$request->isMethod('POST')) {
            return new Response('NOT POST TODO');
        }

        $user = $this->userService->getUser($user);

        $group = $this->groupService->getGroup($group);

        if (!$group) {
            throw new \InvalidArgumentException("There is no such group");
        }

        $group->addUser($user);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $group;
    }


    //As an admin I can remove users from a group.
    /**
     * @Rest\Post("api/group/{group}/removeUser/{user}", name="api_remove_user_from_group") TODO: Ar url struktūra REST?
     */
    public function removeUserAction(Request $request, $user, $group) {
        //todo: validation example Kiec.lt
        //        if (!$studentInfo) {
        //            throw new NotFoundHttpException("Mokinys nerastas.");
        //        }
        //        if (!$studentInfo->getClassInfo()->getUser()->contains($this->getCurrentUser())) {
        //            throw new AccessDeniedException("Mokinys nepasiekiamas.");
        //        }
        if (!$request->isMethod('POST')) {
            return new Response('NOT POST TODO');
        }
//        $this->handlerequest?
//        $this->validate?

        $user = $this->userService->getUser($user);
        $group = $this->groupService->getGroup($group);


        if (!$group) {
            throw new \InvalidArgumentException("There is no such group");
        }

        $group->removeUser($user);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return new Response('User removed from group');
    }

    /**
     * @Rest\Post("api/group", name="api_create_group")
     */
    public function createGroup($name = 'unnamed') {
        $group = new UserGroup();

        dd($name);

        $group->setName($name);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return new Response('Group created');
    }

    /**
     * @Rest\Get("api/group", name="api_show_all_groups")
     */
    public function getAllGroups() {
        return $this->json($this->groupService->getAllGroups(), 200, [], [
                'groups' => ['main_group']]
        );

//        $this->entityManager->persist($group);
//        $this->entityManager->flush();
    }

    /**
     * @Rest\Get("api/group/getUsers", name="api_get_users_from_group")
     */
    public function getUsersFromGroup() {
        $groups = $this->groupService->getAllGroups();



        dd($groups);

        //        $this->entityManager->persist($group);
        //        $this->entityManager->flush();

        return new Response('Group created');
    }
}