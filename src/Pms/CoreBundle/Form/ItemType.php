<?php

namespace Pms\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('itemName', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Add item name',
                    'autocomplete' => 'off'
                )
            ))
            ->add('itemUnit', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Unit of measurement',
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
            'data_class' => 'Pms\CoreBundle\Entity\Item'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'item';
    }
}
