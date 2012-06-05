<?php

namespace Btask\BoardBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Btask\UserBundle\Form\Type\UserFormType;

class PostItType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('subject');
        $builder->add('detail');
        $builder->add('due', 'date', array(
            'widget' => 'single_text',
            'format' => 'd-m-Y',
            'required' => false,
        ));
        $builder->add('planned', 'date', array(
            'widget' => 'single_text',
            'format' => 'd-m-Y',
        ));
        $builder->add('priority');
        $builder->add('executor', 'entity', array(
            'class' => 'BtaskUserBundle:User',
            'property' => 'email',
        ));
        $builder->add('executor', 'collection', array(
            'type' => new UserFormType(),
            'allow_add' => true,
            'by_reference' => false,
        ));
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
