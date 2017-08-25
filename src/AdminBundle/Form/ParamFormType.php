<?php

namespace AdminBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ParamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locale', ChoiceType::class, [
                'label' => 'Default locale',
                'choices' => [
                    'DE' => 'de',
                    'EN' => 'en',
                    'RU' => 'ru',
                ]
            ])
            ->add('adminEmail', TextType::class, [
                'label' => 'Admin email(need for callbacks)',
            ])
            ->add('liqpaySandbox', ChoiceType::class, [
                'label' => 'Payment test mode (liqpay.sandbox)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('smsCustomerBooked', ChoiceType::class, [
                'label' => 'Send SMS to Customer (Game booked)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('smsCustomerRemind', ChoiceType::class, [
                'label' => 'Send SMS reminder to Customer (Upcoming game)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('smsManagerBooked', ChoiceType::class, [
                'label' => 'Send SMS to managers (Game booked)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('smsManagerRemind', ChoiceType::class, [
                'label' => 'Send SMS reminder to Managers (Upcoming game)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailCustomerBooked', ChoiceType::class, [
                'label' => 'Send email to Customer (Game Booked)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailCustomerFeedback', ChoiceType::class, [
                'label' => 'Send email to Customer (Feedback accepted)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailCustomerCallback', ChoiceType::class, [
                'label' => 'Send email to Customer (Callback accepted)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailCustomerReward', ChoiceType::class, [
                'label' => 'Send email to Customer (Got new reward)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailManagerBooked', ChoiceType::class, [
                'label' => 'Send email to Manager (Game Booked)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailManagerFeedback', ChoiceType::class, [
                'label' => 'Send email to Manager (Feedback accepted)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailManagerCallback', ChoiceType::class, [
                'label' => 'Send email to Manager (Callback accepted)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailManagerReward', ChoiceType::class, [
                'label' => 'Send email to Manager (New reward for customer)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('emailManagerPayment', ChoiceType::class, [
                'label' => 'Send email to Manager (New payment)',
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ]
            ])
            ->add('discountDiscount', IntegerType::class, [
                'label' => 'Default discount value in percentage %',
                'attr' => [
                    'max' => 99,
                    'min' => 1
                ],
                'required' => true
            ])
            ->add('discountReward', IntegerType::class, [
                'label' => 'Default percentage of the transaction that the client will receive',
                'attr' => [
                    'max' => 99,
                    'min' => 1
                ],
                'required' => true
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Parameters',
                'attr' => [
                    'class' => 'btn-primary',
//                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'parameters' => null
        ]);
    }
}
