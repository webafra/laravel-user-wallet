# laravel user wallet manager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/webafra/laravel-user-wallet.svg?style=flat-square)](https://packagist.org/packages/webafra/laravel-user-wallet)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/webafra/laravel-user-wallet/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/webafra/laravel-user-wallet/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/webafra/laravel-user-wallet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/webafra/laravel-user-wallet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/webafra/laravel-user-wallet.svg?style=flat-square)](https://packagist.org/packages/webafra/laravel-user-wallet)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-user-wallet.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-user-wallet)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require webafra/laravel-user-wallet
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-user-wallet-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-user-wallet-config"
```

### Prepare User Model

To use this package you need to implement the `WalletOperations` into `User` model and utilize the `ManagesWallet` trait.

```php

use HPWebdeveloper\LaravelPayPocket\Interfaces\WalletOperations;
use HPWebdeveloper\LaravelPayPocket\Traits\ManagesWallet;

class User extends Authenticatable implements WalletOperations
{
    use ManagesWallet;
}
```

### Prepare Wallets

In Laravel Pay Pocket, you have the flexibility to define the order in which wallets are prioritized for payments through the use of Enums. The order of wallets in the Enum file determines their priority level. The first wallet listed has the highest priority and will be used first for deducting order values.

For example, consider the following wallet types defined in the Enum class (published in step 3 of installation):

```php
namespace App\Enums;

enum WalletEnums: string
{
    case WALLET1 = 'wallet_1';
    case WALLET2 = 'wallet_2';
}

```

**You have complete freedom to name your wallets as per your requirements and even add more wallet types to the Enum list.**

In this particular setup, `wallet_1` (`WALLET1`) is given the **highest priority**. When an order payment is processed, the system will first attempt to use `wallet_1` to cover the cost. If `wallet_1` does not have sufficient funds, `wallet_2` (`WALLET2`) will be used next.

### Example:

If the balance in `wallet_1` is 10 and the balance in `wallet_2` is 20, and you need to pay an order value of 15, the payment process will first utilize the entire balance of `wallet_1`. Since `wallet_1`'s balance is insufficient to cover the full amount, the remaining 5 will be deducted from `wallet_2`. After the payment, `wallet_2` will have a remaining balance of 15."

## Usage, APIs and Operations:

### Deposit

```php
deposit(type: 'wallet_1', amount: 123.45, notes: null)
```

Deposit funds into `wallet_1`

```php
$user = auth()->user();
$user->deposit('wallet_1', 123.45);
```

Deposit funds into `wallet_2`

```php
$user = auth()->user();
$user->deposit('wallet_2', 67.89);
```

Or using provided facade

```php
use HPWebdeveloper\LaravelPayPocket\Facades\LaravelPayPocket;

$user = auth()->user();
LaravelPayPocket::deposit($user, 'wallet_1', 123.45);

```

Note: `wallet_1` and `wallet_2` must already be defined in the `WalletEnums`.

#### Transaction Info ([#8][i8])

When you need to add descriptions for a specific transaction, the `$notes` parameter enables you to provide details explaining the reason behind the transaction.

```php
$user = auth()->user();
$user->deposit('wallet_1', 67.89, 'You ordered pizza.');
```

### Pay

```php
pay(amount: 12.34, notes: null)
```

Pay the value using the total combined balance available across all allowed wallets

```php
$user = auth()->user();
$user->pay(12.34);
```

Or using provided facade

```php
use HPWebdeveloper\LaravelPayPocket\Facades\LaravelPayPocket;

$user = auth()->user();
LaravelPayPocket::pay($user, 12.34);
```

### Balance

-   **Wallets**

```php
$user->walletBalance // Total combined balance available across all wallets

// Or using provided facade

LaravelPayPocket::checkBalance($user);
```

-   **Particular Wallet**

```php
$user->getWalletBalanceByType('wallet_1') // Balance available in wallet_1
$user->getWalletBalanceByType('wallet_2') // Balance available in wallet_2

// Or using provided facade

LaravelPayPocket::walletBalanceByType($user, 'wallet_1');
```

### Exceptions

Upon examining the `src/Exceptions` directory within the source code,
you will discover a variety of exceptions tailored to address each scenario of invalid entry. Review the [demo](https://github.com/HPWebdeveloper/demo-pay-pocket) that accounts for some of the exceptions.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [webafra](https://github.com/webafra)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
