<?php

namespace AppBundle\Form\Admin;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ContractType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', null, ['label' => 'app.description', 'translation_domain' => 'AppBundle'])
            ->add('company', null, ['label' => 'app.company', 'translation_domain' => 'AppBundle',
                'query_builder' => function (EntityRepository $er){
                return $er->createQueryBuilder('c')
                    ->where('c.enabled = true');
            }])
            ->add('plan', null, ['label' => 'app.administrator.plan', 'translation_domain' => 'AppBundle', 'query_builder' => function (EntityRepository $er){
                return $er->createQueryBuilder('p')
                    ->where('p.active = true');
            }])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contract'
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
