<?php

/**
 * admin - For admin functions.
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

class Admin extends MY_Controller {

    private $data;

    public function __construct() {
        parent::__construct();

        $this->data['js'] = array('base', 'admin');
    }

    public function index() {
        $this->load->view('include/header');
        $this->load->view('admin');
        $this->load->view('include/footer', $this->data);
    }

}

/* End of file admin.php */