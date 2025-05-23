<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom du tag',
                'attr' => [
                    'placeholder' => 'Ex: Végétarien, Sans gluten, Rapide...',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom pour le tag']),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Le nom du tag ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}