<?php

namespace Pms\CoreBundle\Form;

use Pms\CoreBundle\Entity\Repository\BuyerRepository;
use Pms\CoreBundle\Entity\Repository\VendorRepository;
use Pms\CoreBundle\Entity\Vendor;
use Pms\UserBundle\Entity\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderNo', 'text')
            ->add('poNonpo', 'choice', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'choices' => array(
                    '1' => 'PO',
                    '0' => 'Non-PO'
                )
            ))
            ->add($builder->create('dateOfDelivered', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Date'
                ),
                'data_class' => null,
                'read_only' => true
            ))->addViewTransformer(new DateTimeToStringTransformer(null, null, 'Y-m-d')))
            ->add('purchaseOrderItems', 'collection', array(
                'type' => new PurchaseOrderItemType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => true,
                'label_attr' => array(
                    'class' => 'hidden'
                )
            ))
            ->add('buyer', 'entity', array(
                'class' => 'UserBundle:User',
                'property' => 'username',
                'required' => false,
                'attr' => array(
                    'placeholder' => ' Select Buyer'
                ),
                'empty_data' => null,
                'query_builder' => function (UserRepository $repository)
                    {
                        return $repository->createQueryBuilder('u')
                            ->where('u.enabled = 1');
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
            ->add('save', 'submit');
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
