<?php

namespace Pms\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', 'entity', array(
                'class' => 'PmsCoreBundle:Project',
                'property' => 'projectName',
                'required' => false,
                'empty_value' => 'Select Project',
                'empty_data' => null,
            ))
            ->add('item', 'entity', array(
                'class' => 'PmsCoreBundle:Item',
                'property' => 'itemName',
                'required' => false,
                'empty_value' => 'Select Item',
                'empty_data' => null
            ))
            ->add('search', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'search';
    }
}
