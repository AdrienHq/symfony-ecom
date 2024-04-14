<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('slug', TextType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->slugCompletion(...));
    }

    public function slugCompletion(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['name']));
        }
        $event->setData($data);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
