<?php

namespace Btask\BoardBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Btask\BoardBundle\Form\Type\ProjectType;

class CollaborationType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('project', new ProjectType());
        $builder->add('participant', 'entity', array(
            'class' => 'BtaskUserBundle:User',
            'property' => 'email',
            'multiple' => false,
            'expanded' => true
        ));
        $builder->add('workgroup', 'entity', array(
            'class' => 'BtaskBoardBundle:Workgroup',
            'property' => 'name',
            'multiple' => false,
            'expanded' => true
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btask\BoardBundle\Entity\Collaboration',
        );
    }

    public function getName()
    {
        return 'btask_board_bundle_collaboration';
    }
}
