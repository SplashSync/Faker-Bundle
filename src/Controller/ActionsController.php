<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2018 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\FakerBundle\Controller;

use Splash\Bundle\Interfaces\ConnectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @abstract    Splash Faker Connector Actions Controller
 */
class ActionsController extends Controller
{
    /**
     * @abstract    Index Fake Controller Action
     *
     * @return Response
     */
    public function indexAction()
    {
        //====================================================================//
        // Return Dummy Response
        return new JsonResponse(array('result' => 'Ok'));
    }

    /**
     * @abstract    Validate Fake Controller Action
     *
     * @param Request            $request
     * @param ConnectorInterface $connector
     *
     * @return Response
     */
    public function validateAction(Request $request, ConnectorInterface $connector)
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

        return new RedirectResponse($referer);
    }

    /**
     * @abstract    Invalidate Fake Controller Action
     *
     * @param Request            $request
     * @param ConnectorInterface $connector
     *
     * @return Response
     */
    public function invalidateAction(Request $request, ConnectorInterface $connector)
    {
        //====================================================================//
        // If Currently Offline
        if ($connector->getParameter('faker_validate_connect', false)) {
            $connector->setParameter('faker_validate_connect', false);
        //====================================================================//
        // If Currently NEW
        } elseif ($connector->getParameter('faker_validate_selftest', false)) {
            $connector->setParameter('faker_validate_selftest', false);
        }
        //====================================================================//
        // Update Configuration
        $connector->updateConfiguration();
        //====================================================================//
        // Redirect Response
        /** @var string $referer */
        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }

    /**
     * @abstract    Fail Test Fake Controller Action
     *
     * @return Response
     */
    public function failAction()
    {
        //====================================================================//
        // Return Dummy Response
        return new JsonResponse(array('result' => 'Ko'), 500);
    }
}
