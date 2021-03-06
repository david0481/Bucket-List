<?php

namespace App\Form;

use App\Entity\Wish;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title : ',
                'required'   => true,
                'attr' => [
                    'placeholder' => 'Titre du wish',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description : '
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur : '
            ])
            ->add('categorie', null, ["choice_label" => "name"])
/*             ->add('isPublished', HiddenType::class, [
            ])
            ->add('dateCreated', HiddenType::class, [
                ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
