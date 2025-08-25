<?php

namespace Webafra\LaravelUserWallet\Commands;

use Illuminate\Console\Command;

class LaravelUserWalletCommand extends Command
{
    public $signature = 'laravel-user-wallet';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
