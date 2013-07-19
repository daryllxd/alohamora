<?php

/**
 * settings_model - [Add a short description of what this file does]
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

class Settings_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function load_settings() {
        $this->load->dbutil();
        if ($this->dbutil->database_exists('alohamora')) {
            $users = $this->db->get('settings');

            foreach ($users->result_array() as $row) {
                define(strtoupper($row['setting_name']), $row['setting_value']);
            }
        }
    }

}

/* End of file settings_model.php */