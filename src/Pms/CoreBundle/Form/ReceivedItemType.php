<?php

namespace Pms\CoreBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReceivedItemType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grn', 'text', array(
                'attr' => array(
                    'placeholder' => 'GRN#',
                    'autocomplete' => 'off'
                )
            ))
            ->add('quantity', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Quantity',
                    'autocomplete' => 'off'
                )
            ))
            ->add('purchaseRequisitionItem', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:PurchaseRequisitionItem',
                'property' => 'purchaseRequisition.requisitionNo',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Select Item'
                ),
                'empty_data' => null,
                'query_builder' => function (EntityRepository $repository)
                    {
                        return $repository->createQueryBuilder('i')
                            ->where('i.status = 1');
                    },
            ))
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
                            ->join('i.purchaseRequisitionItem', 'pri');
                    },
            ))
            ->add('invoice', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:Invoice',
                'property' => 'title',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Select Invoice'
                ),
                'empty_data' => null,
                'query_builder' => function (EntityRepository $repository)
                    {
                        return $repository->createQueryBuilder('i')
                            ->select('i')
                            ->where('pri.status = 1')
                            ->join('i.purchaseRequisition', 'pri')
                            ->groupBy('i.id');
                    },
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
            'data_class' => 'Pms\CoreBundle\Entity\ReceivedItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'receiveditem';
    }
}
