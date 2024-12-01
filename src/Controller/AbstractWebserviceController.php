<?php
/**
 * Webservice Controller
 * 
 * @copyright  Copyright (C) 2014 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Amigal\Webservice\Controller;

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
     * @var \Amigal\Webservice\View\WebserviceJsonView
     */

    private $view;
    public function __construct(\Amigal\Webservice\View\WebserviceJsonView $view, Input $input, AbstractWebApplication $app)
    {
        $this->view = $view;
        parent::__construct($input, $app);
    }

    public function execute(): bool
    {
        $this->getApplication()->getLogger()->debug(get_class($this) ." getRaw ",   [$this->getInput()->get('data','ingen data')]);
        $method = $this->getInput()->getMethod();
        $this->getApplication()->getLogger()->debug(get_class($this) . ": method ", [$method]);
        $input = $this->getInput();
        $data = $input->getRaw();
        $this->getApplication()->getLogger()->debug(get_class($this) ." getRaw ",   [$data]);
        $this->getApplication()->getLogger()->debug(get_class($this) ."Type af data",  [ gettype($data)]);
        
        $dataDeJson =json_decode(
            $data,
            true,
            512,
            JSON_OBJECT_AS_ARRAY| 
            JSON_UNESCAPED_UNICODE | 
            JSON_UNESCAPED_SLASHES | 
            JSON_NUMERIC_CHECK | 
            JSON_UNESCAPED_LINE_TERMINATORS
        );
        
        $this->getApplication()->getLogger()->debug(get_class($this) ."Type dataDeJson", [ gettype($dataDeJson),$dataDeJson]);
        $this->getApplication()->getLogger()->debug(get_class($this) . ": rawInput ", [$input->get('more data', 'flere data','raw')]);
        $this->getApplication()->getLogger()->debug(get_class($this) . ": rawInput data ", [$input->get('data', 'ingen data','raw')]);



        // if(!is_array($data)){
        //     $data = [];
        // }
        $this->getApplication()->getLogger()->debug(get_class($this) . " Data", [$data]);

        // Disable browser caching
        $this->getApplication()->allowCache(false);

        // This is a JSON response
        $this->getApplication()->mimeType = 'application/json';
        $this->getApplication()->setBody($this->view->render($method, $dataDeJson));
        return true;

    }
}