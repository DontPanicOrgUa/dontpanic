<?php

namespace AdminBundle\Form;


use AdminBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationForm;

class AddUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'attr' => [
                    'class' => 'phone_number'
                ]
            ])
            ->add('enabled', ChoiceType::class, [
                'label' => 'Is active',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save User',
                'attr' => [
                    'class' => 'btn-primary',
                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['registration']
        ]);
    }

    public function getParent()
    {
        return BaseRegistrationForm::class;
    }
}