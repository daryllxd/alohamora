<?php

/**
 * spreedsheet_model - [Add a short description of what this file does]
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

class Spreadsheet_model extends CI_Model {

    private $Spreadsheet;

    public function __construct() {
        parent::__construct();

        $this->load->library('phpexcel/classes/phpexcel.php');
//        Renamed the Excel2007.php file to PHPExcel_Writer_Excel2007.php because CI doesn't play nice
        $this->load->library('phpexcel/Classes/PHPExcel/Writer/PHPExcel_Writer_Excel2007.php');

        $this->Spreadsheet = new PHPExcel();

        $this->preprocess();
    }

    private function preprocess() {


        $this->Spreadsheet->getProperties()->setCreator(AUTHOR);
        $this->Spreadsheet->getProperties()->setLastModifiedBy(AUTHOR);
        $this->Spreadsheet->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $this->Spreadsheet->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $this->Spreadsheet->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
    }

    public function printParticipants() {
        $this->load->model('user_model');
        $users = $this->user_model->get();

        $fields = array('first_name', 'last_name', 'email_address', 'cellphone_number', 'school_name');

        $count = 1;


        foreach ($users as $user) {

            $this->Spreadsheet->getActiveSheet()->SetCellValue('A' . $count, $user['first_name']);
            $this->Spreadsheet->getActiveSheet()->SetCellValue('B' . $count, $user['last_name']);
            $this->Spreadsheet->getActiveSheet()->SetCellValue('C' . $count, $user['email_address']);
            $this->Spreadsheet->getActiveSheet()->SetCellValue('D' . $count, $user['cellphone_number']);
            $this->Spreadsheet->getActiveSheet()->SetCellValue('E' . $count, $user['school_name']);

            $count++;
        }




// Rename sheet
        echo date('H:i:s') . " Rename sheet\n";
        $this->Spreadsheet->getActiveSheet()->setTitle('Simple');


// Save Excel 2007 file
        echo date('H:i:s') . " Write to Excel2007 format\n";
        $objWriter = new PHPExcel_Writer_Excel2007($this->Spreadsheet);
//        $objWriter->save('http://localhost/alohamora/assets/' .   str_replace('.php', '.xlsx', __FILE__));
        $objWriter->save('assets/tite.xlsx');
// Echo done
        echo date('H:i:s') . " Done writing file.\r\n";
    }

}

/* End of file spreedsheet_model.php */