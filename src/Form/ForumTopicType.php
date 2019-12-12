<?php

namespace App\Form;

use App\Entity\ForumTopic;
use App\Entity\TopicContentModule;
use App\Repository\TopicContentModuleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'choice_label' => 'title',
            ])
            ->add('topicContent')
            ->add('topicText',TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumTopic::class,
        ]);
    }

}
