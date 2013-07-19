<?php

/**
 * school_model
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

class School_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get() {
        $users = $this->db->get('schools');

        foreach ($users->result() as $row) {
            $rows[] = $row;
            
        }

        return json_encode($rows, 1);
    }

    public function add($var) {
        $this->db->trans_start();

//        $user = array(
//            'first_name' => $var['first_name'],
//            'last_name' => $var['last_name'],
//            'cellphone_number' => $var['cellphone_number'],
//            'email_address' => $var['email_address'],
//            'school' => $var['school'],
//            'location' => 'My Name'
//        );
//
//        $this->db->insert('users', $user);
//        $insert_id = $this->db->insert_id();
//        $transaction = array(
//            'user_id' => $insert_id,
//            'transaction_date' => date('Y-m-d H:i:s'),
//            'time_logged_in' => $var['cellphone_number']
//        );
//
//        $this->db->insert('transactions', $transaction);
        $this->db->trans_complete();

        return 'Successfully inserted!';
    }

}

/* End of file school_model.php */