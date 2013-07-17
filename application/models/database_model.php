<?php

/**
 * database_model - [Add a short description of what this file does]
 *
 * [Add a long description of the file (1 sentence) and then delete my example]
 * Example: A PHP file template created to standardize code.
 * 
 * @package		alohamora
 * @author		University of the East Research and Development Unit
 * @author              Daryll Santos, <daryll.santos@gmail.com>
 * @copyright           Copyright (c) 2013
 * @license		http://opensource.org/licenses/mit-license.php
 * @link		https://www.facebook.com/ueccssrnd
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Database_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    public function construct_database() {
        $this->dbforge->create_database(DATABASE_NAME);
        $this->load->database('test', TRUE);

        $this->create_cities_table();
        $this->seed_cities_table();
        $this->create_schools_table();
        $this->create_users_table();
        $this->create_transactions_table();

        if (ENVIRONMENT == 'development') {
            $this->seed_schools_table();
            $this->seed_users_table();
        }
    }

    /**
     * Manila only
     */
    private function create_cities_table() {
        $this->dbforge->add_field(array(
            'city_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'city_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            "stamp_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            'stamp_updated TIMESTAMP '
        ));

        $this->dbforge->add_key('city_id', TRUE);
        $this->dbforge->create_table('cities');
    }

    private function seed_cities_table() {
        $cities = array('Caloocan', 'Las Piñas', 'Makati', 'Malabon',
            'Mandaluyong', 'Manila', 'Marikina', 'Muntinlupa', 'Navotas',
            'Parañaque', 'Pasay', 'Pasig', 'Pateros', 'Quezon City', 'San Juan',
            'Taguig', 'Valenzuela');
        foreach ($cities as $key) {
            $this->db->set('city_name', $key);
            $this->db->insert('cities');
        }
    }

    private function create_schools_table() {
        $this->dbforge->add_field(array(
            'school_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'city_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'school_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            "stamp_created TIMESTAMP DEFAULT '0000-00-00 00:00:00'",
            'stamp_updated TIMESTAMP DEFAULT NOW() ON UPDATE NOW()'
        ));

        $this->dbforge->add_key('school_id', TRUE);
        $this->dbforge->create_table('schools');

        $this->db->query('
            ALTER TABLE schools
            ADD CONSTRAINT fk_Schools_Cities
            FOREIGN KEY (city_id)
            REFERENCES cities(city_id)
            ');
    }

    private function seed_schools_table() {
        $cities = array('Isaac Lopez Integrated School' => 5,
            'Benigno Aquino Jr. High School' => 1,
            'Deparo High School' => 1,
            'Sta. Lucia High School' => 12,
            'Kasarinlan High School' => 1,
            'San Rafael National High School' => 9,
            'Barangka National High School' => 7,
            'Amparo High School' => 1,
            'Tugatog National High School' => 4,
            'F. Torres High School' => 6,
            'Caloocan High School' => 1,
            'MNHS-Concepcion Tech-Voc Annex' => 4,
            'Manggahan High School' => 12,
            'Camarin High School' => 1,
            'Sta. Elena High School' => 7,
            'San Joaquin - Kalawaan High School' => 12,
            'Manuel L. Quezon High School (Manila)' => 6,
            'Panghulo National High School' => 4,
            'A.J. Villegas Vocational School' => 6,
            'Fortune High School' => 7,
            'Baesa High School' => 1,
            'Rizal Technological University' => 5,
            'Manuel L. Quezon High School (Caloocan)' => 1,
            'Elpidio Quirino High School' => 6,
            'Marikina High School' => 7,
            'Rizal High School' => 12,
            'Vicente P. Trinidad National High School' => 17,
            'Llano High School' => 1,
            'Tañong High School' => 7,
            'R. Magsaysay High School' => 6,
            'Malabon National High School' => 4,
            'RESPSCI' => 12,
            'Sto. Niño National High School' => 7,
            'Pasig City Science High School' => 12,
            'Nagpayong High School' => 12,
            'Jose P.Laurel High School-Manila' => 6,
            'Wawangpulo National High School' => 17,
            'Santolan High School' => 12,
            'Kalayaan National High School' => 1,
            'Bagumbong High School Annex (Northville)' => 1,
            'Victorino Mapa High School' => 6,
            'M.B. Asistio Sr. High School - Unit I' => 1,
            'Mountain Heights High School' => 1,
            'Tinajeros National High School' => 4,
            'Esteban Abada High School' => 6,
            'Tanza National High School' => 9,
            'Eulogio Rodriguez Integrated School' => 5,
            'Sampaguita High School' => 1,
            'E.Aguinaldo Integrated High School' => 6,
            'Tangos National High School' => 9,
            'Highway Hills Integrated School' => 5,
            'Panghulo National high School' => 4,
            'I.Villamor High School' => 6,
            'Kaunlaran High School' => 9,
            'Mataas na Paaralang Neptali A. Gonzales' => 5,
            'Caybiga High School' => 1,
            'San Roque National High School - Navotas' => 9,
            'Mandaluyong High School' => 5,
            'Tala High School' => 1,
            'Tañong National High School - Malabon' => 4,
            'A. Maceda Integrated-Sta. Mesa' => 6,
            'Navotas National High School' => 9,
            'Malanday National High School' => 7,
            'Nangka High School' => 7,
            'Sagad High School Pasig' => 12,
            'Parang High School' => 7,
            'San Juan National High School' => 15,
            'Parada National High School' => 17,
            'Maypajo High School' => 1,
            'San Roque National High School - Marikina' => 7,
            'Catmon Integrated School' => 4,
            'C. Arellano High School' => 6,
            'Pangarap High School' => 1,
            'Talipapa High School' => 1,
            'Longos National High School' => 4,
            'Caloocan City Business High School' => 1,
            'Maria Clara High School' => 1,
            'Pinagbuhatan High School' => 12,
            'Ilaya Barangka Integrated School' => 5,
            'NHC High School' => 1,
            'Andres Bonifacio Integrated School' => 5,
            'Kalumpang National High School' => 7,
            'Bagong Barrio National High School' => 1,
            'Claro M. Recto High School' => 6,
            'Bagong Silang High School' => 1,
            'Araullo High School' => 6,
            'Elpidio Quirino High School (Manila)' => 6,
            'Cielito Zamora High School' => 1,
            'Jose Abad Santos High School' => 6,
            'UE Manila ESLS' => 6,
            'Manila High School' => 6,
            'Manuel A. Roxas High School' => 6,
            'Bagumbong High School' => 1,
            'Tondo High School' => 6,
            'Caloocan City Science High School' => 1,
            'E. Rodriguez Voc. HS' => 6,
            'F.G. Calderon Integrated School' => 6,
            'Potrero National High School' => 4,
            'Lakan Dula High School' => 6,
            'President Sergio Osmeña High School' => 6,
            'Dr. Juan G. Nolasco High School' => 6,
            'Mariano Marcos Memorial High School' => 6,
            'Cielito Zamora High School Annex II' => 1,
            'Marikina Heights High School' => 7,
            'T. Paez Integrated School' => 6,
            'Marikina Science High School' => 7,
            'Eusebio High School' => 12,
            'Tandang Sora Integrated School' => 1,
            'M.B. Asistio Sr. High School' => 1,
            'Concepcion Integrated School' => 7,
            'Horacio dela Costa High School Annex' => 1,
            'Tinajeros National High School Acacia Annex' => 4,
            'T. Alonzo High School' => 6,
            'C.P. Garcia High School' => 6,
            'M.B. Asistio Sr. High School ' => 1,
            'Kapitolyo High School' => 12
        );
        foreach ($cities as $key => $value) {
            $this->db->set('school_name', $key);
            $this->db->set('city_id', $value);
            $this->db->insert('schools');
        }
    }

    private function create_users_table() {
        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'school_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'cellphone_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'email_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'location' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'stamp_created TIMESTAMP DEFAULT NOW() ON UPDATE NOW()',
            "stamp_updated TIMESTAMP DEFAULT '0000-00-00 00:00:00'"
        ));

        $this->dbforge->add_key('user_id', TRUE);
        $this->dbforge->create_table('users');
        $this->db->query('
            ALTER TABLE transactions
            ADD CONSTRAINT unique_participant_per_day 
            UNIQUE (first_name, last_name);
            ');
    }

    private function seed_users_table() {

        $user = array(
            'first_name' => 'Vanessa',
            'last_name' => 'Orillano',
            'cellphone_number' => '09151234567',
            'email_address' => 'vanessa.orillano@gmail.com',
            'school_id' => 1,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);

        $user = array(
            'first_name' => 'Daryll',
            'last_name' => 'Santos',
            'cellphone_number' => '09151234562',
            'email_address' => 'daryll.santos@gmail.com',
            'school_id' => 2,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);
    }

    private function create_transactions_table() {

        $this->dbforge->add_field(array(
            'transaction_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'transaction_date' => array(
                'type' => 'DATE'
            ),
            'time_logged_in TIMESTAMP DEFAULT NOW()',
            'time_logged_out' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));
        $this->dbforge->add_key('transaction_id', TRUE);
        $this->dbforge->create_table('transactions');
        $this->db->query('
            ALTER TABLE transactions
            ADD CONSTRAINT fk_Users_Transactions
            FOREIGN KEY (user_id)
            REFERENCES users(user_id)
            ');
        $this->db->query('
            ALTER TABLE transactions
            ADD CONSTRAINT unique_participant_per_day 
            UNIQUE (transaction_date, user_id);
            ');
    }

    public function destroy_database() {
        $this->load->database('test', TRUE);

        $tables = $this->db->list_tables();

        foreach ($tables as $table) {
            echo $table;

            $fields = $this->db->list_fields($table);

            echo implode(' ', $fields);
        }
        $this->dbforge->drop_database(DATABASE_NAME);
    }

}

/* End of file database_model.php */