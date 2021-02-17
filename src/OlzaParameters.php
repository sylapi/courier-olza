<?php
declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use ArrayObject;

class OlzaParameters extends ArrayObject
{

    const DEFAULT_LABEL_TYPE = 'A4';
    
    public static function create(array $parameters) : self
    {
        return new self($parameters, ArrayObject::ARRAY_AS_PROPS);
    }

    public function getLabelType()
    {
       return  ($this->hasProperty('labelType')) ? $this->labelType : self::DEFAULT_LABEL_TYPE;
    }

    public function hasProperty(string $name)
    {
        return property_exists($this, $name);
    }
}