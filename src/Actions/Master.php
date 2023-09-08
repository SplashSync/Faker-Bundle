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

namespace Splash\Connectors\Faker\Actions;

use Splash\Bundle\Models\Local\ActionsTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Splash Faker Master Controller Action
 */
class Master extends AbstractController
{
    use ActionsTrait;

    /**
     * Master Fake Controller Action
     *
     * @return Response
     */
    public function __invoke()
    {
        //====================================================================//
        // Return Dummy Response
        return new JsonResponse(array('result' => 'Ok'));
    }
}
