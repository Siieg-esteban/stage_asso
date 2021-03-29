<?php

namespace App\Form;

use App\Entity\Com;

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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;

class MakecomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenue', CKEditorType::class, [
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
                            'maxSize'=>'1024k',
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
            // ->add('type', HiddenType::class, [
            //     'data' => 'test',
            // ])
            // ->add('protoid', HiddenType::class, [
            //     'mapped'=>false,
            //     'data' => '1',
            // ])
            // ->add('datetime')
            // ->add('envoyer')
            // ->add('jeu')
            // ->add('blog')
            // ->add('proto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Com::class,
        ]);
    }
}
