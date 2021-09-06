<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToTeamForm extends AbstractType
{
    private $teamId;
    private $managerId;
    public function __construct()
    {
        $this->teamId = null;
        $this->managerId = null;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->teamId=$options['teamId'];
        $this->managerId=$options['managerId'];
        $builder
        ->add('name', EntityType::class, [
            'class' => Team::class,
            'query_builder' => function (EntityRepository $er ) {
                return $er->createQueryBuilder('t')
                     ->where('t.id LIKE :element')
                     ->setParameter('element', $this->teamId);
             },
            ], array('attr' =>['class' => 'form-control'])
        )

        ->add('managerId', EntityType::class, [
            'class' => User::class,
            'query_builder' => function (EntityRepository $er ) {
                return $er->createQueryBuilder('u')
                     ->where('u.id LIKE :element')
                     ->setParameter('element', $this->managerId);
             },
            ], array('attr' =>['class' => 'form-control'])
        )  
        ->add('memberId', EntityType::class, [
            'multiple'=> true,
            'class' => User::class,
        ])   
        ->add('save' , SubmitType::class , array('label' 
            => 'Add to team' , 'attr' => array('class' => 'btn btn-primary mt-3')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'teamId' => null,
            'managerId' => null,
        ]);
    }
}
