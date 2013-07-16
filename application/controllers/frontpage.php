<?php

if (!defined('BASEPATH'))
    die();

class Frontpage extends Main_Controller {

    private $data;

    public function __construct() {
        parent::__construct();
        $this->data['js'] = array('custom');
    }

    public function index() {
        $this->load->view('include/header');
        $this->load->view('frontpage');
        $this->load->view('include/footer', $this->data);
    }

    public function database_not_found() {
        $this->load->dbutil();
        if (!$this->dbutil->database_exists('alohamora')) {
            $this->load->model('database_model');
            $this->database_model->construct_database();
            echo 'have db';
        } else {
            $this->load->model('database_model');
            $this->database_model->destroy_database();
            echo 'no db now';
        }
    }

}

/* End of file frontpage.php */
/* Location: ./application/controllers/frontpage.php */
