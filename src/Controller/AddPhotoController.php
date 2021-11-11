<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddPhotoController extends AbstractController
{
    /**
     * @Route("/add-photo", name="add_photo")
     */
    public function index(): Response
    {
        return $this->render('home/addPhoto.html.twig', [
            'controller_name' => 'AddPhotoController',
        ]);
    }
}
