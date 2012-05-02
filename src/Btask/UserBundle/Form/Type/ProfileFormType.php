<?php

namespace Btask\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    protected function buildUserForm(FormBuilder $builder, array $options)
    {
        parent::buildUserForm($builder, $options);
        // Do not allow users to modify their username
        $builder->remove('username');
    }

    public function getName()
    {
        return 'btask_user_profile';
    }
}
