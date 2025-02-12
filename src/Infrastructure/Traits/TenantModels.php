<?php

namespace Infrastructure\Traits;

use Infrastructure\Persistence\Scopes\TenantScope;

trait TenantModels
{
    public static function bootTenantModels(): void
    {
        static::addGlobalScope(new TenantScope());
    }
}
