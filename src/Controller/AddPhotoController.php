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
    public function addPhoto(Request $request, UserRepository $UserRepository, TagRepository $TagRepository, PhotoRepository $PhotoRepository, LienTagPhotoRepository $LienTagPhotoRepository,  EntityManagerInterface $em ): Response
    {
        $visibleTaggedPersonnBtn = false;
       //$visibleTagSectionBtn = false;

        $photo = new Photo();
        $formPhoto = $this->createForm(PhotoType::class, $photo);
        $formPhoto->handleRequest($request);

        $tag = new Tag();
        $formTag = $this->createForm(TagType::class, $tag);
        $formTag->handleRequest($request);

        $linkTagPhoto = new LienTagPhoto();
        $formLinkTagPhoto = $this->createForm(LienTagPhotoType::class, $linkTagPhoto);
        $formLinkTagPhoto->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $currentUserId = $this->security->getUser()->getId();
        $formComment->handleRequest($request);

        $album = new Album();
        $formAlbum = $this->createForm(AlbumType::class, $album);
        $formAlbum->handleRequest($request);

        if ($formPhoto->isSubmitted() && $formPhoto->isValid()) {
            $visibleTagSectionBtn = true;
            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);
            $photo->setAuteur($currentUserObject);
            $data = $formPhoto->getData();
            $em->persist($data);
            $em->flush();
        }

        if ($formTag->isSubmitted() && $formTag->isValid()) {
            $visibleTaggedPersonnBtn = true;
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

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);
            $comment->setAuteur($currentUserObject);
            $photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
            $photoId = $photoList[0];
            $comment->setPhoto($photoId);
            $data = $formComment->getData();
            $em->persist($data);
            $em->flush();
        }

        if ($formAlbum->isSubmitted() && $formAlbum->isValid()) {
            $data = $formAlbum->getData();
            $em->persist($data);
            $em->flush();
        }

        return $this->render(
            'home/addPhoto.html.twig', [
            'photoForm' => $formPhoto->createView(),
            'tagForm'=>$formTag->createView(),
            'commentForm'=>$formComment->createView(),
            //'visibleTagSectionBtn'=>$visibleTagSectionBtn,
            'visibleTaggedPersonnBtn'=>$visibleTaggedPersonnBtn,
            'formAlbum'=>$formAlbum->createView(),
        ]);
    }

        /**
         * @Route("/display-tag", name="display_tag")
         */
        public function displayTag(Request $request, TagRepository $TagRepository, LienTagPhotoRepository $LienTagPhotoRepository, PhotoRepository $PhotoRepository)
        {
            $taggedPersonns = [];

            $photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
            $photoId = $photoList[0];

            $listTagIdEquivalent = $LienTagPhotoRepository->findBy(['photo'=>$photoId]);
            foreach ($listTagIdEquivalent as $idEquivalent){
                $personn = $TagRepository->findby(['id'=>$idEquivalent->getTag()->getId()]);
                array_push($taggedPersonns, $personn);
            }

            return $this->render(
                'home/section/displayTag.html.twig', [
                    'taggedPersonns' => $taggedPersonns,
                ]
            );
        }
}
