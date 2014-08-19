<?php

namespace Pms\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProjectCostType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateOfCost', 'text', array(
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
                'query_builder' => function (\Pms\UserBundle\Entity\UserRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->where('s.status = 1');
                    }
            ))
            ->add('item', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:Item',
                'property' => 'itemName',
                'required' => false,
                'attr' => array(
                    'placeholder' => ' Select Item'
                ),
                'empty_data' => null,
                'query_builder' => function (\Pms\UserBundle\Entity\UserRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->where('s.status = 1');
                    }
            ))
            ->add('customer', 'entity', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'class' => 'PmsCoreBundle:Customer',
                'property' => 'customerName',
                'required' => false,
                'attr' => array(
                    'placeholder' => ' Select Customer'
                ),
                'empty_data' => null,
                'query_builder' => function (\Pms\UserBundle\Entity\UserRepository $repository)
                {
                    return $repository->createQueryBuilder('s')
                        ->where('s.status = 1');
                }
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
            ->add('unitPrice', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Unit Price',
                    'autocomplete' => 'off'
                )
            ))
            ->add('lineTotal', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Total'
                ),
                'read_only' => true
            ))
            ->add('invoice', 'text', array(
                'attr' => array(
                    'placeholder' => 'Invoice',
                    'autocomplete' => 'off'
                )
            ))
            ->add('grn', 'text', array(
                'attr' => array(
                    'placeholder' => 'GRN#',
                    'autocomplete' => 'off'
                )
            ))
            ->add('pr', 'text', array(
                'attr' => array(
                    'placeholder' => 'PR#',
                    'autocomplete' => 'off'
                )
            ))
            ->add('po', 'text', array(
                'attr' => array(
                    'placeholder' => 'PO#',
                    'autocomplete' => 'off'
                )
            ))
            ->add('category', 'entity', array(
                'class' => 'PmsCoreBundle:Category',
                'property' => 'categoryName',
                'required' => false,
                'empty_value' => 'Select Category',
                'empty_data' => null,
                'query_builder' => function (\Pms\UserBundle\Entity\UserRepository $repository)
                {
                    return $repository->createQueryBuilder('s')
                        ->where('s.parent = 0')
                        ->andWhere('s.status = 1');
                }
            ))
            ->add('subCategory', 'entity', array(
                'class' => 'PmsCoreBundle:Category',
                'property' => 'categoryName',
                'required' => false,
                'empty_value' => 'Select Sub-category',
                'empty_data' => null,
                'query_builder' => function (\Pms\UserBundle\Entity\UserRepository $repository)
                {
                    return $repository->createQueryBuilder('s')
                        ->where('s.parent > 0')
                        ->andWhere('s.status = 1');
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
            'data_class' => 'Pms\CoreBundle\Entity\ProjectCost'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'projectcost';
    }
}
