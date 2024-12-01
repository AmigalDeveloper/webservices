<?php

/**
 * Joomla! Framework Website
 *
 * @copyright  Copyright (C) 2014 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Amigal\Webservice\Controller;

use Joomla\Controller\AbstractController;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\TextResponse;

/**
 * Controller class to display a message to individuals looking for the wrong CMS
 *
 * @method         \Amigal\Webservice\WebApplication  getApplication()  Get the application object.
 * @property-read  \Amigal\Webservice\WebApplication  $app              Application object
 */
class WrongCmsController extends AbstractController
{
    /**
     * Execute the controller.
     *
     * @return  boolean
     */
    public function execute(): bool
    {
        // Enable browser caching
        $this->getApplication()->allowCache(true);
        $data = [
            'code' => 404,
            'message' => "This isn't the CMS you're looking for."
        ];
        $response = new JsonResponse($data, 404, [],JSON_UNESCAPED_UNICODE);
        $this->getApplication()->setResponse($response);
        return true;
    }
}
