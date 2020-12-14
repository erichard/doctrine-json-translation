<?php

namespace Erichard\DoctrineJsonTranslation;

use InvalidArgumentException;
use JsonSerializable;
use ArrayAccess;

class TranslatedField implements JsonSerializable, ArrayAccess
{
    protected $array;
    protected $defaultLocale;

    public function __construct($array)
    {
        $this->array = $array;
        $this->defaultLocale = \Locale::getDefault();
    }

    public function __toString()
    {
        try {
            return $this->get() ?? '';
        } catch (\Exception $e) {
            return '';
        }
    }

    public function __get($locale)
    {
        return $this->get($locale);
    }

    public function __set($locale, $value)
    {
        return $this->array[$locale] = $value;
    }

    public function get($locale = null)
    {
        if (null === $locale) {
            $locale = $this->defaultLocale;
        }

        return $this->array[$locale] ?? null;
    }

    public function find($locale = null)
    {
        if (!$this->has($locale)) {
            return null;
        }

        return $this->array[$locale];
    }

    public function has($locale = null)
    {
        if (null === $locale) {
            $locale = $this->defaultLocale;
        }

        return array_key_exists($locale, $this->array);
    }

    public function all()
    {
        return $this->array;
    }

    public function jsonSerialize()
    {
        return $this->__toString();
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
    }

    public function offsetGet($offset)
    {
        return $this->array[$offset] ?? null;
    }

    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }
}
