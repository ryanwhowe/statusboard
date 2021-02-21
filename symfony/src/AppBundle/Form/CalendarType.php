<?php

namespace AppBundle\Form;

use AppBundle\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                ['choices' => [
                    'Company Holiday' => Calendar::TYPE_COMPANY_HOLIDAY,
                    'PTO' => Calendar::TYPE_PTO,
                    'Sick' => Calendar::TYPE_SICK,
                    'Pay Date' => Calendar::TYPE_PAY_DATE,
                    'National Holiday' => Calendar::TYPE_NATIONAL_HOLIDAY
                    ]
                ]
            )
            ->add('eventDate');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Calendar'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_calendar';
    }


}
