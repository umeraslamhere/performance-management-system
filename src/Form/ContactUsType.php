<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
/* or */
use Symfony\Component\Form\Extension\Core\Type\TextareaType as TypeTextareaType;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title' , TextType::class , [
                'attr' => [
                    'placeholder' => 'Enter Title',
                    'class' => 'custom_class'
                ],
            ])
            ->add('body' , TextAreaType::class ,  [
                'attr' => [
                    'placeholder' => 'Enter Description here',
                    
                ]
            ])
            ->add('save' , SubmitType::class , [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
