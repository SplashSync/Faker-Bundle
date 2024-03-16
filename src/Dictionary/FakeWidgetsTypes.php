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

namespace Splash\Connectors\Faker\Dictionary;

/**
 * List of Available Fake Widget Types
 */
class FakeWidgetsTypes
{
    /**
     * An Empty Widget
     */
    const EMPTY = "Empty";

    /**
     * Simple Text Widget
     */
    const TEXT = "Text";

    /**
     * Notifications Widget
     */
    const NOTIFICATION = "Notifications";

    /**
     * Basic Chart Widget
     */
    const CHART = "Morris";

    /**
     * Get List of All Available Widgets Types
     *
     * @return string[]
     */
    public static function getAll(): array
    {
        return array(
            self::EMPTY,
            self::TEXT,
            self::NOTIFICATION,
            self::CHART,
        );
    }
}
