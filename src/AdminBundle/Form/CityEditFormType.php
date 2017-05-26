<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CityEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city_name_ru', TextType::class, [
                'label' => 'City name [RU]',
                'property_path' => 'translations[ru].name'
            ])
            ->add('city_name_en', TextType::class, [
                'label' => 'City name [EN]',
                'property_path' => 'translations[en].name'
            ])
            ->add('city_name_de', TextType::class, [
                'label' => 'City name [DE]',
                'property_path' => 'translations[de].name',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save City',
                'attr' => ['class' => 'btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_city_form_type';
    }
}
