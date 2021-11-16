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
    public $toggleBtn = false;

    /**
     * @Route("/display/all-album", name="displayAllAlbum")
     */
    public function displayAllAlbum(AlbumRepository $AlbumRepository, PhotoRepository $PhotoRepository): Response
    {
        $albumList = $AlbumRepository->findBy([], ['titre'=>'ASC']);
        $photoList = $PhotoRepository->findBy([], ['id'=>'ASC']);

        return $this->render('display/all-albums.html.twig', [
            'albumList' => $albumList,
            'photoList'=>$photoList,
        ]);
    }


    /**
     * @Route("/display/single-album-inversChrono/{titleAlbum}/{idAlbum}", name="displayOneAlbumAntiChrono")
     */
    public function displayOneAlbumAntiChrono(PhotoRepository $PhotoRepository, $idAlbum, $titleAlbum): Response
    {
        $this->toggleBtn =  true;
        $listPhotoAlbum = $PhotoRepository->findBy(['album'=>$idAlbum], ['id'=>'DESC']);

        return $this->render('display/single-album.html.twig', [
            'idAlbum'=>$idAlbum,
            'titleAlbum'=>$titleAlbum,
            'listPhotoAlbum'=>$listPhotoAlbum,
            'toggleBtn'=> $this->toggleBtn,
        ]);
    }

    /**
     * @Route("/display/single-album-chrono/{titleAlbum}/{idAlbum}", name="displayOneAlbumChrono")
     */
    public function displayOneAlbumChrono(PhotoRepository $PhotoRepository, $idAlbum, $titleAlbum): Response
    {
        $this->toggleBtn =  false;
        $listPhotoAlbum = $PhotoRepository->findBy(['album'=>$idAlbum], ['id'=>'ASC']);

        return $this->render('display/single-album.html.twig', [
            'idAlbum'=>$idAlbum,
            'titleAlbum'=>$titleAlbum,
            'listPhotoAlbum'=>$listPhotoAlbum,
            'toggleBtn'=> $this->toggleBtn,
        ]);
    }

    /**
     * @Route("/display/detail-photo/{titleAlbum}/{idPhoto}", name="detailsPhoto")
     */
    public function detailsPhoto(PhotoRepository $PhotoRepository, $idPhoto, $titleAlbum): Response
    {
        $selectedPhotoArray = $PhotoRepository->findBy(['id'=>$idPhoto]);
        $selectedPhoto  = $selectedPhotoArray[0];

        return $this->render(
            'display/detailsPhoto.html.twig',[
            'selectedPhoto'=>$selectedPhoto,
            'titleAlbum'=>$titleAlbum,
        ]);
    }

    /**
     * @Route("/display/all-photo", name="displayAllPhoto")
     */
    public function displayAllPhoto(PhotoRepository $PhotoRepository): Response
    {
        $photoAllList = $PhotoRepository->findAll();

        return $this->render('display/all-photos.html.twig', [
            'photoAllList' => $photoAllList,
        ]);
    }

}
