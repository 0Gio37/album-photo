<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\PhotoRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplayAllController extends AbstractController
{
    public $toggleBtn = false;

    /**
     * @Route("/display/all-theme", name="displayAllTheme")
     */
    public function displayAllTheme(ThemeRepository $ThemeRepository): Response
    {
        $themeList = $ThemeRepository->findBy([], ['titre'=>'ASC']);
        //dd($themeList);

        return $this->render('display/all-themes.html.twig', [
            'themeList' => $themeList,
        ]);
    }

    /**
     * @Route("/display/single-theme/{idTheme}/{titreTheme}", name="displayOneTheme")
     */
    public function displayOneTheme(AlbumRepository $AlbumRepository, PhotoRepository $PhotoRepository, $idTheme, $titreTheme): Response
    {
        //dd($idTheme);
        $photoList = $PhotoRepository->findBy([], ['id'=>'ASC']);

        $listAlbumsByTheme = $AlbumRepository->findBy(['theme'=>$idTheme], ['id'=>'ASC']);
        //dd($listAlbumsByTheme);

        return $this->render('display/single-theme.html.twig', [
            'listAlbumsByTheme'=>$listAlbumsByTheme,
            'titreTheme'=>$titreTheme,
            'photoList'=>$photoList,
        ]);
    }



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
     * @Route("/display/all-photos-inversChrono", name="displayAllPhotoAntiChrono")
     */
    public function displayAllPhotoAntiChrono(PhotoRepository $PhotoRepository): Response
    {
        $this->toggleBtn =  true;
        $photoAllList = $PhotoRepository->findBy([], ['id'=>'DESC']);
        //dd($photoAllList);

        return $this->render('display/all-photos.html.twig', [
            'photoAllList' => $photoAllList,
            'toggleBtn'=> $this->toggleBtn,
        ]);
    }

    /**
     * @Route("/display/all-photos-Chrono", name="displayAllPhotoChrono")
     */
    public function displayAllPhotoChrono(PhotoRepository $PhotoRepository): Response
    {
        $this->toggleBtn =  false;
        $photoAllList = $PhotoRepository->findBy([], ['id'=>'ASC']);

        return $this->render('display/all-photos.html.twig', [
            'photoAllList' => $photoAllList,
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

}
