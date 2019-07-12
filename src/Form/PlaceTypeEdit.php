<?php

namespace App\Form;

use App\Entity\Place;
use App\Helper\PlaceStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceTypeEdit extends PlaceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('status', ChoiceType::class, array(
                'label' => 'Статус',
                'choices' => [
                    'НОВЫЙ' => PlaceStatus::NEW,
                    'ПОДТВЕЖДЕН' => PlaceStatus::CONFIRMED,
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
