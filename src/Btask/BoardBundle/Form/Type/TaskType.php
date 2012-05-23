<?php

namespace Btask\BoardBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('detail');
        $builder->add('due');
        $builder->add('planned');
        $builder->add('status');
        $builder->add('priority');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btask\BoardBundle\Entity\Item',
        );
    }

    public function getName()
    {
        return 'btask_board_bundle_postit';
    }
}
