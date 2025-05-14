<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]
            ])
            ->add('first_name',TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('last_name',TextType::class,  [
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'invalid_message' => 'Passwords must match.',
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Password cannot be blank.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Your password must be at least {{ limit }} characters long.',
                    ]),
                    // new Assert\Regex([
                    //     'pattern' => '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                    //     'message' => 'Password must contain at least one uppercase letter, one number, and one special character.',
                    // ]),
                    new Assert\Regex([
                        'pattern' => '/^\S+$/',
                        'message' => 'Password must not contain spaces.',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
