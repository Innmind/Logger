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
};
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
                return new RavenHandler(
                    new Client(
                        (string) $dsn->withScheme(new Scheme('https'))
                    ),
                    $params['level'] ?? 'debug'
                );
        }

        throw new UnknownDSN((string) $dsn);
    }
}
