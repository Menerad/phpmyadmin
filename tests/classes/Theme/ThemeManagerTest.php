<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Theme;

use PhpMyAdmin\Config;
use PhpMyAdmin\Current;
use PhpMyAdmin\Tests\AbstractTestCase;
use PhpMyAdmin\Theme\ThemeManager;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ThemeManager::class)]
class ThemeManagerTest extends AbstractTestCase
{
    /**
     * SetUp for test cases
     */
    protected function setUp(): void
    {
        parent::setUp();

        parent::setGlobalConfig();

        $config = Config::getInstance();
        $config->settings['ThemePerServer'] = false;
        $config->settings['ThemeDefault'] = 'pmahomme';
        $config->settings['ServerDefault'] = 0;
        Current::$server = 99;
    }

    /**
     * Test for ThemeManager::getThemeCookieName
     */
    public function testCookieName(): void
    {
        $tm = new ThemeManager();
        self::assertEquals('pma_theme', $tm->getThemeCookieName());
    }

    /**
     * Test for ThemeManager::getThemeCookieName
     */
    public function testPerServerCookieName(): void
    {
        $tm = new ThemeManager();
        $tm->setThemePerServer(true);
        self::assertEquals('pma_theme-99', $tm->getThemeCookieName());
    }

    public function testGetThemesArray(): void
    {
        $tm = new ThemeManager();
        $tm->initializeTheme();
        $themes = $tm->getThemesArray();
        self::assertIsArray($themes);
        self::assertArrayHasKey(0, $themes);
        self::assertIsArray($themes[0]);
        self::assertArrayHasKey('id', $themes[0]);
        self::assertArrayHasKey('name', $themes[0]);
        self::assertArrayHasKey('version', $themes[0]);
        self::assertArrayHasKey('is_active', $themes[0]);
    }

    /**
     * Test for setThemeCookie
     */
    public function testSetThemeCookie(): void
    {
        $tm = new ThemeManager();
        self::assertTrue(
            $tm->setThemeCookie(),
        );
    }
}
