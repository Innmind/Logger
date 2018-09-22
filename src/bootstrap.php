<?php
declare(strict_types = 1);

namespace Innmind\Logger;

use Innmind\Url\UrlInterface;
use Monolog\{
    Logger,
    Handler\GroupHandler,
    Handler\FingersCrossedHandler,
};
use Psr\Log\LoggerInterface;

function bootstrap(string $name, UrlInterface ...$dsns): callable
{
    $handlers = array_map([Handler::class, 'make'], $dsns);

    if (count($handlers) === 1) {
        $handler = $handlers[0];
    } else {
        $handler = new GroupHandler($handlers);
    }

    return static function(string $activationLevel = null) use ($name, $handler): LoggerInterface {
        if (is_string($activationLevel)) {
            $handler = new FingersCrossedHandler($handler, $activationLevel);
        }

        return new Logger($name, [$handler]);
    };
}
