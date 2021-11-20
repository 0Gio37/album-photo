<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Image;
use App\Entity\LienTagPhoto;
use App\Entity\Photo;
use App\Entity\Tag;
use App\Form\AlbumType;
use App\Form\ImageType;
use App\Form\LienTagPhotoType;
use App\Form\PhotoType;
use App\Form\TagType;
use App\Repository\ImageRepository;
use App\Repository\LienTagPhotoRepository;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AddPhotoController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/add-photo", name="add_photo")
     */
    public function addPhoto(Request $request, ImageRepository $ImageRepository,  UserRepository $UserRepository,  EntityManagerInterface $em ): Response
    {
        $visibleTaggedPersonnBtn = false;
        $showCurrentPhotoTwig = false;
        $currentImageFileName = '';

        $image = new Image();
        $imageForm = $this->createForm(ImageType::class, $image);
        $imageForm->handleRequest($request);

        $photo = new Photo();
        $formPhoto = $this->createForm(PhotoType::class, $photo);
        $currentUserId = $this->security->getUser()->getId();
        $formPhoto->handleRequest($request);

        $album = new Album();
        $formAlbum = $this->createForm(AlbumType::class, $album);
        $formAlbum->handleRequest($request);


        if ($imageForm->isSubmitted() && $imageForm->isValid()) {

            $showCurrentPhotoTwig = true;

            $file = $imageForm->get('fileName')->getData();
            $newFilename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photo_directory'), $newFilename);
            $image->setFileName($newFilename);
            $data = $imageForm->getData();
            $em->persist($data);
            $em->flush();

            $currentImage = $ImageRepository->findBy([],['id'=>'DESC'],1);
            $currentImageFileName = $currentImage[0]->getFileName();

        }

        if ($formPhoto->isSubmitted() && $formPhoto->isValid()) {

            $visibleTaggedPersonnBtn = true;

            $currentImage = $ImageRepository->findBy([],['id'=>'DESC'],1);
            $currentImageFileName = $currentImage[0]->getFileName();

            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);
            $photo->setAuteur($currentUserObject);
            $photo->setFile($currentImageFileName);
            $data = $formPhoto->getData();
            $em->persist($data);
            $em->flush();
        }

        if ($formAlbum->isSubmitted() && $formAlbum->isValid()) {
            $data = $formAlbum->getData();
            $album->setTitre(strtoupper($album->getTheme()->getTitre()).' '.$album->getTitre().' '.$album->getAnnee() );
            $em->persist($data);
            $em->flush();
        }

        return $this->render(
            'home/addPhoto.html.twig', [
            'imageForm'=>$imageForm->createView(),
            'photoForm' => $formPhoto->createView(),
            'visibleTaggedPersonnBtn'=>$visibleTaggedPersonnBtn,
            'formAlbum'=>$formAlbum->createView(),
            'showCurrentPhotoTwig'=>$showCurrentPhotoTwig,
            'currentImageFileName'=>$currentImageFileName,
        ]);
    }

    /**
     * @Route("/add-tag", name="add_tag")
     */
    public function addTag(Request $request, TagRepository $TagRepository, LienTagPhotoRepository $LienTagPhotoRepository, PhotoRepository $PhotoRepository, EntityManagerInterface $em)
    {
        $tag = new Tag();
        $formTag = $this->createForm(TagType::class, $tag);
        $formTag->handleRequest($request);

        $linkTagPhoto = new LienTagPhoto();
        $formLinkTagPhoto = $this->createForm(LienTagPhotoType::class, $linkTagPhoto);
        $formLinkTagPhoto->handleRequest($request);

        if ($formTag->isSubmitted() && $formTag->isValid()) {
            $dataTag = $formTag->getData();

            $em->persist($dataTag);
            $em->flush();
            $tagList= $TagRepository-> findBy([],['id'=>'DESC'],1);
            $tagId = $tagList[0];
            $linkTagPhoto->setTag($tagId);

            $photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
            $photoId = $photoList[0];
            $linkTagPhoto->setPhoto($photoId);

            $dataLinkTagPhoto= $formLinkTagPhoto->getData();
            $em->persist($dataLinkTagPhoto);
            $em->flush();
        }

        $taggedPersonns = [];

        $photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
        $photoId = $photoList[0];

        $listTagIdEquivalent = $LienTagPhotoRepository->findBy(['photo'=>$photoId]);
        foreach ($listTagIdEquivalent as $idEquivalent){
            $personn = $TagRepository->findby(['id'=>$idEquivalent->getTag()->getId()]);
            array_push($taggedPersonns, $personn);
        }

        return $this->render(
            'home/addTag.html.twig', [
                'tagForm'=>$formTag->createView(),
                'taggedPersonns' => $taggedPersonns,
            ]
        );
    }
}
