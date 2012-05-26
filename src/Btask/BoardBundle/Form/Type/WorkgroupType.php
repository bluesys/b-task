<?php

namespace Btask\BoardBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class WorkgroupType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btask\BoardBundle\Entity\Workgroup',
        );
    }

    public function getName()
    {
        return 'btask_board_bundle_workgroup';
    }
}
