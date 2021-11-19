<?php

namespace App\Controller;

use App\Entity\LienTagPhoto;
use App\Entity\Photo;
use App\Entity\SearchByTag;
use App\Entity\Tag;
use App\Form\SearchByTagType;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/home", name="home")
     */
    public function index(PhotoRepository $PhotoRepository, Request $request): Response
    {
        $photoList = $PhotoRepository->findBy([], ['id'=>'DESC'],12);

        $byTag = new SearchByTag();
        $formSearchByTag = $this->createForm(SearchByTagType::class,$byTag);
        $formSearchByTag->handleRequest($request);
        if($formSearchByTag->isSubmitted() && $formSearchByTag->isValid()) {
            $dataSearched = $formSearchByTag->getData();
            $tagList = $this->entityManager->getRepository(Tag::class)->findAll();
            $lientagPhoto = $this->entityManager->getRepository(LienTagPhoto::class)->findAll();
            $photoList = $this->entityManager->getRepository(Photo::class)->findAll();

            return $this->render('search/by-tag.html.twig', [
                'dataSearched'=>$dataSearched,
                'lientagPhoto'=>$lientagPhoto,
                'photoList'=>$photoList,
                'tagList'=>$tagList,
            ]);
        }





        return $this->render('home/index.html.twig', [
            'photoList' => $photoList,
            'formSearchByTag' => $formSearchByTag->createView(),
        ]);
    }
}
