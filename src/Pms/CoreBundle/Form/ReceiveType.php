<?php

namespace Pms\CoreBundle\Form;

use Pms\CoreBundle\Entity\Repository\BuyerRepository;
use Pms\CoreBundle\Entity\Repository\VendorRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReceiveType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('receiveItems', 'collection', array(
                'type' => new ReceivedItemType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => true,
                'label_attr' => array(
                    'class' => 'hidden'
                )
            ))
            ->add('buyer', 'entity', array(
                'class' => 'PmsCoreBundle:Buyer',
                'property' => 'buyerName',
                'required' => false,
                'attr' => array(
                    'placeholder' => ' Select Buyer'
                ),
                'empty_data' => null,
                'query_builder' => function (BuyerRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->where('s.status = 1');
                    }
            ))
            ->add('vendor', 'entity', array(
                'class' => 'PmsCoreBundle:Vendor',
                'property' => 'vendorName',
                'required' => false,
                'attr' => array(
                    'placeholder' => ' Select Vendor'
                ),
                'empty_data' => null,
                'query_builder' => function (VendorRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->where('s.status = 1');
                    }
            ))
            ->add('grn', 'text', array(
                'attr' => array(
                    'placeholder' => 'GRN#',
                    'autocomplete' => 'off'
                )
            ))
            ->add('invoice', 'entity', array(
                'class' => 'PmsCoreBundle:Invoice',
                'property' => 'title',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Select Invoice'
                ),
                'empty_data' => null
            ))
            ->add('calan', 'entity', array(
                'class' => 'PmsCoreBundle:Invoice',
                'property' => 'title',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Select Invoice'
                ),
                'empty_data' => null
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
            'data_class' => 'Pms\CoreBundle\Entity\Receive'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'receive';
    }
}
