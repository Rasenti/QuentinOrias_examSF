<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length as ConstraintsLength;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    private const CONTRACT_TYPES = [
        "CDI" => "CDI",
        "CDD" => "CDD",
        "Intérim" => "Intérim"
    ];
    private const SECTORS = [
        "RH" => "RH",
        "Informatique" => "Informatique",
        "Comptabilité" => "Comptabilité",
        "Direction" => "Direction"
    ];
    private const ROLES = [
        "Utilisateur" => "ROLE_USER",
        "Administrateur" => "ROLE_ADMIN",
    ];
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, ['choices'  => self::ROLES])
            ->add('plainPassword', TextType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new ConstraintsLength([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('picture', FileType::class, [
                'label' => 'Photos au format jpg, jpeg, png, webp, svg',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/svg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez fournir une image au bon format (jpg, jpeg, png, webp, svg)',
                    ])
                ],
            ])
            ->add('sector', ChoiceType::class, ['choices'  => self::SECTORS])
            ->add('contract', ChoiceType::class, ['choices'  => self::CONTRACT_TYPES])
            ->add('dateOfEnd', DateType::class)
        ;
        
        // The User Entity is expecting an array of Roles, so we have to transform the input 
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function (array $rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function (string $rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
