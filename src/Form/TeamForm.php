<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TeamForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $element= "ROLE_PM";
        $builder
        ->add('name' , TextType::class , array('attr' =>
            array('class' => 'form-control'))) 

        ->add('managerId', EntityType::class, [
            'class' => User::class,
            'query_builder' => function (EntityRepository $er) {
               return $er->createQueryBuilder('u')
                    ->where('u.roles LIKE :element')
                    ->setParameter('element', '%ROLE_PM%')
                   ->orderBy('u.firstName', 'ASC');
            },
        ])
        ->add('memberId', EntityType::class, [
            'multiple'=> true,
            'class' => User::class,
        ])  
        ->add('save' , SubmitType::class , array('label' 
            => 'Create' , 'attr' => array('class' => 'btn btn-primary mt-3')));
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => User::class,
    //     ]);
    // }
}
