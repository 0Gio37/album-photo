<?php

namespace App\Controller;
use App\Repository\AlbumRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Repository\LienTagPhotoRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageItemsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/manage/supp-photo-fromhome/{idPhoto}", name="deletePhotoFromHome")
     */
    public function deletePhotoFromHome(PhotoRepository $PhotoRepository, LienTagPhotoRepository $LienTagPhotoRepository, $idPhoto ): Response
    {
        $suppTagPhoto1 = $LienTagPhotoRepository->findBy(['photo'=>$idPhoto]);
        foreach ($suppTagPhoto1 as $supp)
        {
            $item = $this->getDoctrine()->getManager();
            $item->remove($supp);
            $item->flush();
        }

        $suppPhoto = $PhotoRepository->findOneBy(['id'=>$idPhoto]);
        $item = $this->getDoctrine()->getManager();
        $item->remove($suppPhoto);
        $item->flush();

        return $this->redirectToRoute('home');
    }
    /**
     * @Route("/manage/supp-photo-fromall/{idPhoto}", name="deletePhotoFromAllPhotos")
     */
    public function deletePhotoFromAllPhotos(PhotoRepository $PhotoRepository, LienTagPhotoRepository $LienTagPhotoRepository, $idPhoto ): Response
    {
        $suppTagPhoto2 = $LienTagPhotoRepository->findBy(['photo'=>$idPhoto]);
        foreach ($suppTagPhoto2 as $supp)
        {
            $item = $this->getDoctrine()->getManager();
            $item->remove($supp);
            $item->flush();
        }

        $suppPhoto3 = $PhotoRepository->findOneBy(['id'=>$idPhoto]);
        $item = $this->getDoctrine()->getManager();
        $item->remove($suppPhoto3);
        $item->flush();

        return $this->redirectToRoute('displayAllPhotoAntiChrono');
    }

    /**
     * @Route("/manage/supp-photo-fromalbum/{titleAlbum}/{idAlbum}/{idPhoto}", name="deletePhotoFromAlbum")
     */
    public function deletePhotoFromAlbum(PhotoRepository $PhotoRepository, LienTagPhotoRepository $LienTagPhotoRepository, $idPhoto, $titleAlbum, $idAlbum ): Response
    {
        $suppTagPhoto = $LienTagPhotoRepository->findBy(['photo'=>$idPhoto]);
        foreach ($suppTagPhoto as $supp)
        {
            $item = $this->getDoctrine()->getManager();
            $item->remove($supp);
            $item->flush();
        }

        $suppPhoto = $PhotoRepository->findOneBy(['id'=>$idPhoto]);
        $item = $this->getDoctrine()->getManager();
        $item->remove($suppPhoto);
        $item->flush();


        return $this->redirectToRoute("displayOneAlbumAntiChrono", ['titleAlbum'=>$titleAlbum,'idAlbum'=>$idAlbum ]);
    }

    /**
     * @Route("/manage/supp-album/{idAlbum}", name="deleteAlbum")
     */
    public function deleteAlbum(PhotoRepository $PhotoRepository, AlbumRepository $AlbumRepository, LienTagPhotoRepository $LienTagPhotoRepository, $idAlbum): Response
    {
        $suppAlbum = $AlbumRepository->findOneBy(['id'=>$idAlbum]);

        $item = $this->getDoctrine()->getManager();
        $item->remove($suppAlbum);
        $item->flush();

        return $this->redirectToRoute('home');
    }



}
