<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
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
    public function addPhoto(Request $request, UserRepository $UserRepository, EntityManagerInterface $em ): Response
    {



        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $currentUserId = $this->security->getUser()->getId();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUserObject= $UserRepository->findOneBy(['id'=> $currentUserId]);

            $photo->setAuteur($currentUserObject);
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
        }

        return $this->render(
            'home/addPhoto.html.twig', [
            'photoForm' => $form->createView(),
        ]);
    }
}
