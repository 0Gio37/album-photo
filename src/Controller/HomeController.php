<?php

namespace App\Controller;

use App\Entity\LienTagPhoto;
use App\Entity\Photo;
use App\Entity\SearchByTag;
use App\Entity\SearchByTheme;
use App\Entity\SearchByYear;
use App\Form\SearchByTagType;
use App\Form\SearchByThemeType;
use App\Form\SearchByYearType;
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
            $lientagPhoto = $this->entityManager->getRepository(LienTagPhoto::class)->findAll();

            return $this->render('search/by-tag.html.twig', [
                'dataSearched'=>$dataSearched,
                'lientagPhoto'=>$lientagPhoto,
                ]);
        }

        $byYear = new SearchByYear();
        $formSearchByYear = $this->createForm(SearchByYearType::class,$byYear);
        $formSearchByYear->handleRequest($request);
        if($formSearchByYear->isSubmitted() && $formSearchByYear->isValid()) {
            $dataSearched = $formSearchByYear->getData();
            $photoList = $this->entityManager->getRepository(Photo::class)->findAll();

            return $this->render('search/by-year.html.twig', [
                'dataSearched'=>$dataSearched,
                'photoList'=>$photoList,
            ]);
        }

        $byTheme = new SearchByTheme();
        $formSearchByTheme = $this->createForm(SearchByThemeType::class,$byTheme);
        $formSearchByTheme->handleRequest($request);
        if($formSearchByTheme->isSubmitted() && $formSearchByTheme->isValid()) {
            $dataSearched = $formSearchByTheme->getData();
            $photoList = $this->entityManager->getRepository(Photo::class)->findAll();

            return $this->render('search/by-theme.html.twig', [
                'dataSearched'=>$dataSearched,
                'photoList'=>$photoList,
            ]);
        }

        return $this->render('home/index.html.twig', [
            'photoList' => $photoList,
            'formSearchByTag' => $formSearchByTag->createView(),
            'formSearchByYear' => $formSearchByYear->createView(),
            'formSearchByTheme' => $formSearchByTheme->createView(),
        ]);
    }
}
