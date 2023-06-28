- [![Starts](https://img.shields.io/github/stars/laravelir/paymentable?style=flat&logo=github)](https://github.com/laravelir/paymentable/forks)
- [![Forks](https://img.shields.io/github/forks/laravelir/paymentable?style=flat&logo=github)](https://github.com/laravelir/paymentable/stargazers)
  [![Total Downloads](https://img.shields.io/packagist/dt/laravelir/paymentable.svg?style=flat-square)](https://packagist.org/packages/laravelir/paymentable)


# laravelir/paymentable

A multi driver ir payment laravel package 

### Installation

1. Run the command below to add this paymentable:

```
composer require laravelir/paymentable
```

2. Open your config/app.php and add the following to the providers / aliases array:

```php
Laravelir\Paymentable\Providers\PaymentableServiceProvider::class, // provider
```

```php
'Cart' => Laravelir\Cart\Facade\Cart::class, // alias
```

3. Run the command below to install the package:

```
php artisan paymentable:install
```

### Drivers


### Usage

```php

$user = auth()->user;
$amount = 5000; // toman
$paymentResult = Paymentable::payment($user, $toman);

if($paymentResult->is_success()) {
    $transactionId = $paymentResult->transactionId();

    return $paymentResult->redirectToGateway();
}

// callback

```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [:author_name](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
