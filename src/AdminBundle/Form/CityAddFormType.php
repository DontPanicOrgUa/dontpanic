<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CityAddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city_name_ru', TextType::class, [
                'label' => 'City name [RU]'
            ])
            ->add('city_name_en', TextType::class, [
                'label' => 'City name [EN]'
            ])
            ->add('city_name_de', TextType::class, [
                'label' => 'City name [DE]'
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
