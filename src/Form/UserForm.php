<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email' , TextType::class , array('attr' =>
            array('class' => 'form-control')))  
        ->add('password' , TextType::class , [
            'help' => 'choose a strong one!',
            ], array('attr' =>
            array('class' => 'form-control')))        
        ->add('save' , SubmitType::class , array('label' 
            => 'Login' , 'attr' => array('class' => 'btn btn-primary mt-3')));
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => User::class,
    //     ]);
    // }
}
