<?php

namespace AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use WebBundle\Entity\City;
use WebBundle\Entity\Currency;
use WebBundle\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use WebBundle\Repository\CityRepository;

class RoomFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titleRu', TextType::class, [
                'label' => '[RU]',
            ])
            ->add('titleEn', TextType::class, [
                'label' => '[EN]',
            ])
            ->add('titleDe', TextType::class, [
                'label' => '[DE]',
            ])
            ->add('descriptionRu', TextareaType::class, [
                'label' => '[RU]',
                'attr' => [
                    'rows' => '6'
                ]
            ])
            ->add('descriptionEn', TextareaType::class, [
                'label' => '[En]',
                'attr' => [
                    'rows' => '6'
                ]
            ])
            ->add('descriptionDe', TextareaType::class, [
                'label' => '[De]',
                'attr' => [
                    'rows' => '6'
                ]
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo *.png',
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/png'
                ]
            ])
            ->add('background', FileType::class, [
                'label' => 'Background *.jpg',
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/jpeg'
                ]
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'placeholder' => 'Choose a City',
                'query_builder' => function (CityRepository $repo) {
                    return $repo->createAlphabeticalQueryBuilder();
                }
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'placeholder' => 'Choose a Currency'
            ])
            ->add('coordinates', TextType::class, [
                'label' => 'Google coordinates',
            ])
            ->add('addressRu', TextType::class, [
                'label' => 'Address [RU]',
            ])
            ->add('addressEn', TextType::class, [
                'label' => 'Address [EN]',
            ])
            ->add('addressDe', TextType::class, [
                'label' => 'Address [DE]',
            ])
            ->add('difficulty', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10
                ],
                'data' => 5
            ])
            ->add('timeMax', IntegerType::class, [
                'label' => 'Time max in minutes',
                'attr' => [
                    'min' => 1,
                    'value' => 60
                ]
            ])
            ->add('playersMin', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4
                ],
                'data' => 2
            ])
            ->add('playersMax', ChoiceType::class, [
                'choices' => [
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10
                ],
                'data' => 4
            ])
            ->add('ageMin', IntegerType::class, [
                'attr' => [
                    'min' => 3,
                    'max' => 90,
                    'value' => 14
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
                'label' => 'Save Room',
                'attr' => [
                    'class' => 'btn-primary',
                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Room::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_city_form_type';
    }
}
