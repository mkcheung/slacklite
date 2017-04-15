<?php

namespace AppBundle\Forms\Users;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $availableRoles = [] ;
        $roles = $options['data'];

        foreach ($roles as $role) {
            $availableRoles[$role->getId()] = $role->getType();
        }

        $builder
            ->add('username', TextType::class, [])
            ->add('password', PasswordType::class, [])
            ->add('role', ChoiceType::class, [
                'choices'  => $availableRoles
            ])
            ->add('Submit', SubmitType::class, array(
                'attr' => array('label' => 'Submit'),
            ));
    }

    public function getName()
    {
        return null;
    }

}