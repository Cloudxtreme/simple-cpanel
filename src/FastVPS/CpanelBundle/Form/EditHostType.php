<?php

namespace FastVPS\CpanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EditHostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hostname', 'text', array('label' => 'New host name'))
        ->add('save', 'submit', array('label' => 'Edit Host'));
    }

    public function getName()
    {
        return 'editHost';
    }
}
