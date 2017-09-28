<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imdbId')
            ->add('title',null,[
                "label"=> "Nom du film"
            ])
            ->add('year')
            ->add('cast')
            ->add('directors')
            ->add('writers')
            ->add('plot')
            ->add('runtime')
            ->add('trailerId')
            //->add('dateCreated')
            //->add('dateModified')
            ->add('genres')
            ->add('submit', SubmitType::class,["label"=>"Envoyer !"]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Movie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_movie';
    }


}
