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
                id, 
                first_name firstName, 
                last_name lastName,
                address,
                active
            from contacts            
            where 
                deleted = 0
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
            where id = {$id};
            
            select 
                id,
                email
            from contacts_emails
            where contact_id = {$id};
            
            select 
                id,
                phone_number phoneNumber
            from contacts_phones
            where contact_id = {$id};
SQL;


        $result = $this->getConnection()->execute($select);
        $statement = $result->getResource();

        $resultSet1 = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $resultSet1 = (sizeof($resultSet1) > 0) ? array_pop($resultSet1) : array();

        if ($statement->nextRowSet()) {
            $resultSet2 = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $resultSet2 = (sizeof($resultSet2) > 0) ? $resultSet2 : array();
        }

        if ($statement->nextRowSet()) {
            $resultSet3 = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $resultSet3 = (sizeof($resultSet3) > 0) ? $resultSet3 : array();
        }

        $contacts["contacts"] = $resultSet1;
        $contacts["emails"] = $resultSet2;
        $contacts["phones"] = $resultSet3;

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

            $emailsTable = new TableGateway("contacts_emails", $this->getDbAdapter());
            $arrayPrototype = array(
                "id" => "id",
                "field_name" => "email",
                "method_name" => "getEmail"
            );
            $this->insertEP($emailsTable, $emailData, $arrayPrototype, $id);

            $phonesTable = new TableGateway("contacts_phones", $this->getDbAdapter());
            $arrayPrototype = array(
                "id" => "id",
                "field_name" => "phone_number",
                "method_name" => "getPhoneNumber"
            );
            $this->insertEP($phonesTable, $phoneData, $arrayPrototype, $id);

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
        $contactInDB->setEmails($current["emails"]);
        $contactInDB->setPhones($current["phones"]);

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
                $epc->setData($emailData, $contactInDB->getEmails());

                $emailsTable = new TableGateway("contacts_emails", $this->getDbAdapter());

                $arrayPrototype = array(
                    "id" => "id",
                    "field_name" => "email",
                    "method_name" => "getEmail"
                );

                if (sizeof($epc->getToInsert()) > 0) {
                    $this->insertEP($emailsTable, $emailData, $arrayPrototype, $contact->getId());
                }

                if (sizeof($epc->getToUpdate()) > 0) {
                    $this->updateEP($emailsTable, $emailData, $epc->getToUpdate(), $arrayPrototype, $contact->getId());
                }

                if (sizeof($epc->getToDelete()) > 0) {
                    
                }

                # Phones
                $epc->setData($phoneData, $contactInDB->getPhones());

                $phonesTable = new TableGateway("contacts_phones", $this->getDbAdapter());

                $arrayPrototype = array(
                    "id" => "id",
                    "field_name" => "phone_number",
                    "method_name" => "getPhoneNumber"
                );

                if (sizeof($epc->getToInsert()) > 0) {
                    $this->insertEP($phonesTable, $phoneData, $arrayPrototype, $contact->getId());
                }

                if (sizeof($epc->getToUpdate()) > 0) {
                    $this->updateEP($phonesTable, $phoneData, $epc->getToUpdate(), $arrayPrototype, $contact->getId());
                }

                if (sizeof($epc->getToDelete()) > 0) {
                    
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
    private function insertEP(TableGateway $table, array $dataPost, array $arrayPrototype, $contact_id) {
        $data = array();
        foreach ($dataPost as $obj) {
            if ($obj->getId() == "") {
                $data[$arrayPrototype["field_name"]] = $obj->{$arrayPrototype["method_name"]}();
                $data["contact_id"] = $contact_id;
                $table->insert($data);
            }
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
    private function updateEP(TableGateway $table, array $dataPost, array $epc, array $arrayPrototype, $contact_id) {

        $data = array();

        foreach ($dataPost as $dPost) {
            foreach ($epc as $obj) {
                if ($dPost->getId() == $obj->getId()) {
                    # Update
                    $data[$arrayPrototype["field_name"]] = $dPost->{$arrayPrototype["method_name"]}();
                    $table->update($data, array(
                        "id" => $dPost->getId(),
                        "contact_id" => $contact_id
                    ));
                }
            }
        }
    }

}
