<?php

namespace App\Api;

use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;


class ApiMessage
{
    private const USER_NOT_FOUND = [
        'message' => 'User not found',
    ];
    private const USER_CREATED = [
        'message' => 'User created',
    ];
    private const USER_DELETED = [
        'message' => 'User deleted',
    ];


    private const GROUP_NOT_FOUND = [
        'message' => 'Group not found',
    ];
    private const GROUP_CREATED = [
        'message' => 'Group created',
    ];
    private const GROUP_DELETED = [
        'message' => 'Group deleted',
    ];

    private const USER_ADDED_TO_GROUP = [
        'message' => 'User added to group',
    ];

    private const GROUP_HAS_USERS = [
        'message' => 'Group has users, cannot delete',
    ];




    public static function userNotFound()
    {
        return new View(
            self::USER_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    }

    public static function userCreated()
    {
        return new View(
            self::USER_CREATED,
            Response::HTTP_CREATED
        );
    }

    public static function userDeleted()
    {
        return new View(
            self::USER_DELETED,
            Response::HTTP_NOT_FOUND
        );
    }

    public static function groupNotFound()
    {
        return new View(
            self::GROUP_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    }

    public static function groupCreated()
    {
        return new View(
            self::GROUP_CREATED,
            Response::HTTP_CREATED
        );
    }

    public static function groupDeleted()
    {
        return new View(
            self::GROUP_DELETED,
            Response::HTTP_NOT_FOUND
        );
    }

    public static function userAddedToGroup()
    {
        return new View(
            self::USER_ADDED_TO_GROUP,
            Response::HTTP_OK
        );
    }

    public static function groupHasUsers()
    {
        return new View(
            self::GROUP_HAS_USERS,
            Response::HTTP_ACCEPTED
        );
    }
}
