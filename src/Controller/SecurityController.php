<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\Mailer;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="login")
     */

    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank');
    }

    /**
     * @Route("/sigin", name="sigin")
     */
    public function new(Request $request, UserPasswordHasherInterface $passwordEncoder, UserRepository $userRepository, Mailer $mailerService):Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $userName = $user->getUsername();
            $userMail = $user->getMail();
            $errorMsgDoubleUserInDB = "";

            if($userRepository->findBy(["username"=>$userName])){
                $errorMsgDoubleUserInDB = "Le pseudonyme est déjà utilisé ! ";
            }if ($userRepository->findBy(["mail"=>$userMail])){
                $errorMsgDoubleUserInDB = "L'e-mail est déjà existant ! ";
            }


            //send email to admin for validation -> a activer apres feat/uploadFile
 /*           $mailerService->sendMail(
                "gmessanges@hotmail.fr",
                "gmessanges@gmail.com",
                "Mon Album de Famille - Nouvelle inscription",
                "email/valid-user-by-admin.html.twig",
                [
                    "lastname" =>  $user->getNom(),
                    "firstname" =>  $user->getPrenom(),
                    "email" =>  $user->getMail(),
                    "pseudo" => $user -> getUsername(),
                ]
            );*/

            // Hashing password
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));

            //Ajout du role par défaut
            $user->setRoles(["ROLE_USER"]);

            //Add default status: ROLE_USER
           $user->setIsValid(false);
           try{$this->entityManager->persist($user);
               $this->entityManager->flush();
               return $this->redirectToRoute('confirmSignInMessage');
           }catch (ConstraintViolationException $e){
               $this->addFlash('supp', $errorMsgDoubleUserInDB);
               return $this->redirectToRoute('sigin');
           }
        }

        return $this->render(
            'security/sign-in.html.twig', [
                'form'=>$form->createView()]);
    }

    /**
     * @Route("/confirmation-sign-in", name="confirmSignInMessage")
     */
    public function confirmSignIn():Response
    {
        return $this->render('security/confirm-message-sign-in.html.twig', []);
    }
}
