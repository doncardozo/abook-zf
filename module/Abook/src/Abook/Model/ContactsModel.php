<?php

namespace Abook\Model;

use Abook\Toolbox\Db\AbstractAdapterManager;
use Abook\Toolbox\Db\ResultSetManager;
use Zend\Db\TableGateway\TableGateway;
use Abook\Service\EmailsPhonesControl;

class ContactsModel extends AbstractAdapterManager {

    /**
     * Fetch all Contacts
     * @return type
     */
    public function fetchAll() {

        $select = <<<SQL
            select 
                c.id, 
                c.first_name firstName, 
                c.last_name lastName,
                c.address,
                ct.name contactType,
                c.active
            from contacts c 
                inner join contact_type ct on c.contact_type_id = ct.id
            where 
                c.deleted = 0
            order by 1 desc;
SQL;

        $stmt = $this->getDbAdapter()->createStatement($select);
        $stmt->prepare();
        $result = $stmt->execute();

        $rs = new ResultSetManager();
        return $rs->getResultSet($result);
    }

    /**
     * Fetch Contact by Id
     * @param type $id
     * @return type
     */
    public function fetchById($id) {

        $select = <<<SQL
            select 
                id, 
                first_name firstName, 
                last_name lastName,
                address,
                contact_type_id contactType,
                active
            from contacts            
            where id = {$id} and deleted = 0;
            
            select 
                id,
                email
            from contacts_emails
            where contact_id = {$id} and deleted = 0;
            
            select 
                id,
                phone_number phoneNumber
            from contacts_phones
            where contact_id = {$id} and deleted = 0;
SQL;


        $result = $this->getConnection()->execute($select);
        $statement = $result->getResource();

        $resultSet = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $contacts["contacts"] = (sizeof($resultSet) > 0) ? array_pop($resultSet) : array();

        $statement->nextRowset();
        $resultSet = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $contacts["contacts"]["emails"] = (sizeof($resultSet) > 0) ? $resultSet : array();

        $statement->nextRowset();
        $resultSet = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $contacts["contacts"]["phones"] = (sizeof($resultSet) > 0) ? $resultSet : array();

        return $contacts;
    }

    /**
     * Get Contact Type
     * @return type
     */
    public function getContactType() {

        $select = <<<SQL
            select 
                id, 
                name
            from contact_type
            where 
                active = 1 and deleted = 0;
SQL;

        $stmt = $this->getDbAdapter()->createStatement($select);
        $stmt->prepare();
        $result = $stmt->execute();

        $rs = new ResultSetManager();

        $result = $rs->getResultSet($result);

        return $result;
    }

    /**
     * Create Contacts
     * @param \Abook\Entity\Contacts $contact
     */
    public function create(\Abook\Entity\Contacts $contact) {

        $data = array(
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'address' => $contact->getAddress(),
            'contact_type_id' => $contact->getContactType(),
            'active' => $contact->getActive()
        );

        $emailData = $contact->getEmails();

        $phoneData = $contact->getPhones();

        $connection = $this->getConnection()->beginTransaction();

        try {

            $contactsTable = new TableGateway("contacts", $this->getDbAdapter());

            $contactsTable->insert($data);

            $id = $contactsTable->getLastInsertValue();

            if (is_array($emailData) && sizeof($emailData) > 0) {
                $emailsTable = new TableGateway("contacts_emails", $this->getDbAdapter());
                $arrayPrototype = array(
                    "id" => "id",
                    "field_name" => "email",
                    "method_name" => "getEmail"
                );
                $this->insertEP($emailsTable, $emailData, $arrayPrototype, $id);
            }

            if (is_array($phoneData) && sizeof($phoneData) > 0) {
                $phonesTable = new TableGateway("contacts_phones", $this->getDbAdapter());
                $arrayPrototype = array(
                    "id" => "id",
                    "field_name" => "phone_number",
                    "method_name" => "getPhoneNumber"
                );
                $this->insertEP($phonesTable, $phoneData, $arrayPrototype, $id);
            }

            $connection->commit();
        } catch (Exception $ex) {
            $connection->rollback();
        }
    }

    /**
     * Update Contacts
     * @param \Abook\Entity\Contacts $contact
     */
    public function update(\Abook\Entity\Contacts $contact) {

        $data = array(
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'address' => $contact->getAddress(),
            'contact_type_id' => $contact->getContactType(),
            'active' => $contact->getActive()
        );
        $emailData = $contact->getEmails();
        $phoneData = $contact->getPhones();

        # Get DB current data
        $current = $this->fetchById($contact->getId());
        # Set emails and phones into contacts entity
        $contactInDB = new \Abook\Entity\Contacts();
        $contactInDB->hydrate($current["contacts"]);

        # Create EPC object
        $epc = new EmailsPhonesControl();

        $connection = $this->getConnection()->beginTransaction();

        try {

            $current = $this->fetchById($contact->getId());
            # Check if exist record.
            if (sizeof($current["contacts"]) > 0) {

                $contactsTable = new TableGateway("contacts", $this->getDbAdapter());
                $contactsTable->update($data, array("id" => $contact->getId()));

                # Emails
                if (is_array($emailData) && sizeof($emailData) > 0) {

                    $epc->setData($contactInDB->getEmails(), $emailData);

                    $emailsTable = new TableGateway("contacts_emails", $this->getDbAdapter());

                    $arrayPrototype = array(
                        "id" => "id",
                        "field_name" => "email",
                        "method_name" => "getEmail"
                    );

                    if (sizeof($epc->getToInsert()) > 0) {
                        $this->insertEP($emailsTable, $epc->getToInsert(), $arrayPrototype, $contact->getId());
                    }

                    if (sizeof($epc->getToUpdate()) > 0) {
                        $this->updateEP($emailsTable, $epc->getToUpdate(), $arrayPrototype, $contact->getId());
                    }

                    if (sizeof($epc->getToDelete()) > 0) {
                        $this->deleteEP($emailsTable, $epc->getToDelete(), $contact->getId());
                    }
                }

                # Phones
                if (is_array($phoneData) && sizeof($phoneData) > 0) {

                    $epc->setData($contactInDB->getPhones(), $phoneData);

                    $phonesTable = new TableGateway("contacts_phones", $this->getDbAdapter());

                    $arrayPrototype = array(
                        "id" => "id",
                        "field_name" => "phone_number",
                        "method_name" => "getPhoneNumber"
                    );

                    if (sizeof($epc->getToInsert()) > 0) {
                        $this->insertEP($phonesTable, $epc->getToInsert(), $arrayPrototype, $contact->getId());
                    }

                    if (sizeof($epc->getToUpdate()) > 0) {
                        $this->updateEP($phonesTable, $epc->getToUpdate(), $arrayPrototype, $contact->getId());
                    }

                    if (sizeof($epc->getToDelete()) > 0) {
                        $this->deleteEP($phonesTable, $epc->getToDelete(), $contact->getId());
                    }
                }

                $connection->commit();
            }
        } catch (Exception $ex) {
            $connection->rollback();
        }
    }

    /**
     * Insert Email/Phone data
     * @param \Zend\Db\TableGateway\TableGateway $table
     * @param array $dataPost
     * @param array $arrayPrototype
     * @param type $contact_id
     */
    private function insertEP(TableGateway $table, array $oInsert, array $arrayPrototype, $contact_id) {
        $data = array();
        foreach ($oInsert as $obj) {
            $data[$arrayPrototype["field_name"]] = $obj->{$arrayPrototype["method_name"]}();
            $data["contact_id"] = $contact_id;
            $table->insert($data);
        }
    }

    /**
     * Update Email/Phone data
     * @param \Zend\Db\TableGateway\TableGateway $table
     * @param array $dataPost
     * @param array $epc
     * @param array $arrayPrototype
     * @param type $contact_id
     */
    private function updateEP(TableGateway $table, array $oUpdate, array $arrayPrototype, $contact_id) {

        $data = array();
        foreach ($oUpdate as $obj) {
            # Update
            $data[$arrayPrototype["field_name"]] = $obj->{$arrayPrototype["method_name"]}();
            $table->update($data, array(
                "id" => $obj->getId(),
                "contact_id" => $contact_id
            ));
        }
    }

    private function deleteEP(TableGateway $table, array $oDelete, $contact_id) {

        $data = array();
        foreach ($oDelete as $obj) {
            # Delete
            $data["deleted"] = 1;
            $table->update($data, array(
                "id" => $obj->getId(),
                "contact_id" => $contact_id
            ));
        }
    }

}
