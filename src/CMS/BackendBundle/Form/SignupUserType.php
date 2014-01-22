<?php

namespace CMS\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignupUserType extends AbstractType
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
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options'  => array(
                    'label' => 'Пароль',
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ),
                'second_options' => array(
                    'label' => 'Повторите пароль',
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ),
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
        return 'cms_backendbundle_signupuser';
    }
}
