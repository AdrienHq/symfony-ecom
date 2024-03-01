<?php

namespace App\Controller\Admin;

use App\Entity\Recipes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipes::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
