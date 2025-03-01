<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Navigation\Nodes;

use PhpMyAdmin\Config;
use PhpMyAdmin\Navigation\Nodes\NodeFunctionContainer;
use PhpMyAdmin\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NodeFunctionContainer::class)]
class NodeFunctionContainerTest extends AbstractTestCase
{
    /**
     * Test for __construct
     */
    public function testConstructor(): void
    {
        $parent = new NodeFunctionContainer(new Config());
        self::assertEquals(
            [
                'text' => ['route' => '/database/routines', 'params' => ['type' => 'FUNCTION', 'db' => null]],
                'icon' => ['route' => '/database/routines', 'params' => ['type' => 'FUNCTION', 'db' => null]],
            ],
            $parent->links,
        );
        self::assertEquals('functions', $parent->realName);
    }
}
