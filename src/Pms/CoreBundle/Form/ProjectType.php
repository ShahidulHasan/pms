<?php

namespace Pms\CoreBundle\Form;

use Pms\UserBundle\Entity\Repository\UserRepository;
use Pms\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProjectType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectName', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Add project name',
                    'autocomplete' => 'off'
                )
            ))
            ->add('address', 'textarea')
            ->add('projectHead', 'entity', array(
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pms\CoreBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'project';
    }
}
