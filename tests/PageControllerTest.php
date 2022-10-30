<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PageControllerTest extends WebTestCase
{
    public function testStatusCodeIfUnauthorized(){
        $client = static::createClient();
        $client->request("GET", "/home");
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

/**
    public function testRedirectionIfUnauthorized(){
        $client = static::createClient();
        $client->request("GET", "/home");
        $this->assertResponseRedirects("/");
    }

    public function testDisplayPageLogin(){
        $client = static::createClient();
        $client->request("GET", "/");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists(".alert.alert-danger");
    }

    public function testBadCredential(){
        $client = static::createClient();
        $crawler = $client->request("Get", "/");
        $form = $crawler->selectButton("VALIDER")->form([
            "username" => "admin",
            "password" => "fake password"
        ]);
        $client->submit($form);
        $this->assertResponseRedirects("/");
        $client->followRedirect();
        $this->assertSelectorExists(".alert.alert-danger");
    }

    public function testSuccesCredential(){
        $client = static::createClient();
        $crawler = $client->request("Get", "/");
        $form = $crawler->selectButton("VALIDER")->form([
            "username" => "test",
            "password" => "test"
        ]);
        $client->submit($form);
        $this->assertResponseRedirects("/home");
        $client->followRedirect();
    }
 *
 * **/

}