<?php

namespace App\Form;

use app\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'name',
                'translation_domain' => 'contact',
                'empty_data' => ''
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
                'translation_domain' => 'contact',
                'empty_data' => ''
            ])
            ->add('message', TextareaType::class, [
                'label' => 'message',
                'translation_domain' => 'contact',
                'empty_data' => ''
            ])
            ->add('save', SubmitType::class, [
                'label' => 'send',
                'translation_domain' => 'contact',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
