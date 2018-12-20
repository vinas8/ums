<?php
/**
 * Created by PhpStorm.
 * User: zilvinasnavickas
 * Date: 2018-12-14
 * Time: 08:58
 */

namespace App\Controller;


use App\Api\ApiMessage;
use App\Entity\UserGroup;
use App\Service\GroupService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GroupController
 * @IsGranted("ROLE_ADMIN")
 */
class GroupController extends BaseController
{

    private $groupService;
    /**
     * @var UserService
     */
    private $userService;

    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        GroupService $groupService,
        UserService $userService
    ) {
        $this->entityManager = $entityManager;
        $this->groupService = $groupService;
        $this->userService = $userService;
    }

    /**
     *
     * As an admin I can assign users to a group they aren’t already part of.

     * @Rest\Post("groups/user", name="api_add_user_to_group")
     */
    public function addUserAction(Request $request)
    {
        $userId = $request->get('userId');
        $groupId = $request->get('groupId');

        if (!$userId) {
            return ApiMessage::userNotFound();
        }
        if (!$groupId) {
            return ApiMessage::groupNotFound();
        }

        try {
            $user = $this->userService->getUser($userId);
        } catch (EntityNotFoundException $e) {
            return ApiMessage::userNotFound();
        }

        try {
            $group = $this->groupService->getGroup($groupId);
        } catch (EntityNotFoundException $e) {
            return ApiMessage::groupNotFound();
        }

        $group->addUser($user);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return
            $this->view(
                [
                    'message' => 'User added to group',
                ],
                Response::HTTP_OK
            );
    }


    /**
     * As an admin I can delete users from a group.
     * @Rest\Delete("groups/user", name="api_remove_user_from_group")
     */
    public function deleteUserAction(Request $request)
    {

        $userId = $request->get('userId');
        $groupId = $request->get('groupId');

        if (!$userId) {
            return ApiMessage::userNotFound();
        }
        if (!$groupId) {
            return ApiMessage::groupNotFound();
        }

        try {
            $user = $this->userService->getUser($userId);
        } catch (EntityNotFoundException $exception) {
            return ApiMessage::userNotFound();
        }

        try {
            $group = $this->groupService->getGroup($groupId);
        } catch (EntityNotFoundException $e) {
            ApiMessage::groupNotFound();
        }

        $group->removeUser($user);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return ApiMessage::userDeleted();
    }


    /**
     * - As an admin I can delete groups.
     *
     *  Removes the Group resource
     */
    public function deleteGroupAction(int $groupId): View
    {
        try {
            $group = $this->groupService->getGroup($groupId);
        } catch (EntityNotFoundException $e) {
            return ApiMessage::groupNotFound();
        }

        $users = $this->userService->getUsersFromGroup($groupId);

        if (isset($users) && isset($group) && empty($users)) {
            $this->entityManager->remove($group);
            $this->entityManager->flush();

            return ApiMessage::groupDeleted();
        }

        return ApiMessage::groupHasUsers();
    }




    public function postGroupAction(Request $request)
    {
        $group = new UserGroup();

        if ($groupName = $request->get('groupName')) {
            $group->setName($groupName);
        }

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return ApiMessage::groupCreated();
    }

    /**
     * @Rest\Get("groups", name="api_show_all_groups")
     */
    public function getAllGroups()
    {
        return $this->json(
            $this->groupService->getAllGroups(),
            200,
            [],
            [
                'groups' => ['main_group'],
            ]
        );
    }
}