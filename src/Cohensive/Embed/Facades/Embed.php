<?php
namespace Cohensive\Embed\Facades;

use Illuminate\Support\Facades\Facade;

class Embed extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'embed'; }
}
