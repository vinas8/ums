<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\UserGroup;

class UserFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $group = new UserGroup();
        $group->setName("USERIU_GRUPE");

        $this->createMany(
            10,
            'main_users',
            function ($i) use ($manager, $group) {
                $user = new User();
                $user->setUsername(sprintf('%d@example.com', $i));

                $apiToken1 = new ApiToken($user);
                $apiToken2 = new ApiToken($user);

                $user->addApiToken($apiToken1);
                $user->addApiToken($apiToken2);

                $manager->persist($apiToken1);
                $manager->persist($apiToken2);

                if ($i % 2 == 0) {
                    $user->setRoles(["ROLE_USER"]);
                } else {
                    $user->setRoles(["ROLE_ADMIN"]);
                }
                $group->addUser($user);

                $manager->persist($group);

                return $user;
            }
        );

        $manager->flush();
    }
}
