<?php

namespace Ahmeti\Trader\Facades;

use Illuminate\Support\Facades\Facade;

class Binance extends Facade {
    protected static function getFacadeAccessor() { return 'ahmeti-trader-binance'; }
}