<?php

use App\Utilities\Base62Service;
use App\Utilities\SnowflakeGenerator;

class KeyGenerationService
{
    protected $length;

    public function __construct(
        protected SnowflakeGenerator $snowflakeGenerator,
        protected Base62Service $base62Service,
        protected CodePoolRepository $codePoolRepository
    )
    {}

    #[NoDiscard]
    public function generate(): string
    {
        return$this->base62Service->encode($this->snowflakeGenerator->nextId());
    }
}
