<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\UserInfo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         BaseFormType::userForm($builder);
        $builder
        ->add('username',TextType::class,['mapped'=>false])        
        ->add('password', RepeatedType::class, [
            'mapped'=>false,
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'New Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ])
        ->add('department',EntityType::class,[
            'class'=>Department::class,
            'placeholder'=>'select department'
        ])
        ->add('idNumber',TextType::class,[
            // 'mapped'=>false
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
            // Configure your form options here
        ]);
    }
}
