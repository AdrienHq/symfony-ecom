<?php

namespace App\Form;

use App\Entity\Rating;
use App\Entity\Recipes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stars', IntegerType::class, [
                'label' => 'Rating of the recipe',
                'empty_data' => '0'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Rate !'
            ])
        ;
    }
}