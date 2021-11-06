<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class AjoutVoiture extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('modele', TextType::class, [
                'label' => 'Nom du modèle'
            ])
            ->add('carac', TextType::class, [
                'label' => 'Caractéristiques',
            ])
            ->add('quantite', TextType::class, [
                'label' => 'Quantité'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => 'false',
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ]
                    ])
                ]
            ])
            ->add('prix', TextType::class, [
                'label' => 'Prix journalier'
            ]);

        $builder->get('carac')->addModelTransformer(new CallbackTransformer(
            function ($arrayToString){
                return implode(',', $arrayToString);
            },
            function ($stringToArray){
                $tmp = explode(',', $stringToArray);
                if (count($tmp) != 6) return [];
                return array(
                    'marque' => $tmp[0],
                    'boite' => $tmp[1],
                    'couleur' => $tmp[2],
                    'moteur' => $tmp[3],
                    'nbPlaces' => $tmp[4],
                    'nbPortes' => $tmp[5],
                );
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
