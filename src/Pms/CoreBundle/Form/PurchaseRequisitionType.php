<?php

namespace Pms\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add('dateOfRequisition', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Date'
                ),
                'data_class' => null,
                'read_only' => true
            ))
            ->add('project', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:Project',
                'property' => 'projectName',
                'required' => false,
                'empty_value' => 'Select Project',
                'empty_data' => null,
                'query_builder' => function (\Pms\CoreBundle\Entity\Repository\ProjectRepository $repository)
                {
                    return $repository->createQueryBuilder('s')
                        ->where('s.status = 1');
                }
            ))
            /*->add('purchaseRequisitionItem', new PurchaseRequisitionItemType(), array(
                'label_attr' => array(
                    'class' => 'hidden'
                )
            ))*/
            ->add('purchaseRequisitionItem', 'collection', array(
                'type' => new PurchaseRequisitionItemType()
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
