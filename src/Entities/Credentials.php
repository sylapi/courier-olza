<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\Entities;

use Sylapi\Courier\Contracts\Credentials as CredentialsContract;
use Sylapi\Courier\Abstracts\Credentials as CredentialsAbstract;
use OlzaApiClient\Entities\Helpers\HeaderEntity;

class Credentials extends CredentialsAbstract implements CredentialsContract
{
    private string $languageCode;

    public function setLanguageCode(string $languageCode): self
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    public function getLanguageCode(): string
    {
        return $this->languageCode ?? HeaderEntity::LANG_PL;
    }
}