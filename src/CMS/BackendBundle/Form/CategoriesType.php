<?php

namespace CMS\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array (
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('parent', 'entity', array(
                'class' => 'CMSBackendBundle:Categories',
                'property' => 'indentedTitle',
                'empty_value' => 'Выберите категорию',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
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
            'data_class' => 'CMS\BackendBundle\Entity\Categories'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_backendbundle_categories';
    }
}
