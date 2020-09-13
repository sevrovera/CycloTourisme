<?php

namespace App\Form;

use App\Entity\Parcours;
use App\Entity\Region;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
// use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class ParcoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Intitulé',
                'required' => true,
            ])
            ->add('description', null, [
                'required' => false,
            ])
            ->add('duration', ChoiceType::class, [
                'placeholder' => 'Choisir une durée',
                'choices'  => [
                    'weekend' => 2,
                    '3 jours' => 3,
                    '4 jours' => 4,
                    '5 jours' => 5,
                    '1 semaine' => 7,
                    '10 jours' => 10,
                    '12 jours' => 12,
                    '2 semaines' => 14],
                'label' => 'Durée',
                'required' => true,
                ])
            ->add('cost', null, [
                'label' => 'Prix',
                'required' => true,
            ])
            ->add('difficulty', ChoiceType::class, [
                'placeholder' => 'Choisir un niveau de difficulté',
                'choices'  => [
                    'Très Facile' => 'Très Facile',
                    'Facile' => 'Facile',
                    'Moyenne' => 'Moyenne',
                    'Difficile' => 'Difficile',
                    'Expert' => 'Expert'],
                'label' => 'Difficulté',
                'required' => true,
                ])
            ->add('maxParticipants', ChoiceType::class, [
                'choices'  => [
                    '8' => 8,
                    '10' => 10,
                    '12' => 12,
                    '14' => 14],
                'required' => true,
                'label' => 'Nombre de places',
                ])
            ->add('coverPicture', FileType::class, [
                'label' => 'Photo',
                'required' => false,
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Ajoutez un fichier jpeg ou png valide'
                    ])
                ],
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('reg')
                        ->orderBy('reg.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Choisir une région',
                'label' => 'Région',
                'required' => true,
            ])
            ->add('Sauvegarder', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parcours::class,
        ]);
    }
}
