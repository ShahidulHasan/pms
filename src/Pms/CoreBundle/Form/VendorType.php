<?php

namespace Pms\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class VendorType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vendorName', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Add vendor name',
                    'autocomplete' => 'off'
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
            'data_class' => 'Pms\CoreBundle\Entity\Vendor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vendor';
    }
}
