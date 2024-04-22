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
 * List of Available Fake Objects Types
 */
class FakeObjectsTypes
{
    /**
     * Just a very Small Object for Basic Tests
     */
    const SHORT = "short";

    /**
     * Simple Object for Functional Tests
     */
    const SIMPLE = "simple";

    /**
     * Object with List Fields
     */
    const LIST = "list";

    /**
     * Small Object with Primary Keys Management
     */
    const PRIMARY = "primary";

    /**
     * Small Object with Link to Multiple Objects Types
     */
    const OBJECTS = "objects";

    /**
     * Small Object with Image
     */
    const IMAGE = "image";

    /**
     * Small Object with File
     */
    const FILE = "file";

    /**
     * Small Object with Stream
     */
    const STREAM = "stream";

    /**
     * Small Object with Changes Tracking
     */
    const TRACKING = "tracking";

    /**
     * Get List of All Available Objects Types
     *
     * @return string[]
     */
    public static function getAll(): array
    {
        return array(
            self::SHORT,
            self::SIMPLE,
            self::LIST,
            self::OBJECTS,
            self::PRIMARY,
            self::IMAGE,
            self::FILE,
            self::STREAM,
            self::TRACKING,
        );
    }
}
