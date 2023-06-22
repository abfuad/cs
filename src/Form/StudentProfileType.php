<?php

namespace App\Form;

use App\Entity\StudentProfile;
use App\Entity\UserInfo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
         
            ->add('description')
             ->add('solved',CheckboxType::class,[
                'required'=>false
             ])
         
            ->add('student',EntityType::class,[
                'class'=>UserInfo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->getStudent();
                },
                'choice_label' => 'idNumber',
                'placeholder' => 'Choose Student IdNumber',

            
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StudentProfile::class,
        ]);
    }
}
