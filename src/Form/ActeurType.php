<?php

namespace App\Form;

use App\Entity\Acteur;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomActeur')
            ->add('prenomActeur')
            ->add('sexeActeur', ChoiceType::class,[
                  'expanded' => true,
                  'multiple' => false,
                  'choices' => array(
                    'homme' => 'H',
                    'femme' => 'F')
                  ])
            ->add('dateNaissanceActeur', DateType::class,[
              'years' => range(date('1910'), date('Y')),
              'format' => 'ddMMyyy',
              'data' => new \DateTime()
            ])
            ->add('idFilm')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Acteur::class,
        ]);
    }
}
