<?php

namespace Pms\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PurchaseOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderNo')
            ->add('claimedBy')
            ->add('poNonpo')
            ->add('vendor')
            ->add('dateOfClosing')
            ->add('dateOfDelivered')
            ->add('project')
            ->add('buyer')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pms\CoreBundle\Entity\PurchaseOrder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'purchaseorder';
    }
}
