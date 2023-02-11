<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('path', FileType::class,[
                'label' => 'Ajout d\'image',
                'attr' => [
                    'class' => 'rounded'
                ]
            ])
            ->add('alt', TextType::class, [
                'label' => 'alt',
                'attr' => [
                    'class' => 'rounded'
                ]
            ])
        

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
            'attr' => [
                // désactivation validation HTML5
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}