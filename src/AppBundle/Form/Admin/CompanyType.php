<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CompanyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'app.name', 'translation_domain' => 'AppBundle'])
            ->add('photo', VichFileType::class, ['download_uri' => true, 'label' => 'app.logo', 'translation_domain' => 'AppBundle'])
            ->add('email', null, ['label' => 'app.email', 'translation_domain' => 'AppBundle'])
            ->add('enabled', null, ['label' => 'app.enabled', 'translation_domain' => 'AppBundle'])
//            ->add('expired', null, ['label' => 'app.expired', 'translation_domain' => 'AppBundle'])
//            ->add('expiresAt', DateTimeType::class, ['format' => 'yyyy-MM-dd', 'widget' => 'single_text', 'attr' => ['class' => 'datepicker'], 'label' => 'app.expired_at', 'translation_domain' => 'AppBundle'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Company'
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
