<?php

namespace Instudies\AdminBundle\Form\Management\User\Find;

use
    Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder,
    Symfony\Component\Form\CallbackValidator
;

class ManagementUserFindType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {

        $builder
            ->add('id', 'text', array('required' => false, 'label' => 'Введите id пользователя'))
            ->add('email', 'email', array('required' => false, 'label' => 'Введите email'))
        ;

    }

    public function getName()
    {
        return 'management_user_findtype';
    }

}
