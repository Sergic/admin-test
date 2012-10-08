<?php

namespace Instudies\AdminBundle\Form\Management\User\Edit;

use
    Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder,
    Symfony\Component\Form\CallbackValidator
;

class ManagementUserEditType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {

        $builder
            ->add('firstname', 'text', array('label' => 'Имя', 'required' => false))
            ->add('lastname', 'text', array('label' => 'Фамилия', 'required' => false))
            ->add('email', 'email', array('label' => 'Email', 'required' => false))
            ->add('emailActivated', 'checkbox', array('label' => 'Email активирован', 'required' => false))
            ->add('filledAllInformation', 'checkbox', array('label' => 'Информация заполнена', 'required' => false))
            ->add('plainPassword', null, array('label' => 'Пароль', 'required' => false))
        ;

    }

    public function getName()
    {
        return 'management_user_edittype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Instudies\SiteBundle\Entity\User',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'  => 'edit_user',
        );
    }
}