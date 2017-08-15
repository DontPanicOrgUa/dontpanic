<?php

namespace AdminBundle\Form;


use WebBundle\Entity\Share;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ShareFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descriptionRu', TextareaType::class, [
                'label' => 'Description [Ru]',
                'attr' => [
                    'rows' => '6'
                ]
            ])
            ->add('descriptionEn', TextareaType::class, [
                'label' => 'Description [En]',
                'attr' => [
                    'rows' => '6'
                ]
            ])
            ->add('descriptionDe', TextareaType::class, [
                'label' => 'Description [De]',
                'attr' => [
                    'rows' => '6'
                ]
            ])
            ->add('imgRu', FileType::class, [
                'label' => ' upload *.jpg',
                'label_attr' => [
                    'class' => 'fa fa-upload  fa-2x'
                ],
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/jpeg'
                ]
            ])
            ->add('imgEn', FileType::class, [
                'label' => ' upload *.jpg',
                'label_attr' => [
                    'class' => 'fa fa-upload  fa-2x'
                ],
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/jpeg'
                ]
            ])
            ->add('imgDe', FileType::class, [
                'label' => ' upload *.jpg',
                'label_attr' => [
                    'class' => 'fa fa-upload  fa-2x'
                ],
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/jpeg'
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
            'data_class' => Share::class
        ]);
    }
}
