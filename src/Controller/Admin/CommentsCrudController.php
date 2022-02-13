<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comments::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id','ID')->hideOnForm(),
            DateField::new('createAt', 'Créé le')->hideOnForm(),
            TextField::new('texte', 'Commentaires'),
            AssociationField::new('auteur','Auteur')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW);
    }

}
