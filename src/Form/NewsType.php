<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isHomeEvent', CheckboxType::class, [
                'label'    => 'Epingler à la page d\'accueil',
                'label_attr' => [
                    'class' => 'text-start d-block lh-lg ms-3'
                    
                ],
                'required' => false,
                'attr' => [
                    'class' => 'custom-checkbox mt-0'
                ],
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titre de la publication',
                'attr' => [
                    'class' => 'rounded',
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => 'Texte ',                
                'attr' => [
                    'class' => 'rounded',
                ],
            ])
            ->add('publishedAt', DateTimeType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'data' => new \DateTimeImmutable(),
                'attr' => [
                    'class' => 'rounded',
                ],
            ])
            ->add('pictures', PictureType::class, [
                'data_class' => null,
                'label_attr' => [
                    'class' => 'd-none'
                ]
            ])       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
