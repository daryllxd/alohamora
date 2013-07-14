<?php

/**
 * transaction_model
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

class Transaction_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get() {
        $users = $this->db->get('transactions');

        foreach ($users->result() as $row) {
            $rows[] = $row;
        }

        return json_encode($rows, 1);
    }

    public function add($user) {
        
    }

    public function logout($transaction) {
        $this->db->where('user_id', $transaction['user_id']);
        $this->db->where('transaction_date', date('Y-m-d'));

        $updated_transaction = array(
            'time_logged_out' => date('Y-m-d H:i:s')
        );

        $this->db->update('transactions', $updated_transaction);

//        return $this->db->affected_rows();
        return $this->db->last_query();
    }

}

/* End of file user_model.php */