<?php

namespace App\Controller\Admin;

use App\Entity\School;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('Name'),
            BooleanField::new('Status')->renderAsSwitch(),
            TextField::new('Coordinates'),
            ChoiceField::new('FoodRule')->setChoices($this->getFoodRuleChoices())
        ];
    }

    private function getFoodRuleChoices(): array
    {
        $return = [];
        foreach(School::getFoodRuleLabels() as $k => $v){
            $key = strtolower(str_replace('FOOD_', '', $k));
            $return[$key] = $v;
        }

        return $return;
    }

}
