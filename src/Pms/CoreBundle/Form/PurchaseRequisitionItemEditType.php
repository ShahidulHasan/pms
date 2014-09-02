<?php

namespace Pms\CoreBundle\Form;

use Pms\CoreBundle\Entity\Repository\ItemRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseRequisitionItemEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', 'text')
            ->add('comment', 'textarea')
            ->add('dateOfRequired', 'date', array(
                'input'  => 'datetime',
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Date'
                ),
                'data_class' => null,
                'read_only' => true
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
                'query_builder' => function (ItemRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->where('s.status = 1');
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
            'data_class' => 'Pms\CoreBundle\Entity\PurchaseRequisitionItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'purchaserequisitionitem';
    }
}