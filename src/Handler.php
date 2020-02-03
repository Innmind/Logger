<?php
declare(strict_types = 1);

namespace Innmind\Logger;

use Innmind\Logger\Exception\UnknownDSN;
use Innmind\Url\{
    UrlInterface,
    Scheme,
};
use Monolog\Handler\{
    HandlerInterface,
    StreamHandler,
    RavenHandler,
    NullHandler,
};
use Sentry\{
    SentrySdk,
    Monolog\Handler as SentryHandler,
};
use function Sentry\init as sentry;
use Raven_Client as Client;

final class Handler
{
    public static function make(UrlInterface $dsn): HandlerInterface
    {
        parse_str((string) $dsn->query(), $params);

        switch ((string) $dsn->scheme()) {
            case 'file':
                return new StreamHandler(
                    (string) $dsn->path(),
                    $params['level'] ?? 'debug'
                );
            case 'sentry':
                sentry(['dsn' => (string) $dsn->withScheme(new Scheme('https'))]);

                return new SentryHandler(
                    SentrySdk::getCurrentHub(),
                    $params['level'] ?? 'debug'
                );

            case 'null':
                return new NullHandler;
        }

        throw new UnknownDSN((string) $dsn);
    }
}
