<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du Projet'
            ])
            ->add('Url', null, ['label'=>'Lien du Projet'])
            ->add('programmedAt', null,  ['label' =>'A quelle date a été créé le projet'])
            ->add('image',null ,['label' =>'Image'])
            ->add('description', null, ['label' =>'Description HTML du projet'])
            ->add('isPublished',null, ['label' =>'Le projet doit-étre visibile dans la portfloio'] )



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
