<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Theme;

use PhpMyAdmin\Tests\AbstractTestCase;
use PhpMyAdmin\Theme\Theme;
use PhpMyAdmin\Theme\ThemeManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Group;

use function filemtime;

use const DIRECTORY_SEPARATOR;
use const ROOT_PATH;
use const TEST_PATH;

#[CoversClass(Theme::class)]
class ThemeTest extends AbstractTestCase
{
    protected Theme $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = new Theme();

        parent::setGlobalConfig();
    }

    /**
     * Test for Theme::loadInfo
     */
    #[Group('medium')]
    public function testCheckImgPathNotExisted(): void
    {
        $this->object->setPath('path/to/nowhere');
        self::assertFalse($this->object->loadInfo());
    }

    /**
     * Test for Theme::loadInfo
     */
    public function testCheckImgPathIncorrect(): void
    {
        $this->object->setPath(TEST_PATH . 'tests/classes/_data/incorrect_theme');
        self::assertFalse(
            $this->object->loadInfo(),
            'Theme name is not properly set',
        );
    }

    /**
     * Test for Theme::getName, getVersion
     */
    public function testCheckImgPathFull(): void
    {
        $this->object->setFsPath(TEST_PATH . 'tests/classes/_data/gen_version_info/');
        self::assertTrue($this->object->loadInfo());
        self::assertEquals('Test Theme', $this->object->getName());
        self::assertEquals('6.0', $this->object->getVersion());
    }

    /**
     * Test for Theme::loadInfo
     */
    public function testLoadInfo(): void
    {
        $this->object->setFsPath(ROOT_PATH . 'public/themes/original/');
        $infofile = $this->object->getFsPath() . 'theme.json';
        self::assertTrue($this->object->loadInfo());

        self::assertEquals(
            filemtime($infofile),
            $this->object->mtimeInfo,
        );

        $this->object->setPath(ROOT_PATH . 'public/themes/original');
        $this->object->mtimeInfo = (int) filemtime($infofile);
        self::assertTrue($this->object->loadInfo());
        self::assertEquals('Original', $this->object->getName());
    }

    /**
     * Test for Theme::load
     */
    public function testLoad(): void
    {
        $newTheme = Theme::load(
            ThemeManager::getThemesDir() . 'original',
            ThemeManager::getThemesFsDir() . 'original' . DIRECTORY_SEPARATOR,
            'original',
        );
        self::assertNotNull($newTheme);
        self::assertInstanceOf(Theme::class, $newTheme);
    }

    /**
     * Test for Theme::load
     */
    public function testLoadNonExistent(): void
    {
        self::assertNull(
            Theme::load(
                ThemeManager::getThemesDir() . 'nonexistent',
                ThemeManager::getThemesFsDir() . 'nonexistent' . DIRECTORY_SEPARATOR,
                'nonexistent',
            ),
        );
    }

    /**
     * Test fir Theme::checkImgPath
     */
    public function testCheckImgPathFallback(): void
    {
        $this->object->setPath('path/to/nowhere');
        self::assertTrue($this->object->checkImgPath());
    }

    /**
     * Test for Theme::checkImgPath
     */
    public function testCheckImgPath(): void
    {
        $this->object->setPath(ROOT_PATH . 'themes/original');
        self::assertTrue($this->object->checkImgPath());
    }

    /**
     * Test for Theme::getPath
     */
    public function testGetSetPath(): void
    {
        self::assertEmpty($this->object->getPath());
        $this->object->setPath(ROOT_PATH . 'themes/original');

        self::assertEquals(ROOT_PATH . 'themes/original', $this->object->getPath());
    }

    /**
     * Test for Theme::checkVersion
     */
    #[Depends('testLoadInfo')]
    public function testGetSetCheckVersion(): void
    {
        self::assertEquals(
            '0.0.0.0',
            $this->object->getVersion(),
            'Version 0.0.0.0 by default',
        );

        $this->object->setVersion('1.2.3.4');
        self::assertEquals('1.2.3.4', $this->object->getVersion());

        self::assertFalse($this->object->checkVersion('0.0.1.1'));
        self::assertTrue($this->object->checkVersion('2.0.1.1'));
    }

    /**
     * Test for Theme::getName
     */
    public function testGetSetName(): void
    {
        self::assertEmpty($this->object->getName(), 'Name is empty by default');
        $this->object->setName('New Theme Name');

        self::assertEquals('New Theme Name', $this->object->getName());
    }

    /**
     * Test for Theme::getId
     */
    public function testGetSetId(): void
    {
        self::assertEmpty($this->object->getId(), 'ID is empty by default');
        $this->object->setId('NewID');

        self::assertEquals('NewID', $this->object->getId());
    }

    /**
     * Test for Theme::getImgPath
     */
    public function testGetSetImgPath(): void
    {
        self::assertEmpty(
            $this->object->getImgPath(),
            'ImgPath is empty by default',
        );
        $this->object->setImgPath('/new/path');

        self::assertEquals('/new/path', $this->object->getImgPath());
    }

    /**
     * Test for getImgPath
     *
     * @param string|null $file     file name for image
     * @param string|null $fallback fallback image
     * @param string      $output   expected output
     */
    #[DataProvider('providerForGetImgPath')]
    public function testGetImgPath(string|null $file, string|null $fallback, string $output): void
    {
        self::assertEquals(
            $this->object->getImgPath($file, $fallback),
            $output,
        );
    }

    /**
     * Provider for testGetImgPath
     *
     * @return mixed[]
     */
    public static function providerForGetImgPath(): array
    {
        return [
            [null, null, ''],
            ['screen.png', null, './themes/pmahomme/img/screen.png'],
            ['arrow_ltr.png', null, './themes/pmahomme/img/arrow_ltr.png'],
            ['logo_right.png', 'pma_logo.png', './themes/pmahomme/img/pma_logo.png'],
        ];
    }
}
