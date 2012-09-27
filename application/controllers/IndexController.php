<?php

/**
 * Home page of rememberthemilk-jquerymobile.
 * @author Jeroen Franse <jroenf@github>
 */
class IndexController extends Zend_Controller_Action
{
    /**
     *
     * @var Service layer that provides access to relevant models.
     */
    protected $service;
    
    /**
     * Set up the controller before the right action is performed
     */
    public function init()
    {
        $this->service = new Application_Service_Rtm();
    }
    
    /**
     * Shows homepage of rtm-jqm project
     * Gets lists and smartlists from feed and displays both.
     */
    public function indexAction() 
    {
       $this->view->listNames = $this->service->getListNames();
       $lists = array();
       foreach ( $this->view->listNames as $listName ) {
           $lists[$listName] = $this->service->getTasksFromList($listName);
       }
       
       $smartListNames = $this->service->getSmartlistNames();
       $lists = array();
       foreach ( $smartListNames as $listName ) {
           $lists[$listName] = $this->service->getTasksFromList($listName);
       }
       
       // Add these values (array's) to the view:
       $this->view->smartlistNames = $smartListNames;
       $this->view->lists = $lists;
       $this->view->smartlists = $lists;
    }
    /**
     * Shows all tasks in one 
     */
    public function listAction() {
        $id = $this->_getParam('id', null);
        
        $this->view->listname = $id;
        $this->view->tasks = $this->service->getTasksFromList($id);
    }
    
    /**
     * This is just for debugging purposes
     * ToDo: delete this Action
     */
    public function zendfeedAction() {
       $feedUrls = $this->service->getFeedUrls();
       $atomfeed = Zend_Feed_Reader::import($feedUrls['inbox']);
       
       $feedReader = Zend_Feed_Reader::import($atomfeed);
       //print_r($feedReader->getFeedLink());exit();
    }


}

