<?php

declare(strict_types=1);

require_once(SYSROOT . 'app.php');

class Client
{
    protected object $db;
    protected int $id;

    public function __construct()
    {
        App::get();
        $this->db = DbQuery::get();
    }

    protected function checkId(): int
    {
        return $this->id ?? 0;
    }

    public function getClients(): array
    {
        return $this->db->select(['*'])->from('agency_clients')->getRows();
    }

    public static function getAllClientsRelatedData(): array
    {
        App::get();

        $fields_to_select = [
            'agency_clients.id AS id',
            'agency_clients.name AS client_name',
            'agency_clients.company_name AS company_name',
            'agency_clients.country AS country',
            'agency_clients.vat_number AS vat_number',
            'agency_clients.currency AS currency'
        ];

        $clients_data = DbQuery::get()->select([implode(', ', $fields_to_select)])->from('agency_clients')->getRows();

        if (empty($clients_data)) {
            return [];
        }

        $res = [];

        $currencies = App::getConf('currencies');
        $countries = App::getConf('countries');

        App::loadClass('package', SYSROOT);
        
        foreach ($clients_data as $key => $client) {

            $id = (int) $client['id'];
          
            $client_package = DbQuery::get()->select(['*'])->from('agency_clients_packages')->where("client_id = ?", [$id])->getRow();

            $client['package_name'] = '';
            $client['package_price'] = '';
            $client['package_currency'] = '';

            if($client_package) {
                $package_id = (int) $client_package['package_id'];

                $package = new Package();
                $package_data = $package->get($package_id);
        
                $client['package_name'] = $package_data['name'];
                $client['package_price'] = $package_data['price'];
                $client['package_currency'] = $currencies[$package_data['currency']];
            }

            $contacts_data = DbQuery::get()->select(['*'])->from('agency_clients_contacts_persons')->where("client_id = ?", [$id])->getRows();

            $client['contacts'] = $contacts_data;
            $client['country'] = $countries[$client['country']];
            $client['currency'] = $currencies[$client['currency']];

            $res[] = $client;

        }

        return $res;
    }


    public function get(int $id = 0): array
    {
        if (!$this->checkId() || !$id) {
            return [];
        }

        $id = $id ? $id : $this->id;

        return $this->db->select(['*'])->from('agency_clients')->where('id = ?', [$id])->getRow();
    }

    public function checkIfClientExistsByCompanyName(string $company_name): bool
    {
        $result = $this->db->setQuery("SELECT id FROM agency_clients WHERE company_name = '" . $company_name . "'")->getField("id");
        return $result ? true : false;
    }

    public function createClient(array $client_data, array $contact_persons = []): int
    {
        if (!$this->validateDataBeforeClientAdd($client_data)) {
            return 0;
        }

        if (!$this->validateCountryAndCurrency($client_data)) {
            return 0;
        }

        $id = 0;

        try {
            $fields_to_check = App::getConf('client_form_expected_post');
            $client_data = array_intersect_key($client_data, $fields_to_check);

            $insert = $this->db->insert("agency_clients", [
                "name" => $client_data['name'],
                "company_name" => $client_data['company_name'],
                "country" => $client_data['country'],
                "vat_number" => $client_data['vat_number'],
            ]);

            $package = $client_data['package'] ?? 0;

            $id = $insert->insert_id;

            if ($id && !empty($contact_persons)) {
                App::loadClass("clientcontact", SYSROOT);
                $contact_person = new ClientContact();
                foreach ($contact_persons as $contact) {
                    if ($contact_person->validateContactData($contact)) {
                        $contact['client_id'] = $id;
                        $contact_person->createContactPerson($contact);
                    }
                }
            }

            if ($id && $package) {
                $this->db->insert("agency_clients_packages", [
                    "client_id" => $id,
                    "package_id" => $package
                ]);
            }
        } catch (\Exception $e) {
            return 0;
        }

        return $id;
    }

    private function validateDataBeforeClientAdd(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $fields_to_check = App::getConf('client_form_expected_post');

        foreach ($fields_to_check as $key => $name) {
            if (!isset($data[$key]) || empty($data[$key])) {
                return false;
            }
        }

        return true;
    }

    private function validateCountryAndCurrency(array $data): bool
    {
        $countries = App::getConf('countries');
        $currencies = App::getConf('currencies');

        if (!isset($countries[$data['country']]) || !isset($currencies[$data['currency']])) {
            return false;
        }

        return true;
    }
}
