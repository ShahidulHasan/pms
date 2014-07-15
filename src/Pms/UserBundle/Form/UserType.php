<?php

namespace Pms\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class UserType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Username'
                ),
                'label_attr' => array(
                    'class' => ''
                )
            ))
            ->add('email', null, array(
                'constraints' => array(
                    new NotBlank(),
                    new Email()
                ),
                'attr' => array(
                    'placeholder' => 'E-mail'
                ),
                'label_attr' => array(
                    'class' => ''
                )
            ))
            ->add('passwords', 'repeated', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'type' => 'password',
                'first_name' => 'Password',
                'second_name' => 'Confirm_Password'
            ))
            ->add('role', 'choice', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'mapped' => true,
                'choices' => array(
                    'ROLE_SUPER_ADMIN' => 'Super Admin',
                    'ROLE_MANAGER' => 'Manager'
                ),
                'required' => false,
                'empty_value' => 'Select User Group',
                'empty_data' => null
            ))
            ->add('country', 'entity', array(
                'class' => 'UserBundle:Country',
                'property' => 'name',
                'required' => false,
                'empty_value' => 'Select Your Country',
                'empty_data' => null
            ))
            ->add('firstName', null, array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'First Name'
                ),
                'label_attr' => array(
                    'class' => ''
                )
            ))
            ->add('lastName', null, array(
                'attr' => array(
                    'placeholder' => 'Last Name'
                ),
                'label_attr' => array(
                    'class' => ''
                )
            ))
            ->add('sex', 'checkbox')
            ->add('designation', null, array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Designation'
                ),
                'label_attr' => array(
                    'class' => ''
                )
            ))
            ->add('phoneNumber', null, array(
                'constraints' => array(
                    new NotBlank(),
                    new Regex(array(
                        'pattern' => '/\d/',
                        'match' => true,
                        'message' => 'Phone number should be number',
                    ))
                ),
                'attr' => array(
                    'placeholder' => 'Phone Number'
                ),
                'label_attr' => array(
                    'class' => ''
                )
            ))
            ->add('address', 'textarea', array(
                'attr' => array(
                    'placeholder' => 'Address'
                ),
                'label_attr' => array(
                    'class' => ''
                )
            ))
            ->add('createdBy', 'hidden')
            ->add('createdDate', 'hidden')
            ->add('status', 'hidden')
            ->add('enabled', 'hidden')
            ->add('save', 'submit')
            ->add('cancel', 'button');
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
        return 'pms_userbundle_user';

    }

}