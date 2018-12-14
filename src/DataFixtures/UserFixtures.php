<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\UserGroup;

class UserFixtures extends BaseFixture
{

     private $passwordEncoder;
     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    protected function loadData(ObjectManager $manager) {
        $group = new UserGroup();

        $this->createMany(10, 'main_users', function($i) use ($manager, $group) {
            $user = new User();
            $user->setUsername(sprintf('%d@example.com', $i));
            $user->setApiToken(sprintf('miau%d', $i));
$user->setPassword($this->passwordEncoder->encodePassword($user, 'miau'));

            $apiToken1 = new ApiToken($user);
            $apiToken2 = new ApiToken($user);
            $manager->persist($apiToken1);
            $manager->persist($apiToken2);

            if ($i%2 == 0) {
                $user->setRoles(["ROLE_USER"]);
            } else {
                $user->setRoles(["ROLE_ADMIN"]);
            }
            $group->addUser($user);

            $manager->persist($group);

            return $user;

        });



        $manager->flush();
    }
}
