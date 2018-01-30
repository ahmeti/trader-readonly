<?php

namespace Ahmeti\Trader\Facades;

use Illuminate\Support\Facades\Facade;

class Trader extends Facade {
    protected static function getFacadeAccessor() { return 'ahmeti-trader'; }
}