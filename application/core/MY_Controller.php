<?php

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('format');
        $this->interpolateModel();
    }

    private function interpolateModel() {
        if (file_exists(APPPATH)) {
            if (file_exists(APPPATH . 'models/' . strtolower(get_class($this)) . '_model.php')) {
                $this->load->model(strtolower(get_class($this)) . '_model');
            }
        }
    }

}
