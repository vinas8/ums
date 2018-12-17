<?php

namespace App\Service;


use App\Entity\UserGroup;
use App\Repository\UserGroupRepository;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class GroupService
 * @package App\Application\Service
 */
final class GroupService
{

    /**
     * @var UserGroupRepository
     */
    private $groupRepository;

    /**
     * groupService constructor.
     * @param UserGroupRepository $groupRepository
     */
    public function __construct(UserGroupRepository $groupRepository){
        $this->groupRepository = $groupRepository;
    }

    /**
     * @param int $groupId
     * @return UserGroup
     * @throws EntityNotFoundException
     */
    public function getGroup(int $groupId): UserGroup
    {
        $group = $this->groupRepository->find($groupId);
        if (!$group) {
            throw new EntityNotFoundException('group with id '.$groupId.' does not exist!');
        }

        return $group;
    }

    /**
     * @return array|null
     */
    public function getAllGroups(): ?array
    {
        return $this->groupRepository->findAll();
    }

    /**
     * @param string $title
     * @return UserGroup
     */
    public function addGroup(string $title): UserGroup
    {
        $group = new UserGroup();
        $group->setFirstName($title);
        $this->groupRepository->save($group);

        return $group;
    }

    /**
     * @param int $groupId
     * @param string $title
     * @param string $content
     * @return UserGroup
     * @throws EntityNotFoundException
     */
    public function updateGroup(int $groupId, string $title, string $content): UserGroup
    {
        $group = $this->groupRepository->findById($groupId);
        if (!$group) {
            throw new EntityNotFoundException('group with id '.$groupId.' does not exist!');
        }

        $group->setFirstName($title);
        $this->groupRepository->save($group);

        return $group;
    }

    /**
     * @param int $groupId
     * @throws EntityNotFoundException
     */
    public function deleteGroup(int $groupId): void
    {
        $group = $this->groupRepository->find($groupId);
        if (!$group) {
            throw new EntityNotFoundException('group with id '.$groupId.' does not exist!');
        }
    }

    public function getGroupUsers($groupId)
    {
        return $this->groupRepository->getUsersByGroupId($groupId);
    }

}