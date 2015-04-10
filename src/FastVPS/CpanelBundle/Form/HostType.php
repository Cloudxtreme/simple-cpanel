<?php

namespace FastVPS\CpanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NewHostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hostname', 'text');
    }

    public function getName()
    {
        return 'newHost';
    }
}
