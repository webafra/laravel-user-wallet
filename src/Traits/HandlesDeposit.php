<?php

namespace Webafra\LaravelUserWallet\Traits;

use App\Enums\WalletEnums;
use Illuminate\Support\Facades\DB;
use Webafra\LaravelUserWallet\Exceptions\InvalidDepositException;
use Webafra\LaravelUserWallet\Exceptions\InvalidValueException;
use Webafra\LaravelUserWallet\Exceptions\InvalidWalletTypeException;

trait HandlesDeposit
{
    /**
     * Deposit an amount to the user's wallet of a specific type.
     *
     * @throws InvalidDepositException
     * @throws InvalidValueException
     * @throws InvalidWalletTypeException
     */
    public function deposit(string $type, int|float $amount, ?string $notes = null): bool
    {
        $depositable = $this->getWalletableTypes();

        if (! $this->isRequestValid($type, $depositable)) {
            throw new InvalidDepositException('Invalid request request.');
        }

        if ($amount <= 0) {
            throw new InvalidValueException;
        }

        DB::transaction(function () use ($type, $amount, $notes) {
            $type = WalletEnums::tryFrom($type);
            $wallet = $this->wallets()->firstOrCreate(['type' => $type]);
            $wallet->incrementAndCreateLog($amount, $notes);
        });

        return true;
    }
}
