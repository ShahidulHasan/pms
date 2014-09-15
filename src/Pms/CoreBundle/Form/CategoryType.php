<?php

namespace Pms\CoreBundle\Form;

use Pms\UserBundle\Entity\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoryName', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'label_attr' => array(
                    'class' => 'hidden'
                ),
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('head', 'entity', array(
                'class' => 'UserBundle:User',
                'property' => 'username',
                'required' => false,
                'empty_value' => 'Select Head',
                'empty_data' => null,
                'query_builder' => function (UserRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->where('s.enabled = 1');
                    }
            ))
            ->add('subHead', 'entity', array(
                'class' => 'UserBundle:User',
                'property' => 'username',
                'required' => false,
                'empty_value' => 'Select Sub Head',
                'empty_data' => null,
                'query_builder' => function (UserRepository $repository)
                    {
                        return $repository->createQueryBuilder('s')
                            ->where('s.enabled = 1');
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
            'data_class' => 'Pms\CoreBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'category';
    }
}
