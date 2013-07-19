<?php

/**
 * report_model - [Add a short description of what this file does]
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

class Report_model extends CI_Model {

    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->load->library('fpdf/fpdf.php');

        $this->pdf = new FPDF('P', 'mm', 'A4');
    }

    public function report($callbackFunction) {
        $paper_width = 190;
        $paper_height = 277;
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', '', 20);
        $this->pdf->Cell($paper_width, 10, EVENT_NAME, 0, 1, 'C');
        $this->pdf->Cell($paper_width, 10, 'List of Attendees - ' . date('M d, Y'), 0, 1, 'C');
        $this->pdf->Cell($paper_width, 10, '', 0, 1, 'C');


        call_user_func($this->{$callbackFunction}());

        $this->pdf->Output('assets/doc.pdf', 'F');
    }

    private function generateAttendance() {


        $this->load->model('user_model');
        $users = $this->user_model->get();

        $this->pdf->SetFont('Arial', '', 12);

        $fields = array('first_name', 'last_name', 'email_address', 'cellphone_number', 'school_name');


        foreach ($users as $user) {
//            Print each of the fields in the array, but only after taking the one with the maximum length
//            Iconv is for the special characters, must sanitize them
//            foreach ($fields as $field) {
//                $this->pdf->Cell($this->getWidestLengthInMM(__::pluck($users, $field)), 10, iconv('UTF-8', 'windows-1252', $user[$field]), 1, ($field == $fields[count($fields) - 1] ? 1 : 0), 'L');
//            }
//            40.7??

            $current_y = $this->pdf->GetY();
            $current_x = $this->pdf->GetX();

            $this->pdf->MultiCell(30, 10, $user['first_name'], 1, 'L');
            $this->pdf->SetXY($current_x + 30, $current_y);
            $current_x = $this->pdf->GetX();
            $this->pdf->MultiCell(30, 10, $user['last_name'], 1, 'L');
//            $this->pdf->Cell($this->getWidestLengthInMM(__::pluck($users, 'last_name')), 10, $user['last_name'], 1, 0);
//            $this->pdf->MultiCell($this->getWidestLengthInMM(__::pluck($users, 'email_address')), 10, $user['email_address'], 1, 0);
//            $this->pdf->MultiCell($this->getWidestLengthInMM(__::pluck($users, 'cellphone_number')), 10, $user['cellphone_number'], 1, 0);
//            $this->pdf->MultiCell($this->getWidestLengthInMM(__::pluck($users, 'school_name')), 10, iconv('UTF-8', 'windows-1252', $user['school_name']), 1, 1);
        }
    }

    public function generateCertificate($user) {
        
    }

    private function getWidestLengthInMM($collection) {
        return __::chain($collection)->map(function($data) {
                            return Report_model::getStringWidth($data);
                        })->max()->value();
    }

    static function getStringWidth($data) {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetFont('Arial', '', 12);
        return $pdf->GetStringWidth($data . 'm');
    }

}

/* End of file report_model.php */