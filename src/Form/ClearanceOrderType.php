<?php

namespace App\Form;

use App\Entity\ClearanceOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClearanceOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            // ->add('code')
            ->add('description')
            ->add('role')
         
            ->add('parent')
            ->add('department')
            // ->add('clearanceOrders')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClearanceOrder::class,
        ]);
    }
}
