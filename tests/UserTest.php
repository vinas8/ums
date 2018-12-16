<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class UserTest extends ApiTestCase
{

    // As an admin I can delete users.
    public function testDeleteUser()
    {
//        $token = $this->
        $user = $this->createUser('vardas');


        $response = $this->client->delete('/api/programmers/UnitTester');
        $this->assertEquals(204, $response->getStatusCode());
    }


//    public function testDELETEProgrammer()
//    {
//        $this->createProgrammer(array(
//            'nickname' => 'UnitTester',
//            'avatarNumber' => 3,
//        ));
//
//        $response = $this->client->delete('/api/programmers/UnitTester');
//        $this->assertEquals(204, $response->getStatusCode());
//    }
}
