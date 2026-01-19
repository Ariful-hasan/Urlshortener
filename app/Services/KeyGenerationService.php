<?php

namespace App\Services;

use App\Utilities\Base62Service;
use App\Utilities\SnowflakeGenerator;
use NoDiscard;

class KeyGenerationService
{
    public function __construct(
        protected SnowflakeGenerator $snowflakeGenerator,
        protected Base62Service $base62Service
    )
    {}

    #[NoDiscard]
    public function generate(): string
    {
        return $this->base62Service->encode($this->snowflakeGenerator->nextId());
    }
}
