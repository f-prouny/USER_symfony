<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class)
            // ->add('roles)
            ->add('roles')
            ->add('password',PasswordType::class)
            ->add('confirmPassword',PasswordType::class)
            ->add('nom')
            ->add('prenom')
            ->add('birthdate', DateType::class, ['widget'=> 'single_text'])
            ->add('avatar', FileType::class, [
                'label'=> "photo de profil (JPG,PNG)",
                'data_class' =>null,
                'required'=> false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
