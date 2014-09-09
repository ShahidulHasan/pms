<?php

namespace Pms\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class UserType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('role', 'choice', array(
                'mapped' => true,
                'choices' => array(
                    'ROLE_DATA' => 'USER',
                    'ROLE_BUYER' => 'BUYER',
                    'ROLE_ADMIN' => 'MANAGER',
                    'ROLE_SUPER_ADMIN' => 'SUPER ADMIN'
                ),
                'required' => false,
                'empty_value' => 'Select Role',
                'empty_data' => null
            ))
            ->add('save', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pms\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }
}