<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', \Symfony\Component\Form\Extension\Core\Type\EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.email.blank',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Email([
                        'message' => 'register.email.invalid',
                    ]),
                ],
                'translation_domain' => 'messages',
            ])
            ->add('username', TextType::class, [
                'label' => 'register.username',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.username.blank',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'register.username.too_short',
                        'max' => 30,
                    ])
                ],
                'translation_domain' => 'messages',
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.password.blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'register.password.too_short',
                        'max' => 25,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                        'message' => 'register.password.weak'
                    ]),
                    new NotCompromisedPassword(([
                        'message' => 'register.password.compromised'
                    ]))
                ],
                'translation_domain' => 'messages',]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
