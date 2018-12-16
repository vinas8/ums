<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\UserGroup;

class UserGroupFixtures extends BaseFixture
{

     private $passwordEncoder;
     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    protected function loadData(ObjectManager $manager) {

        $this->createMany(10, 'main_groups', function($i) use ($manager) {
            $group = new UserGroup();
            $group->setName(sprintf('GRUPE_%d', $i));


            $manager->persist($group);

            return $group;

        });



        $manager->flush();
    }
}
