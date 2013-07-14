<?php

/**
 * user_model - [Add a short description of what this file does]
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

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get() {
        $users = $this->db->get('users');

        foreach ($users->result() as $row) {
            $rows[] = $row;
        }

        return json_encode($rows, 1);
    }

    public function add($user) {
        if ($this->isOldUser($user['user_id'])) {
            $this->db->trans_start();
            
            $this->edit($user);

            $transaction = array(
                'user_id' => $user['user_id'],
                'transaction_date' => date('Y-m-d H:i:s')
            );

            $this->db->insert('transactions', $transaction);
            $this->db->trans_complete();

            return $this->isOldUser($user['user_id']);
        } else {
            $this->db->trans_start();

            $user = array(
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'cellphone_number' => $user['cellphone_number'],
                'email_address' => $user['email_address'],
                'school_id' => $user['school_id'],
                'location' => 'My Name'
            );

            $this->db->insert('users', $user);
            $insert_id = $this->db->insert_id();
            
            $transaction = array(
                'user_id' => $insert_id,
                'transaction_date' => date('Y-m-d H:i:s')
            );

            $this->db->insert('transactions', $transaction);
            $this->db->trans_complete();

            return 'Successfully inserted!';
        }
    }

    public function edit($user) {
        $this->db->trans_start();

        $updated_user = array(
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'cellphone_number' => $user['cellphone_number'],
            'school_id' => $user['school_id'],
            'email_address' => $user['email_address']
        );

        $this->db->where('user_id', $user['user_id']);

        $this->db->update('users', $updated_user);
        $this->db->trans_complete();

        return $user['user_id'];
    }

    private function isOldUser($user_id = 0) {
        return $this->db->get_where('users', array('user_id' => $user_id))->num_rows() > 0;
    }

}

/* End of file user_model.php */