# icanhazstring/expressive-hashids-middleware
PSR-15/PSR-7 compliant middleware using ivanakimov/hashids.php

[![Build Status](https://api.travis-ci.org/icanhazstring/expressive-hashids-middleware.svg?branch=master)](https://travis-ci.org/icanhazstring/expressive-hashids-middleware) [![Code Climate](https://codeclimate.com/github/icanhazstring/expressive-hashids-middleware/badges/gpa.svg)](https://codeclimate.com/github/icanhazstring/expressive-hashids-middleware) [![Test Coverage](https://codeclimate.com/github/icanhazstring/expressive-hashids-middleware/badges/coverage.svg)](https://codeclimate.com/github/icanhazstring/expressive-hashids-middleware/coverage)

## Install

You can install the *expressive-hashids-middleware* library with composer:
```bash
$ composer require icanhazstring/expressive-hashids-middleware
```

## Workflow

The main purpose of the middleware is to obfuscate internal IDs from the outside world.
That said, you don't have to change your internal id handling (like autoincrement in your db) to 
use this middleware.

Any incoming request in the form of `/api/resource/{id}` will be decoded using this middleware.
So for example (default configuration):

`/api/user/ABC` (where `ABC` is the encoded value) will produce request attributes like this:

```php
$attributes = $request->getAttributes();

/*
[
    'id' => 'ABC',
    '__hashids_identifier' => 1
]
*/
```

> The middleware **won't override** attributes!
> You can use the `HashidsMiddleware::ATTRIBUTE` constant to easy access this attribute.

## Usage

### Using expressive

Include the `HashidsConfigProvider` inside your `config/config.php`:

```php
$aggregator = new ConfigAggregator([
    ...
    \icanhazstring\Hashids\HashidsConfigProvider::class,
    ...
]);
```

Make sure the `HashidsConfigProvider` is included before your autoload files!

### Custom configuration

If you want to change parameters of `Hashids`, simply provide the
`HashidsConfigProvider::CONFIG_KEY` inside your autoload configuration and change the values to your desire.

```php
return [
    \icanhazstring\Hashids\HashidsConfigProvider::CONFIG_KEY => [
        'salt'                 => '',
        'minHashLength'        => 0,
        'alphabet'             => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
        'resource_identifiert' => 'id'
    ]
];
```

### Using the strategy

If you want, you can use the hydration/extraction strategy provided to decode/encode data
from and into your objects.

To use the strategy, simply use the provided delegator `HashidsHydratorDelegatorFactory` and append
it as delegator for your hydrator.

```php
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'hydrators' => [
                'delegators' => [
                    ArraySerializable::class => [
                        \icanhazstring\Hashids\Hydrator\HashidsHydratorDelegatorFactory:class
                    ]
                ]
            ],
        ];
    }
}
```