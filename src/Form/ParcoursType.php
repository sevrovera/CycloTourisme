<?php

namespace App\Form;

use App\Entity\Parcours;
use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\Persistence\ManagerRegistry;


class ParcoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('duration')
            ->add('description')
            ->add('cost')
            ->add('difficulty')
            ->add('maxParticipants')
            ->add('registeredParticipants')
            // ->add('cover')
            ->add('coverPicture')
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parcours::class,
        ]);
    }
}
