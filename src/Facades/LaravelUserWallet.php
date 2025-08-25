<?php

namespace Webafra\LaravelUserWallet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Webafra\LaravelUserWallet\LaravelUserWallet
 */
class LaravelUserWallet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Webafra\LaravelUserWallet\Services\WalletServices::class;
    }
}
