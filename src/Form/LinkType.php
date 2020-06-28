<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Domain\Entities\Link\Dto\LinkCreateDto;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('originalUrl', UrlType::class)
            ->add('customShortenedPart', TextType::class, [
                'required' => false,
            ])
            ->add('activeTill', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('isCommercial', CheckboxType::class, [
                'required' => false,
            ])
            ->add('Shorten', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LinkCreateDto::class,
        ]);
    }
}
