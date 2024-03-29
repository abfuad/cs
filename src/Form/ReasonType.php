<?php

namespace App\Form;

use App\Entity\Reason;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            
            ->add('description')
            ->add('startAt',DateType::class,[
                'widget' => 'single_text',

            ])
            ->add('endAt', DateType::class,[
                'widget' => 'single_text',

            ])

           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reason::class,
        ]);
    }
}
