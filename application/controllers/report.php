<?php

/**
 * report - [Add a short description of what this file does]
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

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->report_model->report('generateAttendance');

        $file = 'assets/doc.pdf'; // file to be downloaded
        header("Expires: 0");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-type: application/pdf");
        header('Content-length: ' . filesize($file));
        header('Content-disposition: attachment; filename=' . basename($file));
        readfile($file);
    }

    
    /**
     * don't delete this we need this for feeding data in
     */
    public function test() {
        $str = file('assets/schools.csv');
        
        $arr = array();

        foreach ($str as $line_num => $line) {
            
            $cities = array('Caloocan' => 1, 'Las Piñas' => 2, 'Makati' => 3, 'Malabon'=> 4,
            'Mandaluyong'=> 5, 'Manila'=> 6, 'Marikina'=> 7, 'Muntinlupa'=> 8, 'Navotas'=> 9,
            'Parañaque'=> 10, 'Pasay'=> 11, 'Pasig'=> 12, 'Pateros'=> 13, 'Quezon City'=> 14, 'San Juan'=> 15,
            'Taguig'=> 16, 'Valenzuela'=> 17);
            
            
            $tae = explode(',', $line);
            
            $arr[$tae[0]] = $cities[trim($tae[1])];
            
        }
               
        
        foreach ($arr as $key => $tae){
            echo  "'$key'" . ' => ' . $tae . ',<br/>';
        }

    }

}

/* End of file report.php */