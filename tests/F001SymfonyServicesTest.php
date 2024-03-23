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
use Splash\Connectors\Faker\Dictionary\FakeObjectsTypes;
use Splash\Connectors\Faker\Dictionary\FakeWidgetsTypes;
use Splash\Tests\Tools\TestCase;

class F001SymfonyServicesTest extends TestCase
{
    /**
     * Test Declaration of Splash Objects
     *
     * @dataProvider connectorIdProvider
     *
     * @throws Exception
     */
    public function testSplashObjectsExists(string $connectorId): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector($connectorId);
        $this->assertInstanceOf(AbstractConnector::class, $connector);

        //====================================================================//
        // Verify All Objects Types are Available
        $objectTypes = $connector->getAvailableObjects();
        foreach (FakeObjectsTypes::getAll() as $objectType) {
            $this->assertContains($objectType, $objectTypes);
        }
    }

    /**
     * Test Declaration of Splash Objects
     *
     * @dataProvider connectorIdProvider
     *
     * @throws Exception
     */
    public function testSplashWidgetsExists(string $connectorId): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector($connectorId);
        $this->assertInstanceOf(AbstractConnector::class, $connector);

        //====================================================================//
        // Verify All Widgets Types are Available
        $widgetTypes = $connector->getAvailableWidgets();
        foreach (FakeWidgetsTypes::getAll() as $widgetType) {
            $this->assertContains($widgetType, $widgetTypes);
        }
    }

    /**
     * Test Declaration of Connector Actions
     *
     * @dataProvider connectorIdProvider
     *
     * @throws Exception
     */
    public function testConnectorActionsExists(string $connectorId): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector($connectorId);
        $this->assertInstanceOf(AbstractConnector::class, $connector);

        //====================================================================//
        // Verify All Objects Types are Available
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
     * @dataProvider connectorIdProvider
     */
    public function testConnectorActions(string $connectorId): void
    {
        $connector = $this->getConnector($connectorId);
        $this->assertInstanceOf(AbstractConnector::class, $connector);

        $this->assertPublicActionWorks($connector, null);
        $this->assertPublicActionWorks($connector, "master");
        $this->assertPublicActionWorks($connector, "index");
        $this->assertPublicActionWorks($connector, "validate");
        $this->assertPublicActionWorks($connector, "invalidate");
        $this->assertPublicActionFail($connector, "fail");
    }

    /**
     * Configured Connectors Names
     */
    public function connectorIdProvider(): array
    {
        return array(
            "Standalone" => array("standalone"),
            "Connector" => array("connector"),
        );
    }
}
