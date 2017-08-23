<?php

namespace AdminBundle\Form;


use WebBundle\Entity\Notification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NotificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titleRu', TextType::class, [
                'label' => 'Title [RU]',
                'attr' => [
                    'placeholder' => 'Escape Room Booking'
                ]
            ])
            ->add('titleEn', TextType::class, [
                'label' => 'Title [EN]',
                'attr' => [
                    'placeholder' => 'Бронь квесткомнаты'
                ]
            ])
            ->add('titleDe', TextType::class, [
                'label' => 'Title [De]',
                'attr' => [
                    'placeholder' => 'Buchung Zimmer'
                ]
            ])
            ->add('messageRu', CKEditorType::class, [
                'label' => '[RU]',
                'config' => [
                    'toolbar' => 'my_toolbar'
                ]
            ])
            ->add('messageEn', CKEditorType::class, [
                'label' => '[En]',
                'config' => [
                    'toolbar' => 'my_toolbar'
                ]
            ])
            ->add('messageDe', CKEditorType::class, [
                'label' => '[De]',
                'config' => [
                    'toolbar' => 'my_toolbar'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn-primary',
                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notification::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_city_form_type';
    }
}
