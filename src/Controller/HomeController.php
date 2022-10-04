<?php

namespace App\Controller;

use App\Entity\LienTagPhoto;
use App\Entity\Photo;
use App\Entity\SearchByTag;
use App\Entity\SearchByTheme;
use App\Entity\SearchByYear;
use App\Entity\Theme;
use App\Form\SearchByTagType;
use App\Form\SearchByThemeType;
use App\Form\SearchByYearType;
use App\Repository\PhotoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $photoList = $PhotoRepository->findBy([], ['id'=>'DESC'],16);
        $defaultDataTag = ['message' => 'null'];
        $formSearchByTag = $this->createFormBuilder($defaultDataTag)
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 w-96 p-4'],
                'label'=> false,
                'required'=>false,
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 w-96 p-4'],
                'label'=>false,
                'required'=>false,
            ])
            ->getForm();
        $formSearchByTag->handleRequest($request);
        if ($formSearchByTag->isSubmitted() && $formSearchByTag->isValid()) {
            $dataSearched = $formSearchByTag->getData();
            $lientagPhoto = $this->entityManager->getRepository(LienTagPhoto::class)->findAll();
            return $this->render('search/by-tag.html.twig', [
                'dataSearched'=>$dataSearched,
                'lientagPhoto'=>$lientagPhoto,
            ]);
        }

        $defaultDataPlace = ['message' => 'null'];
        $formSearchPlace = $this->createFormBuilder($defaultDataPlace)
            ->add('lieu', TextType::class, [
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 w-96 p-4'],
                'label'=>false,
                'required'=>true,
            ])
            ->getForm();
        $formSearchPlace->handleRequest($request);
        if ($formSearchPlace->isSubmitted() && $formSearchPlace->isValid()) {
            $dataSearched = $formSearchPlace->getData();
            $photoList = $this->entityManager->getRepository(Photo::class)->findAll();
            return $this->render('search/by-place.html.twig', [
                'dataSearched'=>$dataSearched,
                'photoList'=>$photoList,
            ]);
        }

        $defaultDataYear = ['message' => 'null'];
        $formSearchByYear = $this->createFormBuilder($defaultDataYear)
            ->add('annee', TextType::class, [
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 w-96 p-4'],
                'label'=>false,
                'required'=>true,

            ])
            ->getForm();
        $formSearchByYear->handleRequest($request);
        if ($formSearchByYear->isSubmitted() && $formSearchByYear->isValid()) {
            $dataSearched = $formSearchByYear->getData();
            $photoList = $this->entityManager->getRepository(Photo::class)->findAll();
            return $this->render('search/by-year.html.twig', [
                'dataSearched'=>$dataSearched,
                'photoList'=>$photoList,
            ]);
        }

        $defaultDataTheme = ['message' => 'null'];
        $formSearchByTheme = $this->createFormBuilder($defaultDataTheme)
            ->add('Nom', EntityType::class, [
                'class'=>Theme::class,
                'choice_label'=>'titre',
                'label'=>false,
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 flex justify-center m-auto px-16 py-2'],
            ])
            ->getForm();
        $formSearchByTheme->handleRequest($request);
        if ($formSearchByTheme->isSubmitted() && $formSearchByTheme->isValid()) {
            $dataSearchedTheme = $formSearchByTheme->getData();
            $photoList = $this->entityManager->getRepository(Photo::class)->findAll();
            return $this->render('search/by-theme.html.twig', [
                'dataSearched'=>$dataSearchedTheme,
                'photoList'=>$photoList,
            ]);
        }

        return $this->render('home/index.html.twig', [
            'photoList' => $photoList,
            'formSearchByTag' => $formSearchByTag->createView(),
            'formSearchByYear' => $formSearchByYear->createView(),
            'formSearchByTheme' => $formSearchByTheme->createView(),
            'formSearchPlace' => $formSearchPlace->createView(),
            'uploadImagesDestination' => $_ENV['UPLOAD_IMAGES_DESTINATION'],
            'urlCloudinary'=> $_ENV['URL_CLOUDINARY']
        ]);
    }
}
