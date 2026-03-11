<?php

namespace Webafra\LaravelUserWallet\Facades;

use Illuminate\Support\Facades\Facade;
use Webafra\LaravelUserWallet\Services\WalletServices;

/**
 * @see \Webafra\LaravelUserWallet\LaravelUserWallet
 */
class LaravelUserWallet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return WalletServices::class;
    }
}
