<?php
/**
 * Webservice
 * 
 * @copyright  Copyright (C) 2014 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

 namespace Webservice\View;

 use Joomla\View\JsonView;
 use Webservice\Model\AbstractWebserviceModel;
 
 /**
  * Webservice View class
  * 
  */
  class WebserviceJsonView extends JsonView
  {
    /**
     * WebservicesModel object
     *@var $model 
     */

     private $model;


    /**
     * constructor 
     * @param AbstractWebServiceModel
     */
    public function __construct(AbstractWebserviceModel $model){
        $this->model = $model;  
    }

    public function getModel(){
        return $this->model;
  }
}
