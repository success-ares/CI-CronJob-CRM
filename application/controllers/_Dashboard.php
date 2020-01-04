<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
    }

    function index() {
        $data['_view'] = 'dashboard';
        $this->load->view('layouts/main', $data);
    }

    public function searchByKey($key) {
        if (empty($this->session->userdata('acc_token'))) {
            $this->generateNewToken();
        }
        $url = "https://api.cmb.comprami.ro/api/v2/cmj/cautare/" . $key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_GET, 1);
        $authorization = "Authorization: Bearer " . $this->session->userdata('acc_token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        $res = curl_exec($ch);
        $res_decoded = json_decode($res, TRUE);
        if ($res_decoded['msg'] == 'Token has expired') {
            $this->session->set_userdata('acc_token', '');
            $this->searchByKey($key);
        } else {
            echo trim($res);
        }
    }

    public function importSelectedData() {
        if (empty($this->session->userdata('acc_token'))) {
            $this->generateNewToken();
        }
        $json1_keys = ['cmr_start_date', 'cod_parafa', 'data_nastere', 'tara', 'id', 'pensionar', 'prenume', 'loc_nastere_tara', 'cmr_start_cmj', 'cuim', 'cuim_nou', 'loc_nastere_localitate', 'nume_anterior', 'nume', 'initiala', 'cmr_juramant_date', 'sex', 'status', 'cmj_start_date', 'promotie', 'cnp', 'email', 'telefon'];
        $cert_all = array();

        $filteredData = $this->input->post('filteredData');
        $filteredDataArr = json_decode($filteredData, TRUE);
        foreach ($filteredDataArr as $key => $sd) {
//            echo $sd['id'] . '<br>';
            $url = "https://api.cmb.comprami.ro/api/v2/medic/" . $sd['id'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_GET, 1);
            $authorization = "Authorization: Bearer " . $this->session->userdata('acc_token');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            $res = curl_exec($ch);
//            echo '<pre>';
//            print_r($res);
            $res_decoded = json_decode($res, TRUE);
            if ($res_decoded['msg'] == 'Token has expired') {
                $this->session->set_userdata('acc_token', '');
                $this->importSelectedData();
            } else {
                $medic = $res_decoded['medic'];
                $json1 = array_filter($medic, function($k) use ($json1_keys) {
                    return in_array($k, $json1_keys);
                }, ARRAY_FILTER_USE_KEY);
                //Call JSON1 (single)insert here
                $json1_id = $this->Dashboard_model->add_json1($json1);

                $pu = $medic['preg_postuniv'];
                for ($i = 0; $i < count($pu); $i++) {
                    unset($pu[$i]['versions']);
                    unset($pu[$i]['date_end']);
                    $pu[$i]['idp'] = $pu[$i]['id'];
                    unset($pu[$i]['id']);
                }
//                print_r($pu);
                //PPU ready for Batch insert
                if (!empty($pu)) {
                    $this->Dashboard_model->add_ppu_batch($pu);
                }

                $tdc = $medic['titlu_de_calificare'];
//                echo '<br>tdc cnt: '.count($tdc);
//                for ($i = 0; $i < count($tdc); $i++) {                    
                unset($tdc['versions']);
                $tdc['idf'] = $tdc['id'];
                unset($tdc['id']);
//                }
                //TDC ready for Batch insert
                if (!empty($tdc)) {
                    $this->Dashboard_model->add_tdc($tdc);
                }

                $cert = $medic['certificat'];
                foreach ($cert as $key => $v) {
                    $temp_cert = array();
                    $temp_cert['medic'] = $v['medic'];
                    $cnt = $v['continut'];
                    $temp_cert['certificat_tip'] = $cnt['certificat']['tip'];
                    $temp_cert['certificat_numar'] = $cnt['certificat']['numar'];
                    $temp_cert['certificat_data'] = $cnt['certificat']['data'];
                    $temp_cert['cuim'] = $cnt['cuim'];
                    $temp_cert['judet'] = $cnt['judet'];
                    $temp_cert['act_identitate_tip'] = $cnt['act_identitate']['tip'];
                    $temp_cert['act_identitate_cnp'] = $cnt['act_identitate']['cnp'];
                    $temp_cert['act_identitate_serie'] = $cnt['act_identitate']['serie'];
                    $temp_cert['act_identitate_numar'] = $cnt['act_identitate']['numar'];
                    $temp_cert['nume'] = $cnt['nume'];
                    $temp_cert['titlu_de_calificare_id'] = $cnt['titlu_de_calificare']['id'];
                    $temp_cert['titlu_de_calificare_facultate'] = $cnt['titlu_de_calificare']['facultate'];
                    $temp_cert['titlu_de_calificare_promotie'] = $cnt['titlu_de_calificare']['promotie'];
                    $temp_cert['titlu_de_calificare_fdt'] = $cnt['titlu_de_calificare']['fac_diploma_tip'];
                    $temp_cert['titlu_de_calificare_fdn'] = $cnt['titlu_de_calificare']['fac_diploma_numar'];
                    $temp_cert['titlu_de_calificare_fds'] = $cnt['titlu_de_calificare']['fac_diploma_serie'];
                    $temp_cert['data_juramant'] = $cnt['data_juramant'];
                    $temp_cert['cmj_presedinte'] = $cnt['cmj_presedinte'];
                    $temp_cert['date_end'] = $v['date_end'];
                    $temp_cert['idcert'] = $v['id'];
                    $temp_cert['status'] = $v['status'];
                    $temp_cert['date_start'] = $v['date_start'];
                    $cert_all[] = $temp_cert;

                    $avize = $v['avizari'];
                    foreach ($avize as $a) {
                        $avize_all = array();
                        $temp_avize = array();
                        $temp_avize['idcert'] = $a['certificat'];
                        $a_cnt = $a['continut'];

                        $temp_avize['registru'] = $a_cnt['registru'];
                        $temp_avize['date_end'] = $a['date_end'];
                        $temp_avize['idav'] = $a['id'];

                        $a_spec_medic = $a_cnt['specialitati_medicale'];
                        foreach ($a_spec_medic as $single) {
                            $temp_avize['idp'] = $single['id'];
                            $temp_avize['nume'] = $single['nume'];
                            $temp_avize['grad'] = $single['grad'];
                            $temp_avize['grup_spec'] = $single['grup_spec'];
                            $temp_avize['tip'] = $single['tip'];
                            $temp_avize['dlp_tip'] = $single['dlp_tip'];
                            $temp_avize['valid'] = $single['valid'];
                            $temp_avize['asigurare_nr'] = $single['asigurare_nr'];
                            $temp_avize['asigurare_serie'] = $single['asigurare_serie'];
                            $temp_avize['asigurare_emitent'] = $single['asigurare_emitent'];
                            $temp_avize['asigurare_date_start'] = $single['asigurare_date_start'];
                            $temp_avize['asigurare_date_end'] = $single['asigurare_date_end'];
                            $avize_all[] = $temp_avize;
                        }
                        $a_spec_chiru = $a_cnt['specialitati_chirurgicale'];
                        foreach ($a_spec_chiru as $single) {
                            $temp_avize['idp'] = $single['id'];
                            $temp_avize['nume'] = $single['nume'];
                            $temp_avize['grad'] = $single['grad'];
                            $temp_avize['grup_spec'] = $single['grup_spec'];
                            $temp_avize['tip'] = $single['tip'];
                            $temp_avize['dlp_tip'] = $single['dlp_tip'];
                            $temp_avize['valid'] = $single['valid'];
                            $temp_avize['asigurare_nr'] = $single['asigurare_nr'];
                            $temp_avize['asigurare_serie'] = $single['asigurare_serie'];
                            $temp_avize['asigurare_emitent'] = $single['asigurare_emitent'];
                            $temp_avize['asigurare_date_start'] = $single['asigurare_date_start'];
                            $temp_avize['asigurare_date_end'] = $single['asigurare_date_end'];
                            $avize_all[] = $temp_avize;
                        }
                        $a_spec_para = $a_cnt['specialitati_paraclinice'];
                        foreach ($a_spec_para as $single) {
                            $temp_avize['idp'] = $single['id'];
                            $temp_avize['nume'] = $single['nume'];
                            $temp_avize['grad'] = $single['grad'];
                            $temp_avize['grup_spec'] = $single['grup_spec'];
                            $temp_avize['tip'] = $single['tip'];
                            $temp_avize['dlp_tip'] = $single['dlp_tip'];
                            $temp_avize['valid'] = $single['valid'];
                            $temp_avize['asigurare_nr'] = $single['asigurare_nr'];
                            $temp_avize['asigurare_serie'] = $single['asigurare_serie'];
                            $temp_avize['asigurare_emitent'] = $single['asigurare_emitent'];
                            $temp_avize['asigurare_date_start'] = $single['asigurare_date_start'];
                            $temp_avize['asigurare_date_end'] = $single['asigurare_date_end'];
                            $avize_all[] = $temp_avize;
                        }
                        
                        //AVIZE_ALL ready for batch insert
                        if (!empty($avize_all)) {
                            $this->Dashboard_model->add_avize_batch($avize_all);
                        }
                    }
                }
                //CERT_ALL ready for batch insert
                if (!empty($cert_all)) {
                    $this->Dashboard_model->add_cert_batch($cert_all);
                }

                $titl_uri = $medic['alte_titluri'];
                for ($i = 0; $i < count($titl_uri); $i++) {
                    $titl_uri[$i]['idt'] = $titl_uri[$i]['id'];
                    unset($titl_uri[$i]['id']);
                }
//                echo "----------------------------------";
//                print_r($titl_uri);
                //Alte Titl URI ready for Batch insert
                if (!empty($titl_uri)) {
                    $this->Dashboard_model->add_titl_uri_batch($titl_uri);
                }
            }
        }
    }

    public function generateNewToken() {
        $url = "https://api.cmb.comprami.ro/api/v2/autentificare/login";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"email": "catalin.toma@gmail.com","password": "ffSb94aQZvLGE7o6"}');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));
        $res = curl_exec($ch);

        $res_decoded = json_decode($res, TRUE);
        $this->session->set_userdata('acc_token', $res_decoded['access_token']);
        $this->session->set_userdata('refresh_token', $res_decoded['refresh_token']);
    }
}
