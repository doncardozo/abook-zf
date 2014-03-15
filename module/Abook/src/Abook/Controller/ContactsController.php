<?php

namespace Abook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Abook\Form\CreateContacts;
use Abook\Entity\Contacts;

class ContactsController extends AbstractActionController {

    private $contactTable;

    public function indexAction() {
        $result = $this->getContactsTable()->fetchAll();

        return new ViewModel(array(
            'contacts' => $result
        ));
    }

    public function addAction() {

        $form = new CreateContacts();
        $contacts = new Contacts();
        
        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                var_dump($contacts);
            }
        }
        
        return array("form" => $form);
    }

    public function editAction() {
        
    }

    public function showAction() {
        
    }

    private function getContactsTable() {

        if (is_null($this->contactTable)) {
            $this->contactTable = $this->getServiceLocator()
                    ->get("Abook\Model\ContactsTable");
        }
        return $this->contactTable;
    }

}
