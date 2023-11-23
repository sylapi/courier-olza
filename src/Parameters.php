<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use ArrayObject;
use Rakit\Validation\Validator;
use Sylapi\Courier\Contracts\Validatable as ValidatableContract;
use Sylapi\Courier\Traits\Validatable;

class Parameters extends ArrayObject implements ValidatableContract
{
    use Validatable;

    const DEFAULT_LABEL_TYPE = 'A4';

    public $labelType;

    /**
     * @param array<string, mixed> $parameters
     */
    public static function create(array $parameters): self
    {
        return new self($parameters, ArrayObject::ARRAY_AS_PROPS);
    }

    public function getLabelType()
    {
        return  ($this->hasProperty('labelType') && $this->labelType !== null) ? $this->labelType : self::DEFAULT_LABEL_TYPE;
    }

    public function hasProperty(string $name)
    {
        return property_exists($this, $name);
    }

    public function validate(): bool
    {
        $rules = [
            'login'         => 'required',
            'password'      => 'required',
            'speditionCode' => 'required|in:GLS,CP,CP-RR,CP-NP,SP,DPD,PPL,ZAS-P,ZAS-K,ZAS-D,ZAS-C,ZAS-B,ZAS-COL,GEIS-P,BMCG-IPK,BMCG-IPKP,BMCG-DHL,BMCG-PPK,BMCG-PPE,BMCG-UC,BMCG-HUP,BMCG-FAN,BMCG-INT,BMCG-INT-PP,ZAS-ECONT-HD,ZAS-ECONT-PP,ZAS-ECONT-BOX,ZAS-ACS-HD,ZAS-ACS-PP,ZAS-SPEEDY-PP,ZAS-SPEEDY-HD',
            'shipmentType'  => 'required|in:DIRECT,WAREHOUSE',
        ];
        $data = [
            'login'         => $this->login ?? null,
            'password'      => $this->password ?? null,
            'speditionCode' => $this->speditionCode ?? null,
            'shipmentType'  => $this->shipmentType ?? null,
        ];

        $validator = new Validator();

        $validation = $validator->validate($data, $rules);
        if ($validation->fails()) {
            $this->setErrors($validation->errors()->toArray());

            return false;
        }

        return true;
    }
}
