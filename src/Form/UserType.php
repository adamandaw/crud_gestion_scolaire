<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'required'  => false,
                'constraints'=>[
                            new NotBlank(['message'=>'email est obligatoire'])
                ],
                "attr"=>[
                    "placeholder"=>"ex : toto@gmail.com"
                ]
            ])
            
            // ->add('password')
            ->add('nomComplet', TextType::class, [
                'label' => 'Nom & Prénom',
                'required'  => false,
                'attr' => [
                    'placeholder' => 'ex : toto',   
                    "class"=>""
                ]
                ,'constraints'=>[
                    new NotBlank(['message'=>'nom est obligatoire'])
                ],
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints'=>[
                new UniqueEntity([
                    'fields' => ['email'],
                    "message"=>"Ce champ doit être unique."
                ]),
                // new NotBlank([
                //     'groups' => ['email','nomComplet'],
                //     'message'=>'Ce champ est obligatoire.'
                // ])
            ]
        ]);
    }
}
