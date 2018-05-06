<?php

namespace AdminBundle\Form;

use RoomBundle\Entity\Blank;
use RoomBundle\Entity\Price;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PriceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dayOfWeek', ChoiceType::class, [
                'label' => 'Day Of Week',
                'placeholder' => 'Choose the day',
                'choices' => [
                    'Monday' => 'monday',
                    'Tuesday' => 'tuesday',
                    'Wednesday' => 'wednesday',
                    'Thursday' => 'thursday',
                    'Friday' => 'friday',
                    'Saturday' => 'saturday',
                    'Sunday' => 'sunday'
                ]
            ])
            ->add('blank', EntityType::class, [
                'label' => 'Time',
                'class' => Blank::class,
                'placeholder' => 'Choose the Time',
                'choices' => $options['blanks']
            ])
            ->add('players')
            ->add('price')
            ->add('save', SubmitType::class, [
                'label' => 'Save Time',
                'attr' => [
                    'class' => 'btn-primary',
                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Price::class,
            'blanks' => null,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_blank_form_type';
    }
}
