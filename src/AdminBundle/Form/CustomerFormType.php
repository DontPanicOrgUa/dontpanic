<?php

namespace AdminBundle\Form;


use WebBundle\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [])
            ->add('lastName', TextType::class, [])
            ->add('email', TextType::class, [])
            ->add('phone', TextType::class, [])
            ->add('percentage', IntegerType::class, [
                'attr' => [
                    'max' => 99,
                    'min' => 1
                ],
                'required' => true
            ])
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
            'data_class' => Customer::class
        ]);
    }
}
