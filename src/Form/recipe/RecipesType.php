<?php

namespace App\Form\recipe;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Recipes;
use App\Form\QuantityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                'label' => 'recipeForm.name',
                'translation_domain' => 'recipeForm'
            ])
            ->add('thumbnailFile', FileType::class, [
                'label' => 'recipeForm.thumbnailFile',
                'translation_domain' => 'recipeForm',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'recipeForm.description',
                'translation_domain' => 'recipeForm',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('questions', TextareaType::class, [
                'label' => 'recipeForm.questions',
                'translation_domain' => 'recipeForm',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('duration', IntegerType::class, [
//                'label' => 'Duration of the recipe (in minutes)',
                'label' => 'recipeForm.duration',
                'translation_domain' => 'recipeForm',
                'empty_data' => '0'
            ])
            ->add('category', EntityType::class, [
                'label' => 'recipeForm.category',
                'translation_domain' => 'recipeForm',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false
            ])
            ->add('course', EntityType::class, [
                'label' => 'recipeForm.course',
                'translation_domain' => 'recipeForm',
                'class' => Course::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
//                'by_reference' => false
            ])
            ->add('slug', TextType::class, [
                'label' => 'recipeForm.slug',
                'translation_domain' => 'recipeForm',
                'required' => false
            ])
            ->add('quantities', CollectionType::class,[
                'label' => 'recipeForm.quantities',
                'translation_domain' => 'recipeForm',
                'entry_type' => QuantityType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'attr' =>[
                    'data-controller' => 'form-collection',
                    'data-form-collection-add-label-value' => 'Add ingredient',
                    'data-form-collection-delete-label-value' => 'Delete ingredient'
                ]
            ])
            ->add('vegetarian', CheckboxType::class, [
                'label' => 'recipeForm.vegetarian',
                'translation_domain' => 'recipeForm',
                'required' => false,
                'empty_data' => null
            ])
            ->add('save', SubmitType::class, [
                'label' => 'recipeForm.save',
                'translation_domain' => 'recipeForm',
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
