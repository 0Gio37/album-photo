<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

class UserEntityTest extends KernelTestCase
{
    public function testTrue(){
        $this->assertTrue(true);
    }
    /**
    public function getEntityUser(){
        $newUser =  new User();
        $newUser->setNom("TestUnitNom");
        $newUser->setPrenom("TestUnitPrenom");
        $newUser->setUsername("admin");
        $newUser->setPassword("cervc");
        $newUser->setMail("testmail@testmail");
        return $newUser;
    }

    public function testValidNewUser(){
        $newUser = $this->getEntityUser();
        self::bootKernel();
        $violations = self::$container->get('validator')->validate($newUser);
        $message= [];
     * **/
        /** @var ConstraintViolation $violation */
    /**
        foreach ($violations as $violation){
            $message[] = $violation->getPropertyPath()." ===> ".$violation->getMessage();
        }
        $this->assertCount(0, $violations, implode(',', $message));
    }
    **/

}