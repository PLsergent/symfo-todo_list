<?php

namespace App\Form\Admin;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminTodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('priority', ChoiceType::Class, [
                'choices' => [
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
                ]
            ])
            ->add('status', ChoiceType::Class, [
                'choices' => [
                    'New' => 'New',
                    'In progress' => 'In progress',
                    'Almost done' => 'Almost done'
                ]
            ])
            ->add('deadline', DateType::Class, [
                'widget' => 'choice'
            ])
            ->add('user')
            ->add('categories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}
