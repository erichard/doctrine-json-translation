<?php

namespace AdminBundle\Form;

use Erichard\DoctrineJsonTranslation\TranslatedField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslatedType extends AbstractType
{
    protected $availableLocales;

    public function __construct($availableLocales)
    {
        $this->availableLocales = $availableLocales;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->availableLocales as $locale) {
            $builder->add($locale, $options['entry_type'], ['label' => false]);
        }

        $builder->addViewTransformer(new CallbackTransformer(
            function ($fieldAsObject = null) {
                if (null === $fieldAsObject) {
                    return null;
                } elseif (is_array($fieldAsObject)) {
                    return $fieldAsObject;
                } elseif ($fieldAsObject instanceof TranslatedField) {
                    return $fieldAsObject->all();
                } else {
                    throw new \InvalidArgumentException();
                }
            },
            function ($fieldAsArray) {
                return new TranslatedField($fieldAsArray);
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_type' => TextType::class,
        ]);
    }
}
