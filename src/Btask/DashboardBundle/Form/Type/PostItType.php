<?php

namespace Btask\DashboardBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class PostItType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('subject');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btask\DashboardBundle\Entity\Item',
        );
    }

    public function getName()
    {
        return 'btask_dashbord_bundle_postit';
    }
}