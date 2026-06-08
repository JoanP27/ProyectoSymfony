<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre',  TextType::class, [
                'attr' => [
                    'required' => false,
                    'class' => 'field',
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'required' => false,
                    'class' => 'field',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(
                        message: 'Please repear your password',
                    )
                ],
                'first_options'  => ['label' => 'Password', 'attr' => ['class' => 'field']],
                'second_options' => ['label' => 'Repeat Password', 'attr' => ['class' => 'field']],
                'invalid_message' => 'The password fields must match.',
            ])
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Selecciona una imagen',
                'data_class' => null,
                'label_attr' => [
                    'class' => 'btn invalid:btn-red btn-blue',
                ],
                'attr' => [
                    'class' => 'hidden',
                ],
                'constraints' => [
                    new NotNull(message: 'Por favor, selecciona una imagen para tu avatar.'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
