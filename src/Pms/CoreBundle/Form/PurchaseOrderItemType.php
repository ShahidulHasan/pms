<?php

namespace Pms\CoreBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Pms\CoreBundle\Entity\Repository\ItemRepository;

class PurchaseOrderItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', 'textarea')
            ->add('purchaseRequisitionItem', 'entity', array(
                "class" => "PmsCoreBundle:PurchaseRequisitionItem",
                'property'=>'item.itemName'
            ))
            ->add('purchaseRequisitionItem', 'entity', array(
                "class" => "PmsCoreBundle:PurchaseRequisitionItem",
                'property'=>'item.itemName'
            ))
            ->add('quantity', 'text')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pms\CoreBundle\Entity\PurchaseOrderItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'purchaseorderitem';
    }
}
