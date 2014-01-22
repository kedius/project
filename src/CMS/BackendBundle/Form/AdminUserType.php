<?php

namespace CMS\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Логин',
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('image', 'text', array(
                'label' => 'Картинка',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('roles', 'entity', array(
                'label' => 'Роли',
                'class' => 'CMSBackendBundle:UserRoles',
                'property'=>'role',
                'multiple'  => true,
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
            'data_class' => 'CMS\BackendBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_backendbundle_user';
    }
}
