<?php

namespace FastVPS\CpanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('login', 'text');
        $builder->add('password', 'password');
    }

    public function getName()
    {
        return 'register';
    }
}
