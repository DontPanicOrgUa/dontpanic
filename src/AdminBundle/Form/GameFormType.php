<?php

namespace AdminBundle\Form;


use WebBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('result', IntegerType::class, [
                'label' => 'Result',
                'attr' => [
                    'placeholder' => 'minutes'
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo *.jpg',
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/jpeg'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Game',
                'attr' => [
                    'class' => 'btn-primary',
//                    'formnovalidate' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class
        ]);
    }
}
