<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Btask\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email', 'email');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btask\UserBundle\Entity\User',
        );
    }

    public function getName()
    {
        return 'btask_user_bundle_user';
    }
}
