<?php
declare(strict_types = 1);

namespace Tests\Innmind\Logger;

use function Innmind\Logger\bootstrap;
use Innmind\Url\Url;
use Monolog\{
    Logger,
    Handler\StreamHandler,
    Handler\GroupHandler,
    Handler\FingersCrossedHandler,
};
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $logger = bootstrap(
            'foo',
            Url::fromString('file:///tmp/log.txt')
        );

        $this->assertIsCallable($logger);
        $this->assertInstanceOf(Logger::class, $logger());
        $this->assertInstanceOf(StreamHandler::class, $logger()->popHandler());

        $logger = bootstrap(
            'foo',
            Url::fromString('file:///tmp/log.txt'),
            Url::fromString('file:///tmp/log2.txt')
        );
        $this->assertIsCallable($logger);
        $this->assertInstanceOf(Logger::class, $logger());
        $this->assertInstanceOf(GroupHandler::class, $logger()->popHandler());

        $logger = $logger('error');
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertInstanceOf(FingersCrossedHandler::class, $logger->popHandler());
    }
}
