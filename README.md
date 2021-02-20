# Logger

[![codecov](https://codecov.io/gh/Innmind/Logger/branch/develop/graph/badge.svg)](https://codecov.io/gh/Innmind/Logger)
[![Build Status](https://github.com/Innmind/Logger/workflows/CI/badge.svg?branch=master)](https://github.com/Innmind/Logger/actions?query=workflow%3ACI)
[![Type Coverage](https://shepherd.dev/github/Innmind/Logger/coverage.svg)](https://shepherd.dev/github/Innmind/Logger)

Simple abstraction of monolog to simplify (hopefully) the creation of a logger.

## Installation

```sh
composer require innmind/logger
```

## Usage

```php
use function Innmind\Logger\bootstrap;
use Innmind\Url\Url;

$logger = bootstrap(
    'myApp',
    Url::of('file://'.__DIR__.'/var/log.txt'),
    Url::of('sentry://user@sentry.io/project-id'),
);

$concrete = $logger();
$fingersCrossed = $logger('error');
```

In this example both `$concrete` and `$fingersCrossed` are instances of `Psr\Log\LoggerInterface` with the first one that will write each log to the specified file and to sentry, the latter will only write logs when the `error` level is reached.

If you want to specify the log level for each handler you can do it by adding the `level` query parameter in the handler url.
