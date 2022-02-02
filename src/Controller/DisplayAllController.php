<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\LienTagPhotoRepository;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use App\Repository\ThemeRepository;
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
    public function displayAllYear(PhotoRepository $photoRepository ): Response
    {

        $yearListSorted =[];
        $yearListBrut = $photoRepository->findBy([], ['annee'=>'DESC']);
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
/*
        $pattern ='/[A-Z0-9]+/';
        $replacement = '';
        foreach ($listAlbumsByTheme as $item){
            $test=preg_replace($pattern, $replacement, $item->getTitre());
            $noSpace = trim($test);
            $noSpace = ucfirst($noSpace);
            $item->setTitre($noSpace);}
        */

        return $this->render('display/single-theme.html.twig', [
            'listAlbumsByTheme'=>$listAlbumsByTheme,
            'titreTheme'=>$titreTheme,
            'photoList'=>$photoList,
        ]);
    }


    /**
     * @Route("/display/all-album-non-alpha", name="displayAllAlbumNonAlpha")
     */
    public function displayAllAlbumAntiChrono(AlbumRepository $AlbumRepository, PhotoRepository $PhotoRepository): Response
    {
        $this->toggleBtn =  true;

        $albumList = $AlbumRepository->findBy([], ['titre'=>'DESC']);
        $photoList = $PhotoRepository->findBy([], ['id'=>'ASC']);

        /*$pattern ='/[A-Z0-9]+/';
        $replacement = '';
        foreach ($albumList as $item){
            $test=preg_replace($pattern, $replacement, $item->getTitre());
            $noSpace = trim($test);
            $noSpace = ucfirst($noSpace);
            $item->setTitre($noSpace);}
*/
        return $this->render('display/all-albums.html.twig', [
            'albumList' => $albumList,
            'photoList'=>$photoList,
            'toggleBtn'=> $this->toggleBtn,
        ]);
    }

    /**
     * @Route("/display/all-album-alpha", name="displayAllAlbumAlpha")
     */
    public function displayAllAlbumChrono(AlbumRepository $AlbumRepository, PhotoRepository $PhotoRepository): Response
    {
        $this->toggleBtn =  false;

        $albumList = $AlbumRepository->findBy([], ['titre'=>'ASC']);
        $photoList = $PhotoRepository->findBy([], ['id'=>'ASC']);
/*
        $pattern ='/[A-Z0-9]+/';
        $replacement = '';
        foreach ($albumList as $item){
            $test=preg_replace($pattern, $replacement, $item->getTitre());
            $noSpace = trim($test);
            $noSpace = ucfirst($noSpace);
            $item->setTitre($noSpace);}
*/

        return $this->render('display/all-albums.html.twig', [
            'albumList' => $albumList,
            'photoList'=>$photoList,
            'toggleBtn'=> $this->toggleBtn,
        ]);
    }

    /**
     * @Route("/display/single-year/{photoAnnee}", name="displaySingleYear")
     */
    public function displaySingleYear(PhotoRepository $photoRepository, AlbumRepository $AlbumRepository, $photoAnnee): Response
    {
        $photoListByYear = $photoRepository->findBy(['annee'=>$photoAnnee], ['id'=>'ASC']);

        return $this->render('display/single-year.html.twig', [
            'photoListByYear' => $photoListByYear,
            'photoAnnee'=>$photoAnnee,
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
        $photoAllList = $PhotoRepository->findBy([], ['annee'=>'DESC']);

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
        $photoAllList = $PhotoRepository->findBy([], ['annee'=>'ASC']);

        return $this->render('display/all-photos.html.twig', [
            'photoAllList' => $photoAllList,
            'toggleBtn'=> $this->toggleBtn,
        ]);
    }

    /**
     * @Route("/display/detail-photo/{titleAlbum}/{idPhoto}/{status}/{count}", name="detailsPhoto")
     */
    public function detailsPhoto(AlbumRepository $AlbumRepository, PhotoRepository $PhotoRepository, LienTagPhotoRepository $LienTagPhotoRepository, TagRepository $TagRepository, $idPhoto, $titleAlbum, $status,$count ): Response
    {
        $selectedPhotoArray = $PhotoRepository->findBy(['id'=>$idPhoto]);
        $currentAlbum = $AlbumRepository->findBy(['titre'=>$titleAlbum]);
        //dd($currentAlbum);
        $currentAlbumId = $currentAlbum[0]->getId();
        $lienTagPhotoList = $LienTagPhotoRepository->findAll();
        $tagList = $TagRepository->findAll();

        if($status == 1){
            $selectedPhoto  = $selectedPhotoArray[0];
        }else{
            $currentArrayAlbumPhoto = $PhotoRepository->findBy(['album'=>$currentAlbumId]);
            if($count >= count($currentArrayAlbumPhoto)){
                $count = 0;
                $selectedPhoto = $currentArrayAlbumPhoto[$count];
            }elseif ($count < 0){
                $count = count($currentArrayAlbumPhoto)-1;
                $selectedPhoto = $currentArrayAlbumPhoto[$count];
            }
            else{
                $selectedPhoto = $currentArrayAlbumPhoto[$count];
            }
        }

        return $this->render(
            'display/detailsPhoto.html.twig',[
            'selectedPhoto'=>$selectedPhoto,
            'idPhoto'=>$idPhoto,
            'titleAlbum'=>$titleAlbum,
            'lienTagPhotoList'=>$lienTagPhotoList,
            'tagList'=>$tagList,
            'count'=>$count,
            'currentAlbumId'=>$currentAlbumId,
        ]);
    }








}
