<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserUpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null,[
                "required" => false
            ])
            ->add('password', null, [
                "required" => false
            ])
            ->add('firstname', null, [
                "required" => false
            ])
            ->add('lastname', null, [
                "required" => false
            ])
            ->add('profile_image', null, [
                "required" => false
            ])
            ->add('save', SubmitType::class, [
                "label" => "change user info"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
