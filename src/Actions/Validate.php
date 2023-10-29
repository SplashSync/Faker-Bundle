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

use Splash\Bundle\Models\AbstractConnector;
use Splash\Bundle\Models\Local\ActionsTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Splash Faker Connector Actions Controller
 */
class Validate extends AbstractController
{
    use ActionsTrait;

    /**
     * Validate Fake Controller Action
     *
     * @param Request           $request
     * @param AbstractConnector $connector
     *
     * @return Response
     */
    public function __invoke(Request $request, AbstractConnector $connector)
    {
        //====================================================================//
        // If Currently NEW
        if (!$connector->getParameter('faker_validate_selftest', false)) {
            $connector->setParameter('faker_validate_selftest', true);
        //====================================================================//
        // If Currently Offline
        } elseif (!$connector->getParameter('faker_validate_connect', false)) {
            $connector->setParameter('faker_validate_connect', true);
        }
        //====================================================================//
        // Update Configuration
        $connector->updateConfiguration();
        //====================================================================//
        // Redirect Response
        /** @var string $referer */
        $referer = $request->headers->get('referer');
        if (empty($referer)) {
            return self::getDefaultResponse();
        }

        return new RedirectResponse($referer);
    }
}
