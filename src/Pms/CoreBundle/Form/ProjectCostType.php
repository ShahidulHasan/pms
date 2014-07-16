<?php

namespace Pms\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProjectCostType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateOfCost', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'read_only' => true
            ))
            ->add('project', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:Project',
                'property' => 'projectName',
                'required' => false,
                'empty_value' => 'Select Project',
                'empty_data' => null
            ))
            ->add('item', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:Item',
                'property' => 'itemName',
                'required' => false,
                'empty_value' => 'Select Item',
                'empty_data' => null
            ))
            ->add('quantity', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => ''
                )
            ))
            ->add('unitPrice', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => ''
                )
            ))
            ->add('lineTotal', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => ''
                ),
                'read_only' => true
            ))
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pms\CoreBundle\Entity\ProjectCost'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'projectcost';
    }
}
