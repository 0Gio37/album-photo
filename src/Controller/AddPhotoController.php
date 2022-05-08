<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Comment;
use App\Entity\Commentaire;
use App\Entity\Image;
use App\Entity\LienCommentPhoto;
use App\Entity\LienTagPhoto;
use App\Entity\Photo;
use App\Entity\Tag;
use App\Form\AlbumType;
use App\Form\CommentaireType;
use App\Form\CommentType;
use App\Form\ImageType;
use App\Form\LienCommentPhotoType;
use App\Form\LienTagPhotoType;
use App\Form\PhotoType;
use App\Form\TagType;
use App\Repository\CommentaireRepository;
use App\Repository\CommentRepository;
use App\Repository\CommentsRepository;
use App\Repository\ImageRepository;
use App\Repository\LienCommentPhotoRepository;
use App\Repository\LienTagPhotoRepository;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            $this->addFlash('addPhoto', 'photo ajoutée !');
        }

        if ($formAlbum->isSubmitted() && $formAlbum->isValid()) {
            $data = $formAlbum->getData();
            $album->setTitre($album->getTitre());
            $em->persist($data);
            $em->flush();
            $this->addFlash('addAlbum', 'album ajouté !');

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

    /**
     * @Route("/add-new-tag/{titleAlbum}/{photoId}/{status}/{count}", name="add_new_tag")
     */
    public function addNewTag(Request $request, TagRepository $TagRepository, LienTagPhotoRepository $LienTagPhotoRepository, PhotoRepository $PhotoRepository, EntityManagerInterface $em, $photoId,$titleAlbum,$status,$count)
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

            $currentPhoto= $PhotoRepository-> findOneBy(['id'=>$photoId]);
            $linkTagPhoto->setPhoto($currentPhoto);

            $dataLinkTagPhoto= $formLinkTagPhoto->getData();
            $em->persist($dataLinkTagPhoto);
            $em->flush();
        }

        $taggedPersonns = [];
        $listTagIdEquivalent = $LienTagPhotoRepository->findBy(['photo'=>$photoId]);
        foreach ($listTagIdEquivalent as $idEquivalent){
            $personn = $TagRepository->findby(['id'=>$idEquivalent->getTag()->getId()]);
            array_push($taggedPersonns, $personn);
        }

        return $this->render(
            'home/addNewtag.html.twig', [
                'tagForm'=>$formTag->createView(),
                'photoId'=>$photoId,
                'taggedPersonns' => $taggedPersonns,
                'titleAlbum'=>$titleAlbum,
                'count'=>$count,
                'status'=>$status,
            ]
        );
    }

    /**
     * @Route("/add-comment", name="add_comment")
     */
    public function addComment(Request $request, UserRepository $UserRepository, LienCommentPhotoRepository $lienCommentPhotoRepository, CommentaireRepository $commentaireRepository, CommentRepository $commentsRepository, PhotoRepository $PhotoRepository, EntityManagerInterface $em)
    {
        $currentUserId = $this->security->getUser()->getId();

//        $comment = new Comment();
//        $formComment = $this->createForm(CommentType::class,$comment);
//        $formComment->handleRequest($request);

        //ajout commentaire form
        $commentaire = new Commentaire();
        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
        $formCommentaire->handleRequest($request);


        $linkCommentPhoto = new LienCommentPhoto();
        $formLinkCommentPhoto = $this->createForm(LienCommentPhotoType::class, $linkCommentPhoto);
        $formLinkCommentPhoto->handleRequest($request);

//        if ($formComment->isSubmitted() && $formComment->isValid()) {
            if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {

            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);
                $photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
                $photoId = $photoList[0];


//            $comment->setAuteur($currentUserObject);
                $commentaire->setAuteurId($currentUserObject);
                $commentaire->setPhotoId($photoId);

//            $dataComment = $formComment->getData();
                $dataCommentaire = $formCommentaire->getData();

//            $em->persist($dataComment);
                $em->persist($dataCommentaire);

            $em->flush();

//            $CommentList = $commentsRepository-> findBy([],['id'=>'DESC'],1);
                $CommentaireList = $commentaireRepository-> findBy([],['id'=>'DESC'],1);

//                $commentId = $CommentList[0];
                //$commentId = $CommentaireList[0];

            //$linkCommentPhoto->setComment($commentId);

            //$photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
           // $photoId = $photoList[0];
           // $linkCommentPhoto->setPhoto($photoId);

            //$dataLinkCommentPhoto= $formLinkCommentPhoto->getData();
           // $em->persist($dataLinkCommentPhoto);
           // $em->flush();
        }

        $commentPhoto = [];

        $photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
        $photoId = $photoList[0];

        //$listCommentIdEquivalent = $lienCommentPhotoRepository->findBy(['photo'=>$photoId]);
        $listComment = $commentaireRepository->findBy(['photo_id'=>$photoId]);
//        foreach ($listCommentIdEquivalent as $idEquivalent){
            foreach ($listComment as $idEquivalent){
//            $comment = $commentsRepository->findby(['id'=>$idEquivalent->getComment()->getId()]);
//            array_push($commentPhoto, $comment);
                array_push($commentPhoto, $idEquivalent);
        }

        return $this->render(
            'home/addComment.html.twig', [
//                'commentForm'=>$formComment->createView(),
                'commentForm'=>$formCommentaire->createView(),
                'commentPhoto'=>$commentPhoto,
            ]
        );
    }

    /**
     * @Route("/add-new-comment/{titleAlbum}/{photoId}/{status}/{count}", name="add_new_comment")
     */
    public function addNewComment(Request $request, UserRepository $UserRepository, LienCommentPhotoRepository $lienCommentPhotoRepository, CommentRepository $commentsRepository, PhotoRepository $PhotoRepository, EntityManagerInterface $em, $photoId, $titleAlbum, $status, $count) :Response
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $currentUserId = $this->security->getUser()->getId();

        $linkCommentPhoto = new LienCommentPhoto();
        $formLinkCommentPhoto = $this->createForm(LienCommentPhotoType::class, $linkCommentPhoto);
        $formLinkCommentPhoto->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {

            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);
            $comment->setAuteur($currentUserObject);
            $dataComment = $formComment->getData();
            $em->persist($dataComment);
            $em->flush();

            $commentList = $commentsRepository->findBy([], ['id' => 'DESC'], 1);
            $commentId = $commentList[0];
            $linkCommentPhoto->setComment($commentId);

            $currentPhoto = $PhotoRepository->findOneBy(['id' => $photoId]);
            $linkCommentPhoto->setPhoto($currentPhoto);

            $dataLinkCommentPhoto = $formLinkCommentPhoto->getData();
            $em->persist($dataLinkCommentPhoto);
            $em->flush();

            //return $this->redirect("/display/detail-photo/'+$titleAlbum+'/'+$photoId+'/'+$status+'/'+$count");
        }

        $commentPhoto = [];
        $listCommentIdEquivalent = $lienCommentPhotoRepository->findBy(['photo'=>$photoId]);
        foreach ($listCommentIdEquivalent as $idEquivalent){
            $comment = $commentsRepository->findby(['id'=>$idEquivalent->getComment()->getId()]);
            array_push($commentPhoto, $comment);
        }

        return $this->render(
            'home/addNewcomment.html.twig', [
                'formComment' => $formComment->createView(),
                'photoId' => $photoId,
                'titleAlbum' => $titleAlbum,
                'count' => $count,
                'status' => $status,
                'commentPhoto'=>$commentPhoto,
            ]

        );
    }

    /**
     * @Route("/add-place/{titleAlbum}/{photoId}/{status}/{count}", name="add_place")
     */
    public function addPlace(Request $request, PhotoRepository $photoRepository, EntityManagerInterface $em, $photoId,$titleAlbum,$status,$count)
    {
        $defaultData = ['message' => 'null'];
        $currentPlace='';

        $setPlaceForm = $this->createFormBuilder($defaultData)
            ->add('lieu', TextType::class, [
                'required'=>false,
                'label'=>false,
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-xl text-gray-100 flex justify-start m-auto px-6 w-1/3 py-2'],
            ])
            ->getForm();
        $setPlaceForm->handleRequest($request);

        if ($setPlaceForm->isSubmitted() && $setPlaceForm->isValid()) {
            $data = $setPlaceForm->getData();
            $currentPlace = $data['lieu'];
            $photoObject = $photoRepository->findOneBy(['id' => $photoId]);
            $photoObject->setLieu($currentPlace);
            $em->persist($photoObject);
            $em->flush();
        }

        return $this->render(
            'home/setPlace.html.twig', [
                'setPlaceForm' => $setPlaceForm->createView(),
                'photoId' => $photoId,
                'titleAlbum' => $titleAlbum,
                'count' => $count,
                'status' => $status,
                'currentPlace'=>$currentPlace,
            ]
        );
    }



}
