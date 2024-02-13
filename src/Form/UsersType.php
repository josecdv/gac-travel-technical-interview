<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            // , TextType::class, [
            //     'constraints' => [
            //         new Assert\NotBlank(),
            //     ],
            // ])
            ->add('password');
            // , TextType::class, [
            //     'constraints' => [
            //         new Assert\NotBlank(),
            //     ],
            // ]);
            // ->add('active')
            // ->add('created_at')
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'ROLE_ADMIN' => 'ROLE_ADMIN',
            //     ],
            //     'multiple' => true,
            //     'expanded' => true,
            //]);
                
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
