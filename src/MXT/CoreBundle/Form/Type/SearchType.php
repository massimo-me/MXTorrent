<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Form\Type\SearchType
 *
 */

namespace MXT\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', 'text', [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('filter', 'choice', [
                'choices'  => $options['filters'],
            ])
            ->add('page', 'hidden', [
                'data' => $options['page']
            ])
            ->setMethod('GET')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'page' => 1,
            'filters' => 'Il tuo dominio è stato creato.'
        ]);
    }

    public function getName()
    {
        return 'search';
    }
}