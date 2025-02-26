<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdditemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Photos du produit',
                'mapped' => true,
                'required' => true,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'image-upload'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Titre de l\'annonce',
                'attr' => [
                    'placeholder' => 'Ex: Maillot exterieur PSG'
                ]
            ])
            ->add('class', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Chaussures' => 'chaussures',
                    'Maillots' => 'maillots',
                    'Shorts' => 'shorts',
                    'Joggings' => 'joggings',
                    'Chaussettes' => 'chaussettes'
                ],
                'required' => true
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix (€)',
                'attr' => [
                    'placeholder' => '0.00'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez votre produit en détail...'
                ]
            ])
            ->add('mainfeatures', TextareaType::class, [
                'label' => 'Caractéristiques principales',
                'attr' => [
                    'placeholder' => 'Une caractéristique par ligne...'
                ]
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité en stock',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'placeholder' => 'Ex: 10'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}