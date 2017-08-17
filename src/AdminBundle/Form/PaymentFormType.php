<?php

namespace AdminBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use WebBundle\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PaymentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', IntegerType::class, [
                'required' => true
            ])
            ->add('data', TextareaType::class, [
                'label' => 'Comment',
                'data' => 'Customer paid by cash.'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Payment',
                'attr' => [
                    'class' => 'btn-primary',
//                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class
        ]);
    }
}
