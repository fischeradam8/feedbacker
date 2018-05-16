<?php

declare(strict_types=1);

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('title', TextType::class, [
               'data' => $options['title']
           ])
           ->add('feedback', TextareaType::class)
           ->add('submit', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
          'title' => null,
       ]);
    }

}