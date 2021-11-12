<?php

namespace App\Controller;

use App\Entity\LienTagPhoto;
use App\Entity\Photo;
use App\Entity\Tag;
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
        $visibleTagBtn = false;

        $photo = new Photo();
        $formPhoto = $this->createForm(PhotoType::class, $photo);
       // $currentUserId = $this->security->getUser()->getId();
        $formPhoto->handleRequest($request);

        $tag = new Tag();
        $formTag = $this->createForm(TagType::class, $tag);
        $currentUserId = $this->security->getUser()->getId();
        $formTag->handleRequest($request);

        $linkTagPhoto = new LienTagPhoto();
        $formLinkTagPhoto = $this->createForm(LienTagPhotoType::class, $linkTagPhoto);
        $formLinkTagPhoto->handleRequest($request);


        if ($formPhoto->isSubmitted() && $formPhoto->isValid()) {
            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);
            $photo->setAuteur($currentUserObject);
            $data = $formPhoto->getData();
            $em->persist($data);
            $em->flush();
            $visibleTagBtn = true;
        }

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

        return $this->render(
            'home/addPhoto.html.twig', [
            'photoForm' => $formPhoto->createView(),
            'tagForm'=>$formTag->createView(),
            'visibleTagBtn'=>$visibleTagBtn,
        ]);
    }
}
