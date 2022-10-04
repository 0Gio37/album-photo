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
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Scalar\String_;
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
    public function addPhoto(Request $request, ImageRepository $ImageRepository, UserRepository $UserRepository,  EntityManagerInterface $em): Response
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

            $file = $imageForm->get('fileName')->getData();

            if($this->getParameter('upload_images_destination') == 'local'){
                $newFilename = md5(uniqid()) . '.' . $file->guessExtension();
                //send image in local storage
                $file->move($this->getParameter('photo_directory'), $newFilename);

            } else{
                $newFilename = md5(uniqid());
                //send image in cloudinary prod
                $cloudinary =  new Cloudinary([
                    'cloud' => [
                        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                        'api_key'  => $_ENV['CLOUDINARY_API_KEY'],
                        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
                        'url' => [
                            'secure' => true]]]);
                $cloudinary->uploadApi()->upload($file->getPathName(),
                    ['public_id' => $newFilename]);
            }

            //update image in bdd
            $image->setFileName($newFilename);
            $showCurrentPhotoTwig = true;
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
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
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
    public function addComment(Request $request, UserRepository $UserRepository, CommentaireRepository $commentaireRepository, PhotoRepository $PhotoRepository, EntityManagerInterface $em)
    {
        $currentUserId = $this->security->getUser()->getId();

        $commentaire = new Commentaire();
        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
        $formCommentaire->handleRequest($request);

        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);
            $photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
            $photoId = $photoList[0];

            $commentaire->setAuteurId($currentUserObject);
            $commentaire->setPhotoId($photoId);

            $dataCommentaire = $formCommentaire->getData();
            $em->persist($dataCommentaire);
            $em->flush();
        }

        $commentPhoto = [];

        $photoList= $PhotoRepository-> findBy([],['id'=>'DESC'],1);
        $photoId = $photoList[0];

        $listComment = $commentaireRepository->findBy(['photo_id'=>$photoId]);
        foreach ($listComment as $idEquivalent){
            array_push($commentPhoto, $idEquivalent);
        }

        return $this->render(
            'home/addComment.html.twig', [
                'commentForm'=>$formCommentaire->createView(),
                'commentPhoto'=>$commentPhoto,
            ]
        );
    }

    /**
     * @Route("/add-new-comment/{titleAlbum}/{photoId}/{status}/{count}", name="add_new_comment")
     */
    public function addNewComment(Request $request, CommentaireRepository $commentaireRepository, UserRepository $UserRepository, PhotoRepository $PhotoRepository, EntityManagerInterface $em, $photoId, $titleAlbum, $status, $count) :Response
    {
        $currentUserId = $this->security->getUser()->getId();

        $commentaire = new Commentaire();
        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
        $formCommentaire->handleRequest($request);

        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);

            $commentaire->setAuteurId($currentUserObject);
            $currentPhoto= $PhotoRepository-> findOneBy(['id'=>$photoId]);
            $commentaire->setPhotoId($currentPhoto);

            $dataComment = $formCommentaire->getData();
            $em->persist($dataComment);
            $em->flush();
        }

        $commentPhoto = [];

        $listComment = $commentaireRepository->findBy(['photo_id'=>$photoId]);
        foreach ($listComment as $idEquivalent){
            array_push($commentPhoto, $idEquivalent);
        }

        return $this->render(
            'home/addNewcomment.html.twig', [
                'formComment' => $formCommentaire->createView(),
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
