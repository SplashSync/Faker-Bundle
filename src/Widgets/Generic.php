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

namespace Splash\Connectors\Faker\Widgets;

use Splash\Bundle\Models\AbstractStandaloneWidget;
use Splash\Core\SplashCore as Splash;

/**
 * SelfTest Template Widget for Splash Standalone Connector
 */
class Generic extends AbstractStandaloneWidget
{
    /**
     * {@inheritdoc}
     */
    public static array $options = array(
        'Width' => self::SIZE_DEFAULT,
        'UseCache' => true,
        'CacheLifeTime' => 1,
    );

    /**
     * {@inheritdoc}
     */
    protected static string $name = 'Faker Generic Widget';

    /**
     * {@inheritdoc}
     */
    protected static string $description = 'Fake Widgets for Démo';

    /**
     * {@inheritdoc}
     */
    protected static string $ico = 'fa fa-user-secret';

    //====================================================================//
    // Class Main Functions
    //====================================================================//

    /**
     * Return Widget Customs Parameters
     *
     * @return array
     */
    public function getParameters(): array
    {
        switch ($this->getSplashType()) {
            case 'Morris':
                //====================================================================//
                // Select Chart Rendering Mode
                $this->fieldsFactory()->create(SPL_T_TEXT)
                    ->identifier('chart_type')
                    ->name('Rendering Mode')
                    ->isRequired()
                    ->addChoice('Line', 'Line Chart')
                    ->addChoice('Bar', 'Bar Chart')
                    ->addChoice('Area', 'Area Chart')
                ;

                break;
            default:
                break;
        }

        //====================================================================//
        // Publish Fields
        return $this->fieldsFactory()->publish() ?? array();
    }

    /**
     * Return requested Customer Data
     *
     * @param array $params Widget Inputs Parameters
     *
     * @return array
     */
    public function get($params = null): array
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();

        //====================================================================//
        // Setup Widget Core Informations
        //====================================================================//

        $this->setTitle($this->getName());
        $this->setIcon($this->getIcon());

        //====================================================================//
        // Build Blocks
        //====================================================================//
        switch ($this->getSplashType()) {
            case 'Empty':
                break;
            case 'Text':
                $this->buildTextBlock();

                break;
            case 'Notifications':
                $this->buildNotificationsBlock();

                break;
            case 'Morris':
                $this->buildMorrisBlock($params);

                break;
        }

        //====================================================================//
        // Set Blocks to Widget
        $blocks = $this->blocksFactory()->render();
        if ($blocks) {
            $this->setBlocks($blocks);
        }

        //====================================================================//
        // Publish Widget
        return $this->render();
    }

    //====================================================================//
    // Class Tooling Functions
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'Faker '.$this->getSplashType().' Widget';
    }

    //====================================================================//
    // Blocks Generation Functions
    //====================================================================//

    /**
     * Block Building - Text Demo Widget
     *
     * @return void
     */
    private function buildTextBlock()
    {
        //====================================================================//
        // Into Text Block
        $this->blocksFactory()->addTextBlock('This widget show only TEXT!!');
    }

    /**
     * Block Building - Notifications Demo Widget
     *
     * @return void
     */
    private function buildNotificationsBlock()
    {
        //====================================================================//
        // If test was passed
        $this->blocksFactory()->addNotificationsBlock(array(
            'info' => 'Just for information',
            'success' => 'Success Message!',
            'warning' => "Yes I'm a warning message!",
            'error' => 'This is not an Error...',
        ));
    }

    /**
     * Block Building - Chart Demo Widget
     *
     * @param null|array $params
     *
     * @return void
     */
    private function buildMorrisBlock(array $params = null)
    {
        $next = rand(0, 100);
        $next2 = rand(0, 100);
        $values = array();
        for ($i = 1; $i < 25; ++$i) {
            $values[] = array(
                'label' => '2017 W'.$i,
                'value' => $next,
                'value2' => $next2,
            );
            $next += rand(-50, 50);
            $next2 += rand(-50, 50);
        }
        //====================================================================//
        // Detect Mode
        $mode = $params['chart_type'] ?? 'Line';
        //====================================================================//
        // Morris Block
        $this->blocksFactory()->addMorrisGraphBlock($values, $mode);
    }
}
