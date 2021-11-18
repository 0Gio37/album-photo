<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ThemeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Theme::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre', 'Titre [Format Majuscule seulement des lettres]'),
        ];
    }

}
