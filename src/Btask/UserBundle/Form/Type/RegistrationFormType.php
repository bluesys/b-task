<?php

namespace Btask\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType  extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);
        // Do not allow a user to enter a username (the username will be the email)
        $builder->remove('username');
    }

    public function getName()
    {
        return 'btask_user_registration';
    }
}
