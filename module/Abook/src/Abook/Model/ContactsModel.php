<?php

namespace Abook\Model;

use Abook\Toolbox\Db\AbstractAdapterManager;
use Abook\Toolbox\Db\ResultSetManager;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;

class ContactsModel extends AbstractAdapterManager {

    public function fetchAll() {

        $select = <<<SQL
            select 
                id, 
                first_name firstName, 
                last_name lastName,
                address
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

            if (sizeof($emailData) > 0) {
                
                $emailsTable = new TableGateway("contacts_emails", $this->getDbAdapter());
                $this->insertEmails($emailsTable, $emailData, $id);
            }

            if (sizeof($phoneData) > 0) {
                
                $phonesTable = new TableGateway("contacts_phones", $this->getDbAdapter());
                $this->insertPhones($phonesTable, $phoneData, $id);
            }

            $connection->commit();
        } catch (Exception $ex) {
            $connection->rollback();
        }
    }

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

        $connection = $this->getConnection()->beginTransaction();

        try {

            $current = $this->fetchById($contact->getId());
            # Check if exist record.
            if (sizeof($current["contacts"]) > 0) {

                $contactsTable = new TableGateway("contacts", $this->getDbAdapter());
                $contactsTable->update($data, array("id" => $contact->getId()));

                if (sizeof($emailData) > 0) {
                    
                    $emailsTable = new TableGateway("contacts_emails", $this->getDbAdapter());

                    if (sizeof($current["emails"]) > 0) {

                        # Update email
                        $this->updateEmails($emailsTable, $emailData, $contact->getId());
                        
                    } else {

                        # Insert new email
                        $this->insertEmails($emailTable, $emailData, $contact->getId());
                    }
                }

                if (sizeof($phoneData) > 0) {
                    
                    $phonesTable = new TableGateway("contacts_phones", $this->getDbAdapter());

                    if (sizeof($current["phones"]) > 0) {
                        
                        # Update phones
                        $this->updatePhones($phonesTable, $phonesTable, $contact->getId());
                                                
                    } else {

                        # Insert phones
                        $this->insertPhones($phonesTable, $phoneData, $contact->getId());
                    }
                }

                $connection->commit();
            }
        } catch (Exception $ex) {
            $connection->rollback();
        }
    }

    private function insertEmails(TableGateway $emailsTable, array $emailData, $contact_id) {
        $data = array();
        foreach ($emailData as $email) {
            $data["email"] = $email->getEmail();
            $data["contact_id"] = $contact_id;
            $emailsTable->insert($data);
        }
    }

    private function insertPhones(TableGateway $phonesTable, array $phoneData, $contact_id) {
        $data = array();
        foreach ($phoneData as $phone) {
            $data["phone_number"] = $phone->getPhoneNumber();
            $data["contact_id"] = $contact_id;
            $phonesTable->insert($data);
        }
    }

    private function updateEmails(TableGateway $emailsTable, array $emailData, $contact_id) {
        $data = array();
        foreach ($emailData as $email) {
            $data["email"] = $email->getEmail();
            $data["contact_id"] = $contact_id;
            $emailsTable->insert($data);
        }
    }

    private function updatePhones(TableGateway $phonesTable, array $phoneData, $contact_id) {
        $data = array();
        foreach ($phoneData as $phone) {
            $data["phone_number"] = $phone->getPhoneNumber();
            $data["contact_id"] = $contact_id;
            $phonesTable->insert($data);
        }
    }

}
