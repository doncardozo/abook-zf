<?php

namespace Abook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactsController extends AbstractActionController
{

    public function indexAction()
    {        
        $contacts = $this->getServiceLocator()->get("Abook\Model\Contacts");
        
        $result = $contacts->getContacts();
        
        #z$contactsEmails = $contacts->getContactsEmails();
        
        return new ViewModel(array(
            'contacts' => $result
        ));
    }
    
    public function addAction(){
        
    }
    
    public function editAction(){
        
    }
    
    public function showAction(){
        
    }


}

