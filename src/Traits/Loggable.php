<?php

namespace Webafra\LaravelUserWallet\Traits;

use Webafra\LaravelUserWallet\Models\WalletsLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Loggable
{
    public function logs(): MorphMany
    {
        return $this->morphMany(WalletsLog::class, 'loggable');
    }
}
