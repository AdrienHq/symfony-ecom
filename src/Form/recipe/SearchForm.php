<?php

namespace App\Form\recipe;

use AllowDynamicProperties;
use App\Data\recipe\SearchData;
use App\Entity\Category;
use App\Entity\Course;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AllowDynamicProperties]
class SearchForm extends AbstractType
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'placeholder' => $this->translator->trans('form.search', [], 'searchRecipeForm')
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => false
            ])
            ->add('course', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Course::class,
                'expanded' => true,
                'multiple' => false
            ])
            ->add('minDuration', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('form.min_duration_placeholder', [], 'searchRecipeForm')
                ]
            ])
            ->add('maxDuration', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('form.max_duration_placeholder', [], 'searchRecipeForm')
                ]
            ])
            ->add('vegetarian', CheckboxType::class, [
                'label' => 'form.vegetarian',
                'translation_domain' => 'searchRecipeForm',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}