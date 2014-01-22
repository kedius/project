<?php

namespace CMS\BackendBundle\Form;

use CMS\BackendBundle\Helper\KeeperDataTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentReviewType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', 'entity', array(
                'label' => 'Статус',
                'class' => 'CMSBackendBundle:Statuses',
                'property' => 'status',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.id <> :statusID')
                        ->setParameter(':statusID', KeeperDataTable::STATUS_NOT_PUBLISHED);
                },
                'empty_value' => 'Выберите статус',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('tags', 'entity', array(
                'label' => 'Тэги',
                'class' => 'CMSBackendBundle:Tags',
                'property' => 'name',
                'multiple' => true
            ))
            ->add('review', 'textarea', array(
                'label' => 'Рецензия',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\BackendBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_backendbundle_content';
    }
}
