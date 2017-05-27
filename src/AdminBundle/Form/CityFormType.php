<?php

namespace AdminBundle\Form;

use WebBundle\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameRu', TextType::class, [
                'label' => 'City name [RU]',
                'attr' => [
                    'placeholder' => 'Киев'
                ]
            ])
            ->add('nameEn', TextType::class, [
                'label' => 'City name [EN]',
                'attr' => [
                    'placeholder' => 'Kiev'
                ]
            ])
            ->add('nameDe', TextType::class, [
                'label' => 'City name [DE]',
                'attr' => [
                    'placeholder' => 'Kiew'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save City',
                'attr' => [
                    'class' => 'btn-primary',
                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => City::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_city_form_type';
    }
}
