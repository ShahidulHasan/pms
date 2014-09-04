<?php

namespace Pms\CoreBundle\Form;

use Pms\CoreBundle\Entity\Repository\ProjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseRequisitionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('requisitionNo', 'text')
            ->add($builder->create('dateOfRequisition', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Date'
                ),
                'data_class' => null,
                'read_only' => true
            ))->addViewTransformer(new DateTimeToStringTransformer(null, null, 'Y-m-d')))
            ->add('project', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:Project',
                'property' => 'projectName',
                'required' => false,
                'empty_value' => 'Select Project',
                'empty_data' => null,
                'query_builder' => function (ProjectRepository $repository)
                {
                    return $repository->createQueryBuilder('s')
                        ->where('s.status = 1');
                }
            ))
            ->add('purchaseRequisitionItems', 'collection', array(
                'type' => new PurchaseRequisitionItemType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => true,
                'label_attr' => array(
                    'class' => 'hidden'
                )
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
            'data_class' => 'Pms\CoreBundle\Entity\PurchaseRequisition'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'purchaserequisition';
    }
}
