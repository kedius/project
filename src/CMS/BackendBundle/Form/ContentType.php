<?php

namespace CMS\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'Заголовок',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('category', 'entity', array(
                'label' => 'Категория',
                'class' => 'CMSBackendBundle:Categories',
                'property' => 'indentedTitle',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.root', 'ASC')
                        ->addOrderBy('c.lft', 'ASC');
                },
                'attr' => array(
                    'required' => 'required',
                    'class' => 'form-control'
                )
            ))
            ->add('text', 'textarea', array(
                'label' => 'Текст/Описание (140+ символов)',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('files', 'collection', array(
                'label' => 'Файлы',
                'type' => new FilesType(),
                'allow_add'    => true,
                'allow_delete' => true,
            ))
            ->add('tags', 'entity', array(
                'label' => 'Тэги',
                'class' => 'CMSBackendBundle:Tags',
                'property' => 'name',
                'multiple' => true
            ));
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
