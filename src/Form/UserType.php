<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\RolesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => RolesEnum::admin,
                    'Manager' => RolesEnum::manager,
                ],
                'expanded' => true, 
                'multiple' => false, 
                'label' => 'Rôle',
                'attr' => [
                    'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none space-y-2'
                ],
            ])
            ->add('password')
            ->add('firstname')
            ->add('lastname')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
