<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AlbumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Album::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('Id','Identifiant')->hideOnForm(),
            TextField::new('titre')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            ->disable(Action::DELETE);
    }


}
