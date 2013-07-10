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

        $this->create_users_table();
        $this->create_transactions_table();
    }

    private function create_users_table() {
        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
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
            'school' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'location' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            "stamp_created TIMESTAMP DEFAULT '0000-00-00 00:00:00'",
            'stamp_updated TIMESTAMP DEFAULT NOW() ON UPDATE NOW()'
        ));

        $this->dbforge->add_key('user_id', TRUE);

        $this->dbforge->create_table('users');
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
                'type' => 'DATETIME'
            )
        ));

        $this->dbforge->add_key('transaction_id', TRUE);

        $this->dbforge->create_table('transactions');
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