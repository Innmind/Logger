# Logger

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Logger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Logger/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Logger/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Logger/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Logger/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Logger/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Logger/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Logger/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/Innmind/Logger/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Logger/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/Innmind/Logger/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Logger/build-status/develop) |

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
    Url::fromString('file://'.__DIR__.'/var/log.txt'),
    Url::fromString('sentry://user@sentry.io/project-id')
);

$concrete = $logger();
$fingersCrossed = $logger('error');
```

In this example both `$concrete` and `$fingersCrossed` are instances of `Psr\Log\LoggerInterface` with the first one that will write each log to the specified file and to sentry, the latter will only write logs when the `error` level is reached.

If you want to specify the log level for each handler you can do it by adding the `level` query parameter in the handler url.
