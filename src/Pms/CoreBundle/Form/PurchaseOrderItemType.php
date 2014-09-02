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
            ->add('item', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:Item',
                'property' => 'itemName',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Select Item'
                ),
                'empty_data' => null,
                'query_builder' => function (EntityRepository $repository)
                    {
                        return $repository->createQueryBuilder('i')
                                          ->select('i')
                                          ->where('pri.status = 1')
                                          ->join('i.purchaseRequisitionItem', 'pri')
                                          ->groupBy('i.id');
                    },
            ))
            ->add('quantity', 'text')
            ->add('delete', 'button')
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
