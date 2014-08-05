<?php

namespace Pms\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubCategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subcategory', 'entity', array(
                'class' => 'PmsCoreBundle:Category',
                'property' => 'categoryName',
                'required' => false,
                'empty_value' => 'Select Category',
                'empty_data' => null,
                'query_builder' => function (\Pms\UserBundle\Entity\UserRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->where('s.parent = 0');
                    }
            ))
//            ->add('add', 'submit')
        ;
        $builder
            ->add('category', new CategoryType(), array(
                'label_attr' => array(
                    'class' => 'hidden'
                )
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'subcategory';
    }
}