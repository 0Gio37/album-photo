<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
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
    public function addPhoto(Request $request): Response
    {

        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $currentUser = $this->security->getUser();
        dd($currentUser->getId());

        return $this->render(
            'home/addPhoto.html.twig', [
            'photoForm' => $form->createView(),
        ]);
    }
}
