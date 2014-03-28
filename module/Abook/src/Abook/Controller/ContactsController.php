<?php

namespace Abook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Abook\Form\ContactsForm;
use Abook\Entity\Contacts;

class ContactsController extends AbstractActionController {

    private $contactModel;

    public function indexAction() {
        $result = $this->getContactsModel()->fetchAll();

        return new ViewModel(array(
            'contacts' => $result
        ));
    }

    private function createAddForm() {
        
        $form = new ContactsForm("add_contact", $this->getServiceLocator());
        
        $form->get("contactType")->setValue(1);
        
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

            $form = $this->createAddForm();

            $form->setData($request->getPost());

            if ($form->isValid()) {                              
                $this->getContactsModel()->create($form->getData());
                $this->redirect()->toRoute("contact-list");
            }
        }

        return $form;
    }

    public function addAction() {

        $addForm = $this->createAddForm();

        $addForm = $this->createAction($addForm);

        return array("form" => $addForm);
    }

    private function createEditForm($id) {

        $contactData = $this->getContactsModel()->fetchById($id);
        
        $contact = new Contacts();        
        $contact->hydrate($contactData["contacts"]);                 
        $contact->setEmails($contactData["emails"]);      
        $contact->setPhones($contactData["phones"]);
        
        $form = new ContactsForm("edit_contact", $this->getServiceLocator());
        
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

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $contact = $form->getData();
                $this->getContactsModel()->update($contact);
                $this->redirect()->toRoute("contact-list");
            }
        }

        return $form;
    }

    public function editAction() {

        $id = (int) $this->params("id");

        $editForm = $this->createEditForm($id);

        $editForm = $this->updateAction($editForm);

        return array(
            "form" => $editForm,
            "id" => $id
        );
    }

    public function showAction() {
        
    }

    private function getContactsModel() {

        if (is_null($this->contactModel)) {
            $this->contactModel = $this->getServiceLocator()
                    ->get("Abook\Model\ContactsModel");
        }
        return $this->contactModel;
    }

}
