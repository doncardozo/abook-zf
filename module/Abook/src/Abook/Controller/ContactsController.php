<?php

namespace Abook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Abook\Form\ContactsForm;


class ContactsController extends AbstractActionController
{

    private $contactTable;
    
    public function indexAction()
    {        
        $result = $this->getContactsTable()->fetchAll();
        
        return new ViewModel(array(
            'contacts' => $result
        ));        
    }
    
    public function addAction(){
        
        $form = new ContactsForm("add-form");
        
        $request = $this->getRequest();
        
        if($request->isPost()){
            
            $contactFilter = new ContactsFilter();
			$form->setInputFilter($contactFilter->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid()){
				$contactFilter->exchangeArray($form->getData());
				$this->getContactsTable();
	            return $this->redirect()->toRoute('contact-list');
			}

            return $this->redirect()->toRoute('contact-add');
        }
        
        return array( "form" => $form );        
    }
    
    public function editAction(){
        
    }
    
    public function showAction(){
        
    }
    
    
    private function getContactsTable(){
        
        if(is_null($this->contactTable)){
            $this->contactTable = $this->getServiceLocator()
                                  ->get("Abook\Model\ContactsTable");
        }
        return $this->contactTable;
    }


}

