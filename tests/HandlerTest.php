<?php
declare(strict_types = 1);

namespace Tests\Innmind\Logger;

use function Innmind\Logger\create;
use Innmind\Logger\Exception\UnknownDSN;
use Innmind\Url\Url;
use Monolog\Handler\{
    HandlerInterface,
    StreamHandler,
    NullHandler,
};
use Sentry\Monolog\Handler as SentryHandler;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    /**
     * @dataProvider dsns
     */
    public function testMake($dsn, $expected)
    {
        $handler = create(Url::of($dsn));

        $this->assertInstanceOf(HandlerInterface::class, $handler);
        $this->assertInstanceOf($expected, $handler);
    }

    public function testThrowWhenUnknownScheme()
    {
        $this->expectException(UnknownDSN::class);
        $this->expectExceptionMessage('foobar://something');

        create(Url::of('foobar://something'));
    }

    public function dsns(): array
    {
        return [
            ['file:///tmp/log.txt', StreamHandler::class],
            ['sentry://secret@sentry.io/project-id', SentryHandler::class],
            ['null://', NullHandler::class],
        ];
    }
}
