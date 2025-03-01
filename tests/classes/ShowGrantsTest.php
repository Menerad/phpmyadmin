<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests;

use PhpMyAdmin\ShowGrants;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ShowGrants::class)]
class ShowGrantsTest extends AbstractTestCase
{
    public function test1(): void
    {
        $showGrants = new ShowGrants('GRANT ALL PRIVILEGES ON *.* TO \'root\'@\'localhost\' WITH GRANT OPTION');
        self::assertEquals('ALL PRIVILEGES', $showGrants->grants);
        self::assertEquals('*', $showGrants->dbName);
        self::assertEquals('*', $showGrants->tableName);
    }

    public function test2(): void
    {
        $showGrants = new ShowGrants('GRANT ALL PRIVILEGES ON `mysql`.* TO \'root\'@\'localhost\' WITH GRANT OPTION');
        self::assertEquals('ALL PRIVILEGES', $showGrants->grants);
        self::assertEquals('mysql', $showGrants->dbName);
        self::assertEquals('*', $showGrants->tableName);
    }

    public function test3(): void
    {
        $showGrants = new ShowGrants(
            'GRANT SELECT, INSERT, UPDATE, DELETE ON `mysql`.`columns_priv` TO \'root\'@\'localhost\'',
        );
        self::assertEquals('SELECT, INSERT, UPDATE, DELETE', $showGrants->grants);
        self::assertEquals('mysql', $showGrants->dbName);
        self::assertEquals('columns_priv', $showGrants->tableName);
    }

    public function test4(): void
    {
        $showGrants = new ShowGrants('GRANT ALL PRIVILEGES ON `cptest\_.`.* TO \'cptest\'@\'localhost\'');
        self::assertEquals('cptest\_.', $showGrants->dbName);

        $showGrants = new ShowGrants(
            'GRANT ALL PRIVILEGES ON `cptest\_.a.b.c.d.e.f.g.h.i.j.k.'
                . 'l.m.n.o.p.q.r.s.t.u.v.w.x.y.z`.* TO \'cptest\'@\'localhost\'',
        );
        self::assertEquals('cptest\_.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z', $showGrants->dbName);
    }
}
