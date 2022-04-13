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

namespace Splash\Connectors\Faker\Controller;

use Splash\Bundle\Models\AbstractConnector;
use Splash\Bundle\Models\Local\ActionsTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Splash Faker Connector Actions Controller
 */
class ActionsController extends AbstractController
{
    use ActionsTrait;

    /**
     * Master Fake Controller Action
     *
     * @return Response
     */
    public function masterAction()
    {
        //====================================================================//
        // Return Dummy Response
        return new JsonResponse(array('result' => 'Ok'));
    }

    /**
     * Index Fake Controller Action
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
     * @param Request           $request
     * @param AbstractConnector $connector
     *
     * @return Response
     */
    public function validateAction(Request $request, AbstractConnector $connector)
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

    /**
     * Invalidate Fake Controller Action
     *
     * @param Request           $request
     * @param AbstractConnector $connector
     *
     * @return Response
     */
    public function invalidateAction(Request $request, AbstractConnector $connector)
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
        if (empty($referer)) {
            return self::getDefaultResponse();
        }

        return new RedirectResponse($referer);
    }

    /**
     * Fail Test Fake Controller Action
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
