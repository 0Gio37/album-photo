<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Comment;
use App\Entity\LienTagPhoto;
use App\Entity\Photo;
use App\Entity\Tag;
use App\Form\AlbumType;
use App\Form\CommentType;
use App\Form\LienTagPhotoType;
use App\Form\PhotoType;
use App\Form\TagType;
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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
    public function addPhoto(Request $request, SluggerInterface $slugger, UserRepository $UserRepository, TagRepository $TagRepository, PhotoRepository $PhotoRepository, LienTagPhotoRepository $LienTagPhotoRepository,  EntityManagerInterface $em ): Response
    {
        $visibleTaggedPersonnBtn = false;
        $showCurrentPhotoTwig = false;
        $currentPhotoFile ='';

        $photo = new Photo();
        $formPhoto = $this->createForm(PhotoType::class, $photo);
        $currentUserId = $this->security->getUser()->getId();
        $formPhoto->handleRequest($request);

        $album = new Album();
        $formAlbum = $this->createForm(AlbumType::class, $album);
        $formAlbum->handleRequest($request);

        if ($formPhoto->isSubmitted() && $formPhoto->isValid()) {

            $file = $formPhoto->get('file')->getData();
            $newFilename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photo_directory'), $newFilename);
            $photo->setFile($newFilename);

            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);
            $photo->setAuteur($currentUserObject);
            $data = $formPhoto->getData();
            $em->persist($data);
            $em->flush();

            $showCurrentUser = $PhotoRepository-> findBy([],['id'=>'DESC'],1);
            $currentPhotoFile = $showCurrentUser[0]->getFile();
            $showCurrentPhotoTwig = true;
        }

        if ($formAlbum->isSubmitted() && $formAlbum->isValid()) {
            $data = $formAlbum->getData();
                $em->persist($data);
                $em->flush();
        }

        return $this->render(
            'home/addPhoto.html.twig', [
            'photoForm' => $formPhoto->createView(),
            'visibleTaggedPersonnBtn'=>$visibleTaggedPersonnBtn,
            'formAlbum'=>$formAlbum->createView(),
            'showCurrentPhotoTwig'=>$showCurrentPhotoTwig,
            'currentPhotoFile'=>$currentPhotoFile,
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
