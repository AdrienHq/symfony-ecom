<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stars', ChoiceType::class, [
                'label' => 'Rate it ?',
                'choices' => $this->generateChoices(),
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Rate !'
            ]);
    }

    private function generateChoices(): array
    {
        $choices = [];
        for ($i = 0; $i <= 10; $i++) {
            $choices[$i / 2] = number_format($i / 2, 1);
        }
        return $choices;
    }
}