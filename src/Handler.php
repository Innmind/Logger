<?php
declare(strict_types = 1);

namespace Innmind\Logger;

use Innmind\Logger\Exception\UnknownDSN;
use Innmind\Url\{
    Url,
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
    public static function make(Url $dsn): HandlerInterface
    {
        parse_str($dsn->query()->toString(), $params);

        switch ($dsn->scheme()->toString()) {
            case 'file':
                return new StreamHandler(
                    $dsn->path()->toString(),
                    $params['level'] ?? 'debug'
                );
            case 'sentry':
                sentry(['dsn' => $dsn->withScheme(Scheme::of('https'))->toString()]);

                return new SentryHandler(
                    SentrySdk::getCurrentHub(),
                    $params['level'] ?? 'debug'
                );

            case 'null':
                return new NullHandler;
        }

        throw new UnknownDSN($dsn->toString());
    }
}
