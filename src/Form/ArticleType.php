<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('articleThumbnailFile', FileType::class, [
                'label' => 'articleForm.thumbnail',
                'translation_domain' => 'articleForm',
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'articleForm.name',
                'translation_domain' => 'articleForm'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'articleForm.content',
                'translation_domain' => 'articleForm',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'label' => 'articleForm.slug',
                'translation_domain' => 'articleForm',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'articleForm.save',
                'translation_domain' => 'articleForm',
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
        if (!$data instanceof Article) {
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
            'data_class' => Article::class,
        ]);
    }
}
