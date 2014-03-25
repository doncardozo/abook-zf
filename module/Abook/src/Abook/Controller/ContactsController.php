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
        
        $form = new ContactsForm("add_contact", $this->getContactsModel());
        
        $form->get("contactType")->setValue(2);
        
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

            $contact = new Contacts();

            $form = $this->createAddForm($contact);

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $contact = $form->getData();
                $this->getContactsModel()->create($contact);
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

        $contact = new Contacts();        
        $contact->hydrate($this->getContactsModel()->fetchById($id));
        
        $form = new ContactsForm("edit_contact", $this->getContactsModel());

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
