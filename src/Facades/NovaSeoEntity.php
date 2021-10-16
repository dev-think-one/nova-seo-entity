<?php

namespace NovaSeoEntity\Facades;

use Illuminate\Support\Facades\Facade;

class NovaSeoEntity extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'nova-seo-entity';
    }
}
