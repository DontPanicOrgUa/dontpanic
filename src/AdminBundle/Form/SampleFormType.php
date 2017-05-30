<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WebBundle\Entity\Sample;

class SampleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder
//            ->add()
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sample::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_sample_form_type';
    }
}
