<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RequestStack;

class UserProfileForm extends AbstractType
{
    private $rs;

    public function __construct(RequestStack $requestStack) {

        $this->rs = $requestStack;

        //$route = $requestStack->getCurrentRequest()->get('_route');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $current_route = $this->rs->getCurrentRequest()->get('_route');
        $builder
        ->add('firstName' , TextType::class , array( 'attr' =>
                array('class' => 'form-control', 'read_only' =>true)))
            ->add('lastName' , TextType::class , array('attr' =>
                array('class' => 'form-control')))
            ->add('email' , TextType::class , array('attr' =>
                array('class' => 'form-control')));
            
            if($current_route == 'update_request'){ //Sign Up Requests
                $builder->add('roles', ChoiceType::class, [
                    'multiple' =>true,  
                    'placeholder' => '-- Select --',
                    'choices' => [
                        'System Admin'=> 'ROLE_ADMIN',
                        'Employee' => [
                            'Project Manager' => 'ROLE_PM',
                            'Software Engineer' => 'ROLE_SE',
                            'Associate Software Engineer' => 'ROLE_ASE',
                            'Intern' => 'ROLE_INTERN',
                        ],
                        'HR' => [
                            'Head' => 'ROLE_HR_HEAD',
                            'General' => 'ROLE_HR_GENERAL',
                            'Intern' => 'ROLE_HR_INTERN',
                        ],

                    ],
                ])
                ->add('save' , SubmitType::class , array('label' 
                    => 'Update' , 'attr' => array('class' => 'btn btn-primary mt-3')));            
            }   

            if($current_route != 'update_request'){ //User Updating/Watching his/her profile
                $builder->add('phone' , TextType::class , array('required' =>
                    false , 'attr' => array('class' => 'form-control')));            
            }       

            if($current_route == 'update_user' ){
                $builder->add(
                    'gender', 
                    ChoiceType::class, 
                    [
                        'choices' => [
                            'Male' => 'male',
                            'Female' => 'female',
                            'Other' => 'other'
                        ],
                    'expanded' => true,
                    'disabled' =>false,  
                    'multiple' => false /* if true, it'll be a checkbox */
                    ]
                )
                ->add('save' , SubmitType::class , array('label' 
                => 'Update' , 'attr' => array('class' => 'btn btn-primary mt-3')));
            }
            elseif($current_route != 'update_request'){
                $builder->add(
                    'gender', 
                    ChoiceType::class, 
                    [
                        'choices' => [
                            'Male' => 'male',
                            'Female' => 'female',
                            'Other' => 'other'
                        ],
                    'expanded' => true,
                    'disabled' =>true,  
                    'multiple' => false /* if true, it'll be a checkbox */
                    ]
                );
            }

            if($current_route != 'update_request'){ //User Updating/Watching his/her profile
                $builder->add('picture', FileType::class, [
                    'label' => 'User Image (JPG file)',
                    'mapped' => false,
                    'required' => false,
                ]);         
            }            
            
            $builder->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
