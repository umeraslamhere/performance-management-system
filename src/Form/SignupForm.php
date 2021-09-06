<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SignupForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name' , TextType::class , array('attr' =>
                array('class' => 'form-control')))
            ->add('email' , TextType::class , array('attr' =>
                array('class' => 'form-control')))  
            ->add('password' , TextType::class , [
                    'help' => 'choose a strong one!',
                ], array('attr' =>
                array('class' => 'form-control')))        
            ->add('phone' , TextType::class , array('required' =>
                false , 'attr' => array('class' => 'form-control')))


            ->add('role', ChoiceType::class, [
                'placeholder' => '-- Select --',
                'choices' => [
                    'System Admin'=> 'admin',
                    'Employee' => [
                        'Project Manager' => 'project_manager',
                        'Software Engineer' => 'software_engineer',
                        'Associate Software Engineer' => 'associate_software_engineer',
                        'Intern' => 'intern',
                    ],
                    'HR' => [
                        'Head' => 'head',
                        'General' => 'general',
                        'Intern' => 'intern',
                    ],

                ],
            ])
            ->add(
                'gender', 
                ChoiceType::class, 
                [
                    'choices' => [
                        'Male' => 'male',
                        'Female' => 'female',
                        'Other' => 'other'
                    ],
                'expanded' => true,
                'multiple' => false /* if true, it'll be a checkbox */
                ]
            )

            ->add('picture', FileType::class, [
                'label' => 'User Image (JPG file)',
                'mapped' => false,
                'required' => false,
            ])

            ->add('save' , SubmitType::class , array('label' 
                => 'Signup' , 'attr' => array('class' => 'btn btn-primary mt-3')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}