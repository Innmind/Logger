<?php
declare(strict_types = 1);

namespace Innmind\Logger;

use Innmind\Logger\Exception\UnknownDSN;
use Innmind\Url\{
    Url,
    Scheme,
};
use Monolog\{
    Logger,
    Handler\HandlerInterface,
    Handler\StreamHandler,
    Handler\NullHandler,
    Handler\GroupHandler,
    Handler\FingersCrossedHandler,
};
use Sentry\{
    SentrySdk,
    Monolog\Handler as SentryHandler,
};
use function Sentry\init as sentry;
use Psr\Log\LoggerInterface;

/**
 * @return callable(string = null): LoggerInterface
 */
function bootstrap(string $name, Url ...$dsns): callable
{
    $handlers = \array_map('Innmind\Logger\create', $dsns);

    if (\count($handlers) === 1) {
        $handler = $handlers[0];
    } else {
        $handler = new GroupHandler($handlers);
    }

    return static function(string $activationLevel = null) use ($name, $handler): LoggerInterface {
        if (\is_string($activationLevel)) {
            $handler = new FingersCrossedHandler($handler, $activationLevel);
        }

        return new Logger($name, [$handler]);
    };
}

function create(Url $dsn): HandlerInterface
{
    /** @var array{level?: string} $params */
    $params = [];
    \parse_str($dsn->query()->toString(), $params);
    /** @var string $level */
    $level = $params['level'] ?? 'debug';

    switch ($dsn->scheme()->toString()) {
        case 'file':
            return new StreamHandler(
                $dsn->path()->toString(),
                $level,
            );
        case 'sentry':
            sentry(['dsn' => $dsn->withScheme(Scheme::of('https'))->toString()]);

            return new SentryHandler(
                SentrySdk::getCurrentHub(),
                Logger::toMonologLevel($level),
            );

        case 'null':
            return new NullHandler;
    }

    throw new UnknownDSN($dsn->toString());
}
