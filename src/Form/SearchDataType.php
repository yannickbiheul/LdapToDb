<?php

namespace App\Form;

use App\Entity\PeopleRecord;
use App\Entity\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', EntityType::class, [
                'class' => PeopleRecord::class,
                'placeholder' => 'Entrez un nom',
                'autocomplete' => true,
                'attr' => [
                    'autofocus' => true,
                    'style' => 'width: 400px !important; border: 1px solid #ccc;',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
        ]);
    }
}
