# hashids-middleware
PSR-15/PSR-7 compliant middleware using ivanakimov/hashids.php

[![Build Status](https://api.travis-ci.org/icanhazstring/hashids-middleware.svg?branch=master)](https://travis-ci.org/icanhazstring/hashids-middleware)

## Install

You can install the *hashids-middleware* library with composer:
```bash
$ composer require icanhazstring/hashids-middleware
```

## Usage

### Using expressive

Include the `HashidsConfigProvider` inside your `config/config.php`:

```php
$aggregator = new ConfigAggregator([
    ...
    \icanhazstring\Middleware\HashidsConfigProvider::class,
    ...
]);
```

Make sure the `OptimusConfigProvider` is included before your autoload files!

### Custom configuration

If you want to change parameters of `Hashids`, simply provide the
`HashIdsMiddleware::CONFIG_KEY` inside your autoload configuration and change the values to your desire.

```php
return [
    \icanhazstring\Middleware\HashidsMiddleware::CONFIG_KEY => [
        'salt'          => '',
        'minHashLength' => 0,
        'alphabet'      => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ]
];
```
