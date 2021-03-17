<?php

namespace App\Controller\Admin;

use App\Entity\School;
use App\Entity\User;
use App\Repository\SchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('FirstName'),
            TextField::new('LastName'),
            TelephoneField::new('Mobil'),
            EmailField::new('Mail'),
            ChoiceField::new('Role')->setChoices($this->getUserRoleLabels()),
            BooleanField::new('Status')->renderAsSwitch(),
            AssociationField::new('School'),
            TextField::new('Acronym')->setMaxLength(3),

        ];
    }

    private function getUserRoleLabels(): array
    {
        $return = [];
        foreach(User::getRoleLabels() as $k => $v){
            $key = strtolower(str_replace('ROLE_', '', $k));
            $return[$key] = $v;
        }

        return $return;
    }

}
