<?php

namespace App\Form;

use App\Entity\Juego;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JuegoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class , [
                'attr' => [
                    'class' => 'field',
                ]
            ])
            ->add('descripcion', TextareaType::class , [
                'attr' => [
                    'class' => 'field resize-none',
                ]
            ])
            ->add('precio', NumberType::class, [
                'attr' => [
                    'class' => 'field',
                ]
            ])
            ->add('fecha', DateType::class, [
                'attr' => [
                    'class' => 'field',
                ]
            ])
            ->add('rutaImagen', FileType::class, [
                'label' => 'Selecciona una imagen',
                'data_class' => null,
                'label_attr' => [
                    'class' => 'btn btn-blue',
                ],
                'attr' => [
                    'class' => 'hidden',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juego::class,
        ]);
    }
}
