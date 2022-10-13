<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\AlbumRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CommentRepository;
use App\Repository\CommentsRepository;
use App\Repository\LienCommentPhotoRepository;
use App\Repository\LienTagPhotoRepository;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use App\Repository\ThemeRepository;
use App\Service\Services;
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
    public function displayAllYear(PhotoRepository $photoRepository): Response
    {
        $yearListSorted =[];
        $yearListBrut = $photoRepository->findBy([], ['annee'=>'DESC']);
        foreach ($yearListBrut as $photo){
            array_push($yearListSorted, $photo->getAnnee());
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
        $listAlbumsByTheme = $AlbumRepository->findBy(['theme'=>$idTheme], ['titre'=>'ASC']);

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

        return $this->render('display/all-albums.html.twig', [
            'albumList' => $albumList,
            'photoList'=>$photoList,
            'toggleBtn'=> $this->toggleBtn,
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']

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

        return $this->render('display/all-albums.html.twig', [
            'albumList' => $albumList,
            'photoList'=>$photoList,
            'toggleBtn'=> $this->toggleBtn,
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
        ]);
    }

    /**
     * @Route("/display/single-year/{photoAnnee}", name="displaySingleYear")
     */
    public function displaySingleYear(PhotoRepository $photoRepository, $photoAnnee): Response
    {
        $photoListByYear = $photoRepository->findBy(['annee'=>$photoAnnee], ['id'=>'ASC']);

        return $this->render('display/single-year.html.twig', [
            'photoListByYear' => $photoListByYear,
            'photoAnnee'=>$photoAnnee,
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
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
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
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
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
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
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
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
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
        ]);
    }

    /**
     * @Route("/display/detail-photo/{titleAlbum}/{idPhoto}/{status}/{count}", name="detailsPhoto")
     */
    public function detailsPhoto(
        AlbumRepository $AlbumRepository, CommentaireRepository $commentaireRepository , PhotoRepository $photoRepository,
        LienTagPhotoRepository $LienTagPhotoRepository,
        TagRepository $TagRepository, $idPhoto, $titleAlbum, $status, $count, Services $photoSlider ): Response
    {
        $photoDisplayed  = $photoRepository->findBy(['id'=>$idPhoto])[0];
        $albumParentPhotoDisplayed = $AlbumRepository->findBy(['titre'=>$titleAlbum]);
        $currentAlbumId = $albumParentPhotoDisplayed[0]->getId();
        $lienTagPhotoList = $LienTagPhotoRepository->findAll();
        $tagList = $TagRepository->findAll();
        $commentaireList = $commentaireRepository->findBy(['photo_id'=>$idPhoto], ['created_at'=>'DESC']);
        $count = (int)$count;

        if($status == 0){
            $tempArrayToDisplayPhotosFromAlbumParent = $photoSlider->Slider($photoDisplayed, $photoRepository, $currentAlbumId);

            switch ($count) {
                case $count <= 0:
                    $count = count($tempArrayToDisplayPhotosFromAlbumParent)-1;
                    break;
                case $count >= count($tempArrayToDisplayPhotosFromAlbumParent) :
                    $count = 0;
                    break;
            }
            $photoDisplayed = $tempArrayToDisplayPhotosFromAlbumParent[$count];
            $commentaireList = $commentaireRepository->findBy(['photo_id'=>$photoDisplayed->getId()]);
        }

        return $this->render(
            'display/detailsPhoto.html.twig',[
            'selectedPhoto'=>$photoDisplayed,
            'idPhoto'=>$idPhoto,
            'titleAlbum'=>$titleAlbum,
            'lienTagPhotoList'=>$lienTagPhotoList,
            'tagList'=>$tagList,
            'commentaireList'=>$commentaireList,
            'count'=>$count,
            'currentAlbumId'=>$currentAlbumId,
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
        ]);
    }
}
