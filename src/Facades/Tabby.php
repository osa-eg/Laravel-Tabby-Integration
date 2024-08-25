<?php
namespace Osama\TabbyIntegration\Facades;

use Illuminate\Support\Facades\Facade;

class Tabby extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tabby';
    }
}
