<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\LienTagPhotoRepository;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
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

        return $this->render('display/all-themes.html.twig', [
            'themeList' => $themeList,
        ]);
    }

    /**
     * @Route("/display/all-year", name="displayAllYear")
     */
    public function displayAllYear(AlbumRepository $AlbumRepository): Response
    {
        $yearListSorted =[];
        $yearListBrut = $AlbumRepository->findBy([], ['annee'=>'DESC']);
        foreach ($yearListBrut as $year){
            array_push($yearListSorted, $year->getAnnee());
    }
        $yearListSortedUnique = array_unique($yearListSorted);

        return $this->render('display/all-years.html.twig', [
            'yearListSortedUnique' => $yearListSortedUnique,
        ]);
    }

    /**
     * @Route("/display/single-theme/{idTheme}/{titreTheme}", name="displayOneTheme")
     */
    public function displayOneTheme(AlbumRepository $AlbumRepository, PhotoRepository $PhotoRepository, $idTheme, $titreTheme): Response
    {
        $photoList = $PhotoRepository->findBy([], ['id'=>'ASC']);
        $listAlbumsByTheme = $AlbumRepository->findBy(['theme'=>$idTheme], ['id'=>'ASC']);

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
     * @Route("/display/single-year/{albumAnnee}", name="displaySingleYear")
     */
    public function displaySingleYear(AlbumRepository $AlbumRepository, PhotoRepository $PhotoRepository, $albumAnnee): Response
    {
        $albumListByYear = $AlbumRepository->findBy(['annee'=>$albumAnnee], ['titre'=>'ASC']);
        $photoList = $PhotoRepository->findBy([], ['id'=>'ASC']);

        return $this->render('display/single-year.html.twig', [
            'albumListByYear' => $albumListByYear,
            'photoList'=>$photoList,
            'albumAnnee'=>$albumAnnee,
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
    public function detailsPhoto(PhotoRepository $PhotoRepository, LienTagPhotoRepository $LienTagPhotoRepository, TagRepository $TagRepository, $idPhoto, $titleAlbum): Response
    {
        $selectedPhotoArray = $PhotoRepository->findBy(['id'=>$idPhoto]);
        $selectedPhoto  = $selectedPhotoArray[0];
        $lienTagPhotoList = $LienTagPhotoRepository->findAll();
        $tagList = $TagRepository->findAll();
        return $this->render(
            'display/detailsPhoto.html.twig',[
            'selectedPhoto'=>$selectedPhoto,
            'titleAlbum'=>$titleAlbum,
            'lienTagPhotoList'=>$lienTagPhotoList,
            'tagList'=>$tagList,
        ]);
    }

}
