<?php

namespace App\Form;

use AllowDynamicProperties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AllowDynamicProperties]
class CommentType extends AbstractType
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('comment.leave_comment', [], 'fillerWords'),
                    'id' => 'floatingTextarea'
                ],
                'label' => 'comment.your_comment',
                'translation_domain' => 'fillerWords',
                'label_attr' => ['for' => 'floatingTextarea'],
            ]);

    }
}