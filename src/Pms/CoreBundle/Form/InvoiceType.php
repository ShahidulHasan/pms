<?php

namespace Pms\CoreBundle\Form;

use Pms\CoreBundle\Entity\PurchaseRequisition;
use Pms\CoreBundle\Entity\Repository\PurchaseRequisitionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvoiceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', 'textarea', array(
                'attr' => array(
                    'placeholder' => 'Remark'
                )
            ))
            ->add('invoiceNumber', 'text', array(
                'attr' => array(
                    'placeholder' => 'Invoice',
                    'autocomplete' => 'off'
                )
            ))
            ->add('file', 'file')
            ->add('fileCalan', 'file')
            ->add('save', 'submit')
            ->add('purchaseRequisition', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:PurchaseRequisition',
                'property' => 'requisitionNo',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Select Requisition'
                ),
                'empty_data' => null,
                'query_builder' => function (PurchaseRequisitionRepository $repository)
                    {
                        return $repository->createQueryBuilder('pr')
                            ->where('pr.status = 1');
                    }
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pms\CoreBundle\Entity\Invoice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invoice';
    }
}
