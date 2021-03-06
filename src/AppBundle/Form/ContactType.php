<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Az előadó e-mail címe',
                ]
            ])
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Az előadás címe',
                ]
            ])
            ->add('color', ColorType::class, [
                'label' => false,
            ])
            ->add('size', RangeType::class, [
                'attr' => [
                    'min' => 10,
                    'max' => 30,
                ]
            ])
            ->add('submit', SubmitType::class);
    }
}