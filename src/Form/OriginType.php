<?php

namespace App\Form;

use App\Entity\Origin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class OriginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', TextType::class, [
                'label' => 'Pays d\'origine',
                'attr' => [
                    'placeholder' => 'Ex: France, Italie, Japon...',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un pays d\'origine']),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Le nom du pays ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
                    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Origin::class,
        ]);
    }
}