<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FeedbackForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'gender', 
            ChoiceType::class, 
            [
                'choices' => [
                    '+ve' => 'positive',
                    '-ve' => 'negative',
                    'neu' => 'neutral'
                ],
            'expanded' => true,
            'multiple' => false /* if true, it'll be a checkbox */
            ]
        )
        ->add('save' , SubmitType::class , array('label' 
            => 'Feedback' , 'attr' => array('class' => 'btn btn-primary mt-3')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
