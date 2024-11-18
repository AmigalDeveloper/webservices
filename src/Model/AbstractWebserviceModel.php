<?php
/**
 * Webservice
 * 
 * @copyright  Copyright (C) 2014 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

 namespace Webservice\Model;

 use Joomla\Database\DatabaseDriver;
 use Joomla\Model\DatabaseModelInterface;
 use Joomla\Model\DatabaseModelTrait;

 /**
  * Webservice Abstract Databasemodel
  */
  class AbstractWebserviceModel implements DatabaseModelInterface
  {
    use DatabaseModelTrait;

    /**
     * Instantiate the model.
     *
     * @param   DatabaseDriver  $db  The database adapter.
     */
    public function __construct(DatabaseDriver $db)
    {
        $this->setDb($db);
        
    }

    function getData(){
        return $this->db->select("*")->from("")->where("", $this->id)->get()->result();
    }
    
    function postData(){
        return $this->db->select("*")->from("")->where("", $this->id)->get()->result();
    }

    function putData(){
        return $this->db->select("*")->from("")->where("",  $this->id)->update(array(""=> $this->id));  
    }

    function deleteData($id){
        return $this->db->delete("", array( ""=> $id))->where("",   $this->id)->delete(); 
    }
  }
