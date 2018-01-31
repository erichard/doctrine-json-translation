<?php

namespace Erichard\DoctrineJsonTranslation\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Erichard\DoctrineJsonTranslation\TranslatedField;

class TranslatedType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'JSONB';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = (is_resource($value)) ? stream_get_contents($value) : $value;

        $value = json_decode($value, true);

        return new TranslatedField($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof TranslatedField) {
            $value = $value->all();
        }

        $json = json_encode($value);

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'translated';
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return !$platform->hasNativeJsonType();
    }
}
