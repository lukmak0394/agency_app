<?php

declare(strict_types=1);

require_once(SYSROOT.'app.php');
require_once(SYSROOT.'client.php');

class ClientContact extends Client
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getClientContacts(int $id = 0): array
    {
        if (!$this->checkId() || !$id) {
            return [];
        }

        $id = $id ? $id : $this->id;

        return $this->db->select(['*'])->from('agency_clients_contacts_persons')->where('client_id = ?', [$id])->getRows();
    }

    public static function getAllContactPersons(): array
    {
        App::get();

        $query = "
        SELECT 
            agency_clients_contacts_persons.id AS id,
            agency_clients_contacts_persons.client_id AS client_id,
            agency_clients_contacts_persons.firstname AS firstname,
            agency_clients_contacts_persons.lastname AS lastname,
            agency_clients_contacts_persons.email AS email,
            agency_clients_contacts_persons.phone AS phone,
            agency_clients.company_name AS client_name
        FROM 
            agency_clients_contacts_persons
        LEFT JOIN 
            agency_clients ON agency_clients_contacts_persons.client_id = agency_clients.id
        ";

        $data = DbQuery::get()->setQuery($query)->getRows();

        return $data;
    }

    public function createContactPerson(array $data): bool
    {
        try {
            $this->db->insert("agency_clients_contacts_persons", [
                "client_id" => $data['client_id'],
                "firstname" => $data['firstname'],
                "lastname" => $data['lastname'],
                "email" => $data['email'],
                "phone" => $data['phone']
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function validateContactData(array $data): bool
    {
        $required_fields = ['firstname', 'lastname', 'email', 'phone'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        return true;
    }
    
}
