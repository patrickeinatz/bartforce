<?php

namespace App\Form;

use App\Entity\ForumPost;
use App\Entity\PostContentModule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('postContentModule', EntityType::class, [
                'class' => PostContentModule::class,
                'choice_label' => 'title',
                'expanded' => true,
                'label' => false
            ])
            ->add('postContent')
            ->add('postText',TextareaType::class, [
                'attr' => [
                    'cols' => '5',
                    'rows' => '5',
                    'maxlength' => 255
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumPost::class,
        ]);
    }
}
