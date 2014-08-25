<?php

namespace Pms\PrBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeliveryReceiveType extends AbstractType
{
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pms\PrBundle\Entity\DeliveryReceive'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pms_prbundle_deliveryreceive';
    }
}
