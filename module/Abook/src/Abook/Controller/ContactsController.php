<?php

namespace Abook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Abook\Form\ContactsForm;
use Abook\Entity\Contacts;

class ContactsController extends AbstractActionController {

    private $contactTable;

    public function indexAction() {
        $result = $this->getContactsTable()->fetchAll();

        return new ViewModel(array(
            'contacts' => $result
        ));
    }

    private function createAddForm(Contacts $entity) {

        $form = new ContactsForm("add_contact");

        $form->bind($entity);

        $form->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'id' => 'submit',
                'value' => 'Save',
                'class' => 'btn btn-primary'
            )
        ));

        return $form;
    }

    public function createAction(ContactsForm $form) {

        $request = $this->getRequest();

        if ($request->isPost()) {

            $entity = new Contacts();

            $form = $this->createAddForm($entity);

            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $entity = $form->getData();
                $this->getContactsTable()->create($entity);
                $this->redirect()->toRoute("contact-list");
            } 
                        
        }
        
        return $form;
    }

    public function addAction() {

        $addForm = $this->createAddForm(new Contacts());

        $addForm = $this->createAction($addForm);

        return array("form" => $addForm);
    }

    private function createEditForm($id) {
        
        $contact = $this->getContactsTable()->fetchById($id);

        $form = new ContactsForm("edit_contact");
                
        $form->bind($contact);

        $form->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'id' => 'submit',
                'value' => 'Update',
                'class' => 'btn btn-primary'
            )
        ));

        return $form;
    }

    public function updateAction(ContactsForm $form) {

        $request = $this->getRequest();
        
        if($request->isPost()){
            
            $form->setData($request->getPost());
            
            if($form->isValid()){
                $entity = $form->getData();
                $this->getContactsTable()->update($entity);
                $this->redirect()->toRoute("contact-list");
            }
            
        }
        
        return $form;
    }

    public function editAction() {

        $id = (int)$this->params("id");

        $editForm = $this->createEditForm($id);

        $editForm = $this->updateAction($editForm);
        
        return array(
            "form" => $editForm,
            "id" => $id
        );
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
