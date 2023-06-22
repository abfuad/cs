<?php

namespace App\Form;

use App\Entity\ClearanceDepartment;
use App\Entity\Department;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClearanceDepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $role=["System Admin" => "System Admin","Department Head"=>"Department Head","Manager"=>"Manager","Gate Keeper"=>"Gate Keeper","Student"=>"Student"];

        $builder
        ->add('orderNumber',IntegerType::class)

        ->add('role', ChoiceType::class,["choices" => $role,"placeholder"=>"Select Role"])
        ->add('department',EntityType::class,[
          'class'=>Department::class,
          'placeholder'=>'choose department'
  
      ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClearanceDepartment::class,
        ]);
    }
}
