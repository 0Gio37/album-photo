<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Commentaire;
use App\Entity\Photo;
use App\Entity\Tag;
use App\Entity\Theme;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getPrenom().' '.$user->getNom())
            ->displayUserAvatar(true);
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute("RETOUR AU SITE", 'fas fa-home', 'home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::LinkToCrud('Thémes', 'fas fa-sitemap', Theme::class);
        yield MenuItem::linkToCrud('Albums', 'fas fa-book', Album::class);
        yield MenuItem::linkToCrud('Photos', 'fas fa-camera', Photo::class);
        yield MenuItem::linkToCrud('Personnes identifiées', 'fas fa-users', Tag::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Commentaire::class);
    }
}
