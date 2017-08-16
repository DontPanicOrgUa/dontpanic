<?php

namespace AdminBundle\Form;


use WebBundle\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titleRu', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Заголовок'
                ]
            ])
            ->add('titleEn', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Title'
                ]
            ])
            ->add('titleDe', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Titel'
                ]
            ])
            ->add('contentRu', CKEditorType::class, [
                'label' => 'Content',
                'config' => [
                    'toolbar' => 'my_toolbar'
                ]
            ])
            ->add('contentEn', CKEditorType::class, [
                'label' => 'Content',
                'config' => [
                    'toolbar' => 'my_toolbar'
                ]
            ])
            ->add('contentDe', CKEditorType::class, [
                'label' => 'Content',
                'config' => [
                    'toolbar' => 'my_toolbar'
                ]
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'Meta Title',
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'Meta Description',
            ])
            ->add('metaKeywords', TextType::class, [
                'label' => 'Meta Key Words',
                'attr' => [
                    'placeholder' => 'keyword, second keyword, keyword 3'
                ]
            ])
            ->add('isInMenu', ChoiceType::class, [
                'label' => 'Show In Menu',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Share',
                'attr' => [
                    'class' => 'btn-primary',
                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class
        ]);
    }
}
