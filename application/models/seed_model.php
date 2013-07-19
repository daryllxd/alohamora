<?php

/**
 * seed_model - [Add a short description of what this file does]
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

class Seed_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function seed_all_tables() {
        $this->seed_schools_table();
        $this->seed_users_table();
    }

    private function seed_schools_table() {
        $cities = array('Isaac Lopez Integrated School' => 5,
            'Benigno Aquino Jr. High School' => 1,
            'Deparo High School' => 1,
            'Sta. Lucia High School' => 12,
            'Kasarinlan High School' => 1,
            'San Rafael National High School' => 9,
            'Barangka National High School' => 7,
            'Amparo High School' => 1,
            'Tugatog National High School' => 4,
            'F. Torres High School' => 6,
            'Caloocan High School' => 1,
            'MNHS-Concepcion Tech-Voc Annex' => 4,
            'Manggahan High School' => 12,
            'Camarin High School' => 1,
            'Sta. Elena High School' => 7,
            'San Joaquin - Kalawaan High School' => 12,
            'Manuel L. Quezon High School (Manila)' => 6,
            'Panghulo National High School' => 4,
            'A.J. Villegas Vocational School' => 6,
            'Fortune High School' => 7,
            'Baesa High School' => 1,
            'Rizal Technological University' => 5,
            'Manuel L. Quezon High School (Caloocan)' => 1,
            'Elpidio Quirino High School' => 6,
            'Marikina High School' => 7,
            'Rizal High School' => 12,
            'Vicente P. Trinidad National High School' => 17,
            'Llano High School' => 1,
            'Tañong High School' => 7,
            'R. Magsaysay High School' => 6,
            'Malabon National High School' => 4,
            'RESPSCI' => 12,
            'Sto. Niño National High School' => 7,
            'Pasig City Science High School' => 12,
            'Nagpayong High School' => 12,
            'Jose P.Laurel High School-Manila' => 6,
            'Wawangpulo National High School' => 17,
            'Santolan High School' => 12,
            'Kalayaan National High School' => 1,
            'Bagumbong High School Annex (Northville)' => 1,
            'Victorino Mapa High School' => 6,
            'M.B. Asistio Sr. High School - Unit I' => 1,
            'Mountain Heights High School' => 1,
            'Tinajeros National High School' => 4,
            'Esteban Abada High School' => 6,
            'Tanza National High School' => 9,
            'Eulogio Rodriguez Integrated School' => 5,
            'Sampaguita High School' => 1,
            'E.Aguinaldo Integrated High School' => 6,
            'Tangos National High School' => 9,
            'Highway Hills Integrated School' => 5,
            'Panghulo National high School' => 4,
            'I.Villamor High School' => 6,
            'Kaunlaran High School' => 9,
            'Mataas na Paaralang Neptali A. Gonzales' => 5,
            'Caybiga High School' => 1,
            'San Roque National High School - Navotas' => 9,
            'Mandaluyong High School' => 5,
            'Tala High School' => 1,
            'Tañong National High School - Malabon' => 4,
            'A. Maceda Integrated-Sta. Mesa' => 6,
            'Navotas National High School' => 9,
            'Malanday National High School' => 7,
            'Nangka High School' => 7,
            'Sagad High School Pasig' => 12,
            'Parang High School' => 7,
            'San Juan National High School' => 15,
            'Parada National High School' => 17,
            'Maypajo High School' => 1,
            'San Roque National High School - Marikina' => 7,
            'Catmon Integrated School' => 4,
            'C. Arellano High School' => 6,
            'Pangarap High School' => 1,
            'Talipapa High School' => 1,
            'Longos National High School' => 4,
            'Caloocan City Business High School' => 1,
            'Maria Clara High School' => 1,
            'Pinagbuhatan High School' => 12,
            'Ilaya Barangka Integrated School' => 5,
            'NHC High School' => 1,
            'Andres Bonifacio Integrated School' => 5,
            'Kalumpang National High School' => 7,
            'Bagong Barrio National High School' => 1,
            'Claro M. Recto High School' => 6,
            'Bagong Silang High School' => 1,
            'Araullo High School' => 6,
            'Elpidio Quirino High School (Manila)' => 6,
            'Cielito Zamora High School' => 1,
            'Jose Abad Santos High School' => 6,
            'UE Manila ESLS' => 6,
            'Manila High School' => 6,
            'Manuel A. Roxas High School' => 6,
            'Bagumbong High School' => 1,
            'Tondo High School' => 6,
            'Caloocan City Science High School' => 1,
            'E. Rodriguez Voc. HS' => 6,
            'F.G. Calderon Integrated School' => 6,
            'Potrero National High School' => 4,
            'Lakan Dula High School' => 6,
            'President Sergio Osmeña High School' => 6,
            'Dr. Juan G. Nolasco High School' => 6,
            'Mariano Marcos Memorial High School' => 6,
            'Cielito Zamora High School Annex II' => 1,
            'Marikina Heights High School' => 7,
            'T. Paez Integrated School' => 6,
            'Marikina Science High School' => 7,
            'Eusebio High School' => 12,
            'Tandang Sora Integrated School' => 1,
            'M.B. Asistio Sr. High School' => 1,
            'Concepcion Integrated School' => 7,
            'Horacio dela Costa High School Annex' => 1,
            'Tinajeros National High School Acacia Annex' => 4,
            'T. Alonzo High School' => 6,
            'C.P. Garcia High School' => 6,
            'M.B. Asistio Sr. High School ' => 1,
            'Kapitolyo High School' => 12
        );
        foreach ($cities as $key => $value) {
            $this->db->set('school_name', $key);
            $this->db->set('city_id', $value);
            $this->db->insert('schools');
        }
    }
    
    private function seed_users_table() {

        $user = array(
            'first_name' => 'Vanessa',
            'last_name' => 'Orillano',
            'cellphone_number' => '09151234567',
            'email_address' => 'vanessa.orillano@gmail.com',
            'school_id' => 1,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);

        $user = array(
            'first_name' => 'Daryll',
            'last_name' => 'Santos',
            'cellphone_number' => '09151234562',
            'email_address' => 'daryll.santos@gmail.com',
            'school_id' => 2,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);
        
        $user = array(
            'first_name' => 'Daryll',
            'last_name' => 'Santos',
            'cellphone_number' => '09151234562',
            'email_address' => 'daryll.santos@gmail.com',
            'school_id' => 2,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);
        
        $user = array(
            'first_name' => 'Daryll',
            'last_name' => 'Santos',
            'cellphone_number' => '09151234562',
            'email_address' => 'daryll.santos@gmail.com',
            'school_id' => 2,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);
        
        $user = array(
            'first_name' => 'Anna Jane Missann',
            'last_name' => 'Matillano',
            'cellphone_number' => '09151234562',
            'email_address' => 'annajane@gmail.com',
            'school_id' => 4,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);
        
        $user = array(
            'first_name' => 'Bardagul',
            'last_name' => 'Capote-Batute',
            'cellphone_number' => '09151234562',
            'email_address' => 'daryll.santos@gmail.com',
            'school_id' => 3,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);
        
        $user = array(
            'first_name' => 'Yo',
            'last_name' => 'Lo',
            'cellphone_number' => '',
            'email_address' => '',
            'school_id' => 2,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);
        
        $user = array(
            'first_name' => 'Lady',
            'last_name' => 'Gaga',
            'cellphone_number' => '',
            'email_address' => 'daryll.santos@gmail.com',
            'school_id' => 2,
            'location' => 'My Name'
        );

        $this->db->insert('users', $user);
    }

}

/* End of file seed_model.php */