<?php

namespace Btask\BoardBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;

use Btask\BoardBundle\Form\DataTransformer\UserToEmailTransformer;
use Btask\UserBundle\Entity\User;

class PostItType extends AbstractType
{
    public function __construct (User $user)
    {
        // Get the logged user
        $this->user = $user;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $user = $this->user;

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
        $builder->add('executor', 'user_selector');
        $builder->add('project', 'entity', array(
            'class' => 'BtaskBoardBundle:Project',
            'property' => 'name',
            'multiple' => false,
            'expanded' => true,
            'required' => false,
            'query_builder' => function(EntityRepository $er) use ($user) {
                return $er->createQueryBuilder('p')
                    ->innerJoin('p.collaborations', 'pc')
                    ->andWhere('pc.participant = :participant')
                    ->setParameter('participant', $user);
            })
        );
        $builder->add('type', 'entity', array(
            'class' => 'BtaskBoardBundle:ItemType',
            'property' => 'name',
            'multiple' => false,
            'expanded' => true,
            'required' => false,
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
