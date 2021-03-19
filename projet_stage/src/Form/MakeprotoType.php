<?php

namespace App\Form;

use App\Entity\Proto;
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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;

class MakeprotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('contenue', CKEditorType::class, [
                'required'=>true,
            ])
            // ->add('datetime', DateType::class)
            // ->add('auteur', TextType::class)
            ->add('image', FileType::class,  [
                'mapped'=>false,
                // 'data_class' => Imagejeuproto::class,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proto::class,
        ]);
    }
}
