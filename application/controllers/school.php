<?php

/**
 * school - [Add a short description of what this file does]
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

class School extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo AUTHOR;
        echo $this->school_model->get();
    }

    public function add() {
        echo $this->school_model->add($this->input->post());
    }

}

/* End of file school.php */