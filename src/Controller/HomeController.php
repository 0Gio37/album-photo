<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(PhotoRepository $PhotoRepository): Response
    {
        $photoList = $PhotoRepository->findBy([], ['id'=>'DESC'],12);

        return $this->render('home/index.html.twig', [
            'photoList' => $photoList,
        ]);
    }
}
