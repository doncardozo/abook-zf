<?php

namespace Book\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Book\Form\ContactForm;
use Book\Model\Contact;

class ContactController extends AbstractActionController {

    protected $contactTable;

    public function indexAction() {
        return new ViewModel();
    }

    public function addAction() {
        
        $form = new ContactForm('fadd');
        $form->get("submit")->setValue("Add");
        
        $request = $this->getRequest();
        
        if($request->isPost()){
            
            $contact = new Contact();
            $form->setInputFilter($contact->getInputFilter());
            $form->setData($request->getPost());
            
            if($form->isValid()){
                $contact->exchangeArray($form->getData());
                $this->getContactTable()->saveContact($contact);
                
                return $this->redirect()->toRoute("contact-list");
            }
            
        }
        
        return array("form" => $form);        
    }

    public function listAction() {
        return new ViewModel(array(
            'contacts' => $this->getContactTable()->fetchAll(),
        ));        
    }
    
    public function editAction() {
        return new ViewModel();
    }

    public function deleteAction() {
        return new ViewModel();
    }
    
    public function getContactTable(){
        if (!$this->contactTable) {
            $sm = $this->getServiceLocator();
            $this->contactTable = $sm->get('Book\Model\ContactTable');
        }
        return $this->contactTable;
    }    

}
