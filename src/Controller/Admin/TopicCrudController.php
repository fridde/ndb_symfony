<?php

namespace App\Controller\Admin;

use App\Entity\Topic;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class TopicCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Topic::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('ShortName'),
            TextField::new('LongName'),
            ChoiceField::new('Segment')->setChoices($this->getSegmentLabels()),
            IntegerField::new('VisitOrder'),
            TextField::new('Food'),
            UrlField::new('Url')
        ];
    }

    private function getSegmentLabels(): array
    {
        $return = [];

        foreach (Topic::getSegmentLabels() as $k => $v) {
            $key = strtolower(str_replace('SEGMENT_', '', $k));
            $return[$key] = $v;
        }

        return $return;
    }
}
