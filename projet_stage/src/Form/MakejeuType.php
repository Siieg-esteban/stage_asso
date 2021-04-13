<?php

namespace App\Form;

use App\Entity\Jeu;
use App\Entity\Imagejeuproto;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;

class MakejeuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('contenue', CKEditorType::class, [
                'required'=>true,
            ])
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'alpha' => 'alpha',
                    'bêta' => 'bêta',
                    'release' => 'release',
                ],
            ])
            ->add('image', FileType::class,  [
                'mapped'=>false,
                'required'=>false,
                'multiple'=>true,
                'constraints'=>[
                    new All([
                        new File([
                            'maxSize'=>'1024k',
                            'mimeTypes'=> [
                                'image/png'
                            ],
                            'mimeTypesMessage'=> 'pas le bon mime type !'
                        ])
                    ])
                ]
            ])
            ->add('mainimage', FileType::class,  [
                'mapped'=>false,
                'required'=>true,
                'constraints'=>[
                    new File([
                        'maxSize'=>'1024k',
                        'mimeTypes'=> [
                            'image/png'
                        ],
                        'mimeTypesMessage'=> 'pas le bon mime type !'
                    ])
                ]
            ])
            ->add('fileWeb', FileType::class, [
                'label' => 'fileWeb',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    // new All([
                        new File([
                            'maxSize'=>'4000000k',
                            'mimeTypes'=> [
                                'application/zip',
                            ],
                            'mimeTypesMessage'=> 'pas le bon mime type !'
                        ])
                    // ])
                ],
            ])
            ->add('fileDl', FileType::class, [
                'label' => 'fileDl',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    // new All([
                        new File([
                            'maxSize'=>'4000000k',
                            'mimeTypes'=> [
                                'application/zip',
                            ],
                            'mimeTypesMessage'=> 'pas le bon mime type !'
                        ])
                    // ])
                ],
            ])
            ->add('nomdossier', TextType::class,['required' => false,])
            ->add('longueur', NumberType::class,['required' => false,])
            ->add('largeur', NumberType::class,['required' => false,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Jeu::class,
        ]);
    }
}

