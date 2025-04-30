<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Origin;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'recipe.form.title'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'recipe.form.description'
            ])
            ->add('origin', EntityType::class, [
                'class' => Origin::class,
                'choice_label' => 'country',
                'label' => 'recipe.form.origin',
                'required' => false,
                'placeholder' => 'recipe.form.origin.placeholder',
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'label',
                'label' => 'recipe.form.tags',
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('ingredients', TextareaType::class, [
                'label' => 'recipe.form.ingredients'
            ])
            ->add('steps', TextareaType::class, [
                'label' => 'recipe.form.steps'
            ])
            ->add('servings', IntegerType::class, [
                'label' => 'recipe.form.servings',
                'required' => false,
            ])
            ->add('cookingTime', IntegerType::class, [
                'label' => 'recipe.form.cooking_time',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
