<?php

namespace Webafra\LaravelUserWallet\Traits;

use Illuminate\Support\Facades\DB;
use Webafra\LaravelUserWallet\Exceptions\InsufficientBalanceException;
use Webafra\LaravelUserWallet\Exceptions\InvalidDepositException;
use Webafra\LaravelUserWallet\Exceptions\InvalidValueException;

trait HandlesPayment
{

    /**
     * Pay the order value from the user's wallets.
     *
     * @throws InsufficientBalanceException
     * @throws InvalidDepositException
     */

    public function pay(int|float $amount, ?string $type = null, ?string $notes = null): void
    {
        $paymentable = $this->getWalletableTypes();

        if ($type) {
            if (!$this->isRequestValid($type, $paymentable)) {
                throw new InvalidDepositException('Invalid payment request.');
            }
        }


        if ($amount <= 0) {
            throw new InvalidValueException;
        }


        if ($type) {
            $balance = $this->getWalletBalanceByType($type);
            if ($balance < $amount) {
                throw new InsufficientBalanceException('Insufficient balance to cover the order.');
            }
        } else {
            if (!$this->hasSufficientBalance($amount)) {
                throw new InsufficientBalanceException('Insufficient balance to cover the order.');
            }
        }


        DB::transaction(function () use ($amount, $type, $notes) {
            $remainingOrderValue = $amount;

            /**
             * @var \Illuminate\Support\Collection<TKey, \Webafra\LaravelUserWallet\Models\Wallet>
             */

            $walletsInOrder = $this->wallets()->whereIn('type', $type ? [$type] : $this->walletsInOrder())->get();


            foreach ($walletsInOrder as $wallet) {
                if (!$wallet || !$wallet->hasBalance()) {
                    continue;
                }

                $amountToDeduct = min($wallet->balance, $remainingOrderValue);
                $wallet->decrementAndCreateLog($amountToDeduct, $notes);
                $remainingOrderValue -= $amountToDeduct;

                if ($remainingOrderValue <= 0) {
                    break;
                }
            }

            if ($remainingOrderValue > 0) {
                throw new InsufficientBalanceException('Insufficient total wallet balance to cover the order.');
            }
        });
    }

}
