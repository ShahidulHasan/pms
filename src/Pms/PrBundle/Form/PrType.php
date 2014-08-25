<?php

namespace Pms\PrBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrType extends AbstractType
{
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pms\PrBundle\Entity\Pr'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pms_prbundle_pr';
    }
}
