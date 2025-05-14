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

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_email', EmailType::class, [
                'attr' => [
                    'id' => 'login_form__email',
                    'class' => 'form-control',
                    'autofocus' => true,
                    'required' => true,
                ],
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Email cannot be blank.',
                    ]),
                    new Assert\Email([
                        'message' => 'Please enter a valid email address',
                    ])
                ]
            ])
            ->add('_password', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Password cannot be blank.',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
