<?php

namespace Webafra\LaravelUserWallet\Traits;

trait ManagesWallet
{
    use HandlesDeposit, HandlesPayment, HasWallet;
}
