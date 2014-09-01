<?php

namespace Pms\CoreBundle\Form;

use Pms\CoreBundle\Entity\Repository\ItemRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Pms\CoreBundle\Entity\Repository\PurchaseRequisitionItemRepository;


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
            ->add('item', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:PurchaseRequisitionItem',
                'property' => 'item.itemName',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Select Item'
                ),
                'empty_data' => null,
                'query_builder' => function (PurchaseRequisitionItemRepository $repository)
                    {
                        return $repository->createQueryBuilder("s")
                                          ->select("s","p")
                                          ->leftJoin("s.item", "p");
                    }
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
