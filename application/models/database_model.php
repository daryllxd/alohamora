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
        $cities = array('University of the East High School - Caloocan' => '1',
            'Las Piñas School' => '2',
            'University of the East High School - Manila' => '6',
            'Phiippine Science High School' => '14'
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