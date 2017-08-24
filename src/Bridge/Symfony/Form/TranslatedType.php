<?php

namespace Erichard\DoctrineJsonTranslation\Bridge\Symfony\Form;

use Erichard\DoctrineJsonTranslation\TranslatedField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TranslatedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fr', TextType::class, ['label' => false]);
        $builder->addViewTransformer(new CallbackTransformer(
            function (TranslatedField $fieldAsObject = null) {
                return null === $fieldAsObject ? [] : $fieldAsObject->all();
            },
            function (array $fieldAsArray) {
                return new TranslatedField($fieldAsArray);
            }
        ));
    }
}
