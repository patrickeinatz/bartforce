<?php

namespace App\Form;

use App\Entity\ForumTopic;
use App\Entity\TopicContentModule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumTopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('topicContentModule', EntityType::class, [
                'class' => TopicContentModule::class,
                'choice_label' => 'icon',
                'expanded' => true,
            ])
            ->add('topicContent')
            ->add('topicText',TextareaType::class, [
                'attr' => [
                    'cols' => '5',
                    'rows' => '5'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumTopic::class,
        ]);
    }

}
