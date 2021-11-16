<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplayAllController extends AbstractController
{
    /**
     * @Route("/display/all-album", name="displayAllAlbum")
     */
    public function index(AlbumRepository $AlbumRepository, PhotoRepository $PhotoRepository, EntityManagerInterface $em): Response
    {
        $albumList = $AlbumRepository->findBy([], ['titre'=>'ASC']);
        $photoList = $PhotoRepository->findBy([], ['id'=>'ASC']);


        return $this->render('display/all-albums.html.twig', [
            'albumList' => $albumList,
            'photoList'=>$photoList,
        ]);
    }
}
