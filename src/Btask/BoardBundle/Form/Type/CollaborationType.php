<?php

namespace Btask\BoardBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;

use Btask\BoardBundle\Form\Type\ProjectType;
use Btask\UserBundle\Entity\User;

class CollaborationType extends AbstractType
{
    protected $user;

    public function __construct (User $user)
    {
        // Get the logged user
        $this->user = $user;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $user = $this->user;

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
            'expanded' => true,
            'query_builder' => function(EntityRepository $er) use ($user) {
                return $er->createQueryBuilder('w')
                    ->where("w.owner = :user")
                    ->setParameter('user', $user);
        },
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
