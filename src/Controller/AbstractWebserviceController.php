<?php
/**
* Webservice Controller
* 
* @copyright  Copyright (C) 2014 - 2017 Open Source Matters, Inc. All rights reserved.
* @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
*/

namespace Webservice\Controller;

use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\AbstractController;
use Joomla\Input\Input;
use Joomla\Input\Json;
use Joomla\View\JsonView;


/**
 * Webservice Controller
 */
class AbstractWebserviceController extends AbstractController
{
    /**
     * View object
     * 
     * @var \Joomla\View\JsonView
     */

    private $view;
public function __construct(JsonView $view, Json $input, AbstractWebApplication $app){
    $this->view = $view;
    parent::__construct($input, $app);
}

public function execute():bool {
    $method = $this->getInput()->getMethod();
    $data = $this->getInput()->getData();
    $this->view->$method($data);




        // Disable browser caching
        $this->getApplication()->allowCache(false);

        // This is a JSON response
        $this->getApplication()->mimeType = 'application/json';
        $this->getApplication()->setBody($this->view->render());
        return true;

}

}
?>