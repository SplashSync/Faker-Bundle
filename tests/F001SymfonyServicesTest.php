<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Faker\Tests;

use Exception;
use PHPUnit\Framework\Assert;
use Splash\Bundle\Models\AbstractConnector;
use Splash\Connectors\Faker\Actions;
use Splash\Tests\Tools\TestCase;

class F001SymfonyServicesTest extends TestCase
{
    /**
     * Test Declaration of Splash Objects
     *
     * @throws Exception
     *
     * @return void
     */
    public function testSplashObjectsExists(): void
    {
        $connector = $this->getConnector("faker");
        $this->assertInstanceOf(AbstractConnector::class, $connector);

        $objectTypes = $connector->getAvailableObjects();
        $this->assertContains("short", $objectTypes);
        $this->assertContains("simple", $objectTypes);
        $this->assertContains("primary", $objectTypes);
        $this->assertContains("list", $objectTypes);
        $this->assertContains("image", $objectTypes);
    }

    /**
     * Test Declaration of Connector Actions
     *
     * @throws Exception
     *
     * @return void
     */
    public function testConnectorActionsExists(): void
    {
        $connector = $this->getConnector("faker");
        $this->assertInstanceOf(AbstractConnector::class, $connector);

        $publicActions = $connector->getPublicActions();
        Assert::assertEquals(Actions\Master::class, $publicActions['master']);
        Assert::assertEquals(Actions\Master::class, $publicActions['index']);
        Assert::assertEquals(Actions\Validate::class, $publicActions['validate']);
        Assert::assertEquals(Actions\Invalidate::class, $publicActions['invalidate']);
        Assert::assertEquals(Actions\Fail::class, $publicActions['fail']);
    }

    /**
     * Test Declaration of Connector Actions
     *
     * @throws Exception
     *
     * @return void
     */
    public function testConnectorActions(): void
    {
        $connector = $this->getConnector("faker");
        $this->assertInstanceOf(AbstractConnector::class, $connector);

        $this->assertPublicActionWorks($connector, null);
        $this->assertPublicActionWorks($connector, "master");
        $this->assertPublicActionWorks($connector, "index");
        $this->assertPublicActionWorks($connector, "validate");
        $this->assertPublicActionWorks($connector, "invalidate");
        $this->assertPublicActionFail($connector, "fail");
    }
}
