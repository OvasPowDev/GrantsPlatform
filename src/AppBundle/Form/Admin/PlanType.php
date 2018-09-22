<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PlanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'app.name', 'translation_domain' => 'AppBundle'])
            ->add('description', null, ['label' => 'app.description', 'translation_domain' => 'AppBundle'])
            ->add('active', null, ['label' => 'app.enabled', 'translation_domain' => 'AppBundle'])
            ->add('quantityUser', null, ['label' => 'app.quantity_user', 'translation_domain' => 'AppBundle'])
            ->add('time', null, ['label' => 'app.time', 'translation_domain' => 'AppBundle'])
            ->add('quantityGrant', null, ['label' => 'app.quantity_grant', 'translation_domain' => 'AppBundle'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Plan'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
