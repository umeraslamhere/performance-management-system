<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
/* or */
use Symfony\Component\Form\Extension\Core\Type\TextareaType as TypeTextareaType;

class TeamForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name' , TextType::class , array('attr' =>
            array('class' => 'form-control')))  
        ->add('manager' , EntityType::class , [
            
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
