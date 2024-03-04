<?php

namespace App\Form;

use App\Entity\Recipes;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class RecipesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('content', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('description', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('questions', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('recipeSteps', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duration of the recipe (in minutes)',
                'empty_data' => '0'
            ])
            ->add('slug', TextType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->slugCompletion(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->timeStampGenerator(...));
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

    public function timeStampGenerator(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if (!$data instanceof Recipes) {
            return;
        }
        $data->setUpdatedAt(new \DateTimeImmutable());
        if (!$data->getId()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
