<?php

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('format');
        $this->interpolate_model();
        $this->load_settings();
        
    }

    private function interpolate_model() {
        if (file_exists(APPPATH)) {
            if (file_exists(APPPATH . 'models/' . strtolower(get_class($this)) . '_model.php')) {
                $this->load->model(strtolower(get_class($this)) . '_model');
            }
        }
    }
    
    private function load_settings(){
        $this->load->model('settings_model');
        $this->settings_model->load_settings();
    }

}
