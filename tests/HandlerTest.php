<?php
declare(strict_types = 1);

namespace Tests\Innmind\Logger;

use Innmind\Logger\{
    Handler,
    Exception\UnknownDSN,
};
use Innmind\Url\Url;
use Monolog\Handler\{
    HandlerInterface,
    StreamHandler,
    RavenHandler,
};
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    /**
     * @dataProvider dsns
     */
    public function testMake($dsn, $expected)
    {
        $handler = Handler::make(Url::fromString($dsn));

        $this->assertInstanceOf(HandlerInterface::class, $handler);
        $this->assertInstanceOf($expected, $handler);
    }

    public function testThrowWhenUnknownScheme()
    {
        $this->expectException(UnknownDSN::class);
        $this->expectExceptionMessage('foobar://something');

        Handler::make(Url::fromString('foobar://something'));
    }

    public function dsns(): array
    {
        return [
            ['file:///tmp/log.txt', StreamHandler::class],
            ['sentry://secret@sentry.io/project-id', RavenHandler::class],
        ];
    }
}
