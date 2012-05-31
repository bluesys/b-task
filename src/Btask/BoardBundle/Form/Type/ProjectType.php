<?php

namespace Btask\BoardBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name');
        $builder->add('color');
        $builder->add('workgroups', 'entity', array(
            'class' => 'BtaskBoardBundle:Workgroup',
            'property' => 'name',
            'multiple' => true
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btask\BoardBundle\Entity\Project',
        );
    }

    public function getName()
    {
        return 'btask_board_bundle_project';
    }
}
