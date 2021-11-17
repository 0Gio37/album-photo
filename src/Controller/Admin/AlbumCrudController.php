<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Theme;
use App\Repository\ThemeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\Traits\AbstractTrait;
use Symfony\Component\Validator\Constraints\Choice;

class AlbumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Album::class;
    }


    public function configureFields(string $pageName ): iterable
    {

        return [

            IdField::new('Id','Identifiant')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('annee'),
            AssociationField::new('theme')


        ];
    }

    /*
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            //->disable(Action::NEW)
            ->disable(Action::DELETE);
    }*/

}
