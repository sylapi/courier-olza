<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Rakit\Validation\Validator;
use Sylapi\Courier\Abstracts\Receiver;

class OlzaReceiver extends Receiver
{
    public function validate(): bool
    {
        $rules = [
            'firstName' => 'required',
            'surname' => 'required',
            'countryCode' => 'required|min:2|max:2',
            'city' => 'required',
            'zipCode' => 'required',
            'street' => 'required',
            'address' => 'required',
            'email' => 'required|email',
        ];
        
        $data = $this->toArray();
        
        $validator = new Validator;

        $validation = $validator->validate($data, $rules);
        if ($validation->fails()) {
            $this->setErrors($validation->errors()->toArray());
            return false;
        }
        return true;
    }
}
