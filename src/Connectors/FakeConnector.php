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

namespace Splash\Connectors\Faker\Connectors;

use ArrayObject;
use Psr\Log\LoggerInterface;
use Splash\Bundle\Interfaces\Connectors\PrimaryKeysInterface;
use Splash\Bundle\Models\AbstractConnector;
use Splash\Client\Splash;
use Splash\Connectors\Faker\Actions;
use Splash\Connectors\Faker\Dictionary\FakeObjectsTypes;
use Splash\Connectors\Faker\Dictionary\FakeWidgetsTypes;
use Splash\Connectors\Faker\Form\FakerConfigForm;
use Splash\Connectors\Faker\Objects\Generic as GenericObject;
use Splash\Connectors\Faker\Widgets\Generic as GenericWidget;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
#[Autoconfigure(
    bind: array(
        '$genericObject' => "@splash.connector.faker.object",
        '$genericWidget' => "@splash.connector.faker.widget",
    )
)]
class FakeConnector extends AbstractConnector implements PrimaryKeysInterface
{
    const NAME = 'faker';

    public function __construct(
        private GenericObject $genericObject,
        private GenericWidget $genericWidget,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        parent::__construct($eventDispatcher, $logger);
    }

    /**
     * @inheritDoc
     */
    public function ping(): bool
    {
        //====================================================================//
        // Safety Check => Verify Self test Pass
        if (!$this->selfTest()) {
            return false;
        }

        Splash::log()->msg("Faker Ping Always pass...");

        return true;
    }

    /**
     * @inheritDoc
     */
    public function connect(): bool
    {
        //====================================================================//
        // Safety Check => Verify Self test Pass
        if (!$this->selfTest()) {
            return false;
        }

        Splash::log()->msg("Faker Connect Always pass...");

        return true;
    }

    /**
     * @inheritDoc
     */
    public function informations(ArrayObject $informations): ArrayObject
    {
        //====================================================================//
        // Init Response Object
        $response = $informations;

        //====================================================================//
        // Company Informations
        $response->company = "Fake Company";
        $response->address = "123 testing road";
        $response->zip = "33666";
        $response->town = "Hazard City";
        $response->country = "TestLands";
        $response->www = "www.fake.com";
        $response->email = "faker@splashsync.com";
        $response->phone = "+33612345678";

        //====================================================================//
        // Server Icon
        $response->icoraw = Splash::File()->ReadFileContents(
            (dirname(__DIR__).'/Resources/public/splash-faker-ico.png')
        );
        //====================================================================//
        // Server Logo & Images
        $response->logoraw = Splash::File()->ReadFileContents(
            (dirname(__DIR__).'/Resources/public/splash-faker-ico.png')
        );

        //====================================================================//
        // Server Informations
        $response->servertype = 'Splash Faker';
        $response->serverurl = filter_input(INPUT_SERVER, 'SERVER_NAME')
            ?: 'localhost:8000'
        ;

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function selfTest(): bool
    {
        Splash::log()->deb("Faker SelfTest Always pass...");

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getFile(string $filePath, string $fileMd5): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getAvailableObjects(): array
    {
        return FakeObjectsTypes::getAll();
    }

    /**
     * @inheritDoc
     */
    public function getObjectDescription(string $objectType): array
    {
        return $this->getGenericObject($objectType)->description();
    }

    /**
     * @inheritDoc
     */
    public function getObjectFields(string $objectType): array
    {
        return $this->getGenericObject($objectType)->fields();
    }

    /**
     * @inheritDoc
     */
    public function getObjectList(string $objectType, string $filter = null, array $params = array()): array
    {
        return $this->getGenericObject($objectType)->objectsList($filter, $params);
    }

    /**
     * @inheritDoc
     */
    public function getObject(string $objectType, $objectIds, array $fieldsList): ?array
    {
        // Single Object reading
        if (is_string($objectIds)) {
            return $this->getGenericObject($objectType)->get($objectIds, $fieldsList);
        }
        // Multiple Objects reading
        $data = array();
        foreach ($objectIds as $objectId) {
            $data[$objectId] = $this->getGenericObject($objectType)->get($objectId, $fieldsList);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function setObject(string $objectType, string $objectId = null, array $objectData = array()): ?string
    {
        return $this->getGenericObject($objectType)->set($objectId, $objectData);
    }

    /**
     * @inheritDoc
     */
    public function deleteObject(string $objectType, string $objectId): bool
    {
        return $this->getGenericObject($objectType)->delete($objectId);
    }

    /**
     * @inheritDoc
     */
    public function getObjectIdByPrimary(string $objectType, array $keys): ?string
    {
        return $this->getGenericObject($objectType)->getByPrimary($keys);
    }

    /**
     * @inheritDoc
     */
    public function getProfile(): array
    {
        return array(
            'enabled' => true,                                  // is Connector Enabled
            'beta' => true,                                     // is this a Beta release
            'type' => self::TYPE_ACCOUNT,                       // Connector Type or Mode
            'name' => self::NAME,                               // Connector code (lowercase, no space allowed)
            'connector' => 'splash.connectors.faker',           // Connector PUBLIC service
            'title' => 'Symfony Standalone Connector',          // Public short name
            'label' => 'Fake Connector for Testing',            // Public long name
            'domain' => "SplashFaker",                          // Translation domain for names
            'ico' => 'bundles/faker/splash-faker-ico.png',      // Public Icon path
            'www' => 'www.splashsync.com',                      // Website Url
        );
    }

    /**
     * @inheritDoc
     */
    public function getConnectedTemplate(): string
    {
        return "@Faker/connected.html.twig";
    }

    /**
     * @inheritDoc
     */
    public function getOfflineTemplate(): string
    {
        return "@Faker/offline.html.twig";
    }

    /**
     * @inheritDoc
     */
    public function getNewTemplate(): string
    {
        return "@Faker/new.html.twig";
    }

    /**
     * @inheritDoc
     */
    public function getFormBuilderName(): string
    {
        return FakerConfigForm::class;
    }

    /**
     * @inheritDoc
     */
    public function getMasterAction(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPublicActions(): array
    {
        return array(
            "index" => Actions\Master::class,
            "master" => Actions\Master::class,
            "validate" => Actions\Validate::class,
            "invalidate" => Actions\Invalidate::class,
            "fail" => Actions\Fail::class,
        );
    }

    /**
     * @inheritDoc
     */
    public function getSecuredActions(): array
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function getAvailableWidgets(): array
    {
        return FakeWidgetsTypes::getAll();
    }

    /**
     * @inheritDoc
     */
    public function getWidgetDescription(string $widgetType): array
    {
        return $this->getGenericWidget($widgetType)->description();
    }

    /**
     * @inheritDoc
     */
    public function getWidgetContents(string $widgetType, array $params = array()): ?array
    {
        return $this->getGenericWidget($widgetType)->get($params);
    }

    /**
     * Get Faker Generic Object
     */
    public function getGenericObject(string $objectType): GenericObject
    {
        return $this->genericObject
            ->configure($objectType, $this->getWebserviceId(), array())
        ;
    }

    /**
     * Get Faker Generic Object
     */
    public function getGenericWidget(string $widgetType): GenericWidget
    {
        return $this->genericWidget
            ->configure($widgetType, $this->getWebserviceId(), array())
        ;
    }
}
