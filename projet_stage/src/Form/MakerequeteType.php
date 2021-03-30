<?php

namespace App\Form;

use App\Entity\RequeteContributeur;

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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;

class MakerequeteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('demande', CKEditorType::class, [
                'required'=>true,
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
            ->add('upload', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'required' => false,
                'multiple'=>true,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize'=>'10000k',
                            'mimeTypes'=> [
                                'audio/*',
                                'text/*',
                                'video/*',
                                'application/*',
                            ],
                            'mimeTypesMessage'=> 'pas le bon mime type !'
                        ])
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RequeteContributeur::class,
        ]);
    }
}
