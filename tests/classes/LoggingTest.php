<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests;

use PhpMyAdmin\Logging;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Logging::class)]
class LoggingTest extends AbstractTestCase
{
    public function testGetLogMessage(): void
    {
        $_SERVER['REMOTE_ADDR'] = '0.0.0.0';
        $log = Logging::getLogMessage('user', 'ok');
        self::assertEquals('user authenticated: user from 0.0.0.0', $log);
        $log = Logging::getLogMessage('user', 'error');
        self::assertEquals('user denied: user (error) from 0.0.0.0', $log);
    }
}
