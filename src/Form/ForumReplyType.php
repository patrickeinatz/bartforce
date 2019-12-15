<?php

namespace App\Form;

use App\Entity\ForumReply;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumReplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('replyContent', TextareaType::class,
                [
                    'attr' => [
                        'cols' => '5',
                        'rows' => '5',
                        'maxlength' => 255
                    ],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumReply::class,
        ]);
    }
}
