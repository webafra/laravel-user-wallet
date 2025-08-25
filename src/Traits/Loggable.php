<?php

namespace Webafra\LaravelUserWallet\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Webafra\LaravelUserWallet\Models\WalletsLog;

trait Loggable
{
    public function logs(): MorphMany
    {
        return $this->morphMany(WalletsLog::class, 'loggable');
    }
}
