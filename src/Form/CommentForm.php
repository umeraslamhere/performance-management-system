<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentForm extends AbstractType
{
    private $on;
    private $by;
    public function __construct()
    {
        $this->on = null;
        $this->by = null;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->by=$options['commentBy']->getId();
        $this->on=$options['commentOn']->getId();
        $builder
        ->add('content' , TextareaType::class , array('attr' =>
            array('class' => 'form-control'))) 

        ->add('managerId', EntityType::class, [
            
            'class' => User::class,
            'query_builder' => function (EntityRepository $er ) {
                return $er->createQueryBuilder('u')
                     ->where('u.id LIKE :element')
                     ->setParameter('element', $this->by)
                    ->orderBy('u.firstName', 'ASC');
             },
            ], array('attr' =>['class' => 'form-control'])
        )  
        ->add('subordinateId', EntityType::class, [
            'class' => User::class,
            'query_builder' => function (EntityRepository $er ) {
                return $er->createQueryBuilder('u')
                     ->where('u.id LIKE :element')
                     ->setParameter('element', $this->on)
                    ->orderBy('u.firstName', 'ASC');
             },
            ], array('attr' =>['class' => 'form-control'])
        )   
        ->add('save' , SubmitType::class , array('label' 
            => 'Add Comment' , 'attr' => array('class' => 'btn btn-primary mt-3')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'commentBy' => null,
            'commentOn' => null,
        ]);
    }
}
