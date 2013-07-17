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

    public function __construct() {
        parent::__construct();
        $this->load->library('fpdf/fpdf.php');
    }

    public function report() {


        $pdf = new FPDF('P', 'mm', 'A4');
        $paper_width = 190;
        $paper_height = 277;
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 20);
        $pdf->Cell($paper_width, 10, 'K to 12 National Training of Grade 7 MAPEH Teachers', 0, 1, 'C');
        $pdf->Cell($paper_width, 10, 'List of Attendees - ' . date('M d, Y'), 0, 1, 'C');
        $pdf->Cell($paper_width, 10, '', 0, 1, 'C');

        $this->load->model('user_model');
        $users = $this->user_model->get();

        $pdf->SetFont('Arial', '', 12);

        foreach ($users as $user) {
            $pdf->Cell($this->getWidestLengthInMM(__::pluck($users, 'first_name')), 10, $user['first_name'], 1, 0, 'L');
            $pdf->Cell($this->getWidestLengthInMM(__::pluck($users, 'last_name')), 10, $user['last_name'], 1, 0, 'L');
            $pdf->Cell($this->getWidestLengthInMM(__::pluck($users, 'email_address')), 10, $user['email_address'], 1, 0, 'L');
            $pdf->Cell($this->getWidestLengthInMM(__::pluck($users, 'cellphone_number')), 10, $user['cellphone_number'], 1, 0, 'L');
            $pdf->Cell($this->getWidestLengthInMM(__::pluck($users, 'school_name')), 10, iconv('UTF-8', 'windows-1252', $user['school_name']), 1, 1, 'L');
        }
        $pdf->Output('assets/doc.pdf', 'F');
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