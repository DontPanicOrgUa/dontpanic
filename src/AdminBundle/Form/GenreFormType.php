<?php

namespace AdminBundle\Form;


use WebBundle\Entity\Genre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GenreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameRu', TextType::class, [
                'label' => 'Genre [RU]',
                'attr' => [
                    'placeholder' => 'Приключения'
                ]
            ])
            ->add('nameEn', TextType::class, [
                'label' => 'Genre [EN]',
                'attr' => [
                    'placeholder' => 'Adventure'
                ]
            ])
            ->add('nameDe', TextType::class, [
                'label' => 'Genre [DE]',
                'attr' => [
                    'placeholder' => 'Abenteuer'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Genre',
                'attr' => [
                    'class' => 'btn-primary',
                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Genre::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_city_form_type';
    }
}
