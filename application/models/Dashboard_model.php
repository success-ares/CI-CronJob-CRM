<?php

class Dashboard_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*
     * function to add new JSON!
     */

    function add_json1($params) {
        // print_r($params['id']); exit;
        // print_r(array_slice($params,0,1)); exit;
        // print_r(is_array($params[0])); exit;
        // print_r($params); exit;
        if(is_array($params[0])==1){
            foreach($params as $param){
                
                
                $check = array_slice($param,0,1);
            $checkquery = $this->db->get_where('api_date_membru',$check);
            $checkcount = $checkquery->num_rows();
            // print_r($checkcount); exit;
            if($checkcount === 0) {
                $this->db->insert('api_date_membru', $param);
                return $this->db->insert_id();
            }
            else {
                
                $remember = $param['cuim'];
                date_default_timezone_set('Europe/Moscow');
                $currenttime = date('Y-m-d H:i:s');
                
                $updateData = array('task_updated'=>'1','task_timestamp'=>$currenttime);
                $this->db->where('task_cuim', $remember);
                $this->db->update('api_update_tasks', $updateData);
                
                
                
                $this->db->delete('api_date_membru', $check);
                $this->db->insert('api_date_membru', $param);
                return $this->db->insert_id();
            }
                
                
                
                
                
                
                // $query = $this->db->get_where('api_date_membru', $param);
        
                // $count = $query->num_rows();

                // if ($count === 0) {
                //  $this->db->insert('api_date_membru', $param);
                //     return $this->db->insert_id();
                // }
                // else {
                //     return -1;
                // }
            }
        } else {
            // print_r(1); exit;
            $check = array_slice($params,0,1);
            $checkquery = $this->db->get_where('api_date_membru',$check);
            $checkcount = $checkquery->num_rows();
            // print_r($checkcount); exit;
            if($checkcount === 0) {
                $this->db->insert('api_date_membru', $params);
                return $this->db->insert_id();
            }
            else {
                $remember = $params['cuim'];
                date_default_timezone_set('Europe/Moscow');
                $currenttime = date('Y-m-d H:i:s');
                
                $updateData = array('task_updated'=>'1','task_timestamp'=>$currenttime);
                $this->db->where('task_cuim', $remember);
                $this->db->update('api_update_tasks', $updateData);
                
                
                $this->db->delete('api_date_membru', $check);
                $this->db->insert('api_date_membru', $params);
                return $this->db->insert_id();
            }
            
            
            
            
            
            // $query = $this->db->get_where('api_date_membru', $params);
        
            // $count = $query->num_rows();

            // if ($count === 0) {
            //     $this->db->insert('api_date_membru', $params);
            //     return $this->db->insert_id();
            // }
            // else {
            //     return -1;
            // }
            
            
        }
        
        // $this->db->insert('api_date_membru', $params);
        //return $this->db->insert_id();
    }
    
    /*
     * function to add new PPU batch
     */

    function add_ppu_batch($params) {
        if(is_array($params[0])==1){
            foreach ($params as $param) {
            $query = $this->db->get_where('api_ppu', $param);
            $count = $query->num_rows();
            if ($count === 0) {
                $this->db->insert('api_ppu', $param);
                
            }
        }
        }
        else{
            
            $query = $this->db->get_where('api_ppu', $params);
            $count = $query->num_rows();
            if ($count === 0) {
                $this->db->insert('api_ppu', $params);
                
            }
        
        }
    }
    
    /*
     * function to add new TDC SINGLE
     */

    function add_tdc($params) {
        if(is_array($params[0]==1)){
            foreach ($params as $param) {
            $query = $this->db->get_where('api_fac', $param);
            $count = $query->num_rows();
            if ($count === 0) {
                $this->db->insert('api_fac', $param);
                //return $this->db->insert_id();
            }
         }
        }
        else {
            $query = $this->db->get_where('api_fac', $params);
            $count = $query->num_rows();
            if ($count === 0) {
                $this->db->insert('api_fac', $params);
                //return $this->db->insert_id();
            }
        }
        // print_r($params); exit;
        // print_r(is_array($params[0])); exit;
        
        //$this->db->insert('api_fac', $params);
        //return $this->db->insert_id();
    }
    
    /*
     * function to add new TDC batch
     */

    function add_tdc_batch($params) {
        $this->db->insert_batch('api_fac', $params);
    }
    
    /*
     * function to add new CERT batch
     */

    function add_cert_batch($params) {
        // print_r($params); exit;
        
         if(is_array($params[0])==1){
             foreach ($params as $param) {
                 
                 
                 $check = array_slice($param,20,1);
                    // print_r($check); exit;
            $checkquery = $this->db->get_where('api_cert',$check);
            $checkcount = $checkquery->num_rows();
            // print_r($checkcount); exit;
            if($checkcount === 0) {
                $this->db->insert('api_cert', $param);
                // return $this->db->insert_id();
            }
            else {
                $this->db->delete('api_cert', $check);
                $this->db->insert('api_cert', $param);
                // return $this->db->insert_id();
            }
                    
                    
                    
                 
                 
            // $query = $this->db->get_where('api_cert', $param);
            // $count = $query->num_rows();
            // if ($count === 0) {
            //     $this->db->insert('api_cert', $param);
            //     //return $this->db->insert_id();
            // }
        }
         }else {
             $check = array_slice($params,20,1);
                    // print_r($check); exit;
            $checkquery = $this->db->get_where('api_cert',$check);
            $checkcount = $checkquery->num_rows();
            // print_r($checkcount); exit;
            if($checkcount === 0) {
                $this->db->insert('api_cert', $params);
                // return $this->db->insert_id();
            }
            else {
                $this->db->delete('api_cert', $check);
                $this->db->insert('api_cert', $params);
                // return $this->db->insert_id();
            }
         }
        
        
        //$this->db->insert_batch('api_cert', $params);
    }
    
    /*
     * function to add new AVIZE batch
     */

    function add_avize_batch($params) {
        // print_r($params); exit;
        
        if(is_array($params[0])==1){
            
            foreach ($params as $param) {
                
                
                $check = array_slice($param,3,2);
                    // print_r($check); exit;
            $checkquery = $this->db->get_where('api_avize',$check);
            $checkcount = $checkquery->num_rows();
            // print_r($checkcount); exit;
            if($checkcount === 0) {
                $this->db->insert('api_avize', $param);
                // return $this->db->insert_id();
            }
            else {
                $this->db->delete('api_avize', $check);
                $this->db->insert('api_avize', $param);
                // return $this->db->insert_id();
            }
                
                
                
                
                
                
                
                
            // $query = $this->db->get_where('api_avize', $param);
            // $count = $query->num_rows();
            // if ($count === 0) {
            //     $this->db->insert('api_avize', $param);
            //     //return $this->db->insert_id();
            // }
        }
        }else {
            
            
            $check = array_slice($params,3,2);
                    // print_r($check); exit;
            $checkquery = $this->db->get_where('api_avize',$check);
            $checkcount = $checkquery->num_rows();
            // print_r($checkcount); exit;
            if($checkcount === 0) {
                $this->db->insert('api_avize', $params);
                // return $this->db->insert_id();
            }
            else {
                $this->db->delete('api_avize', $check);
                $this->db->insert('api_avize', $params);
                // return $this->db->insert_id();
            }
            
            
            
            
            
            // $query = $this->db->get_where('api_avize', $params);
            // $count = $query->num_rows();
            // if ($count === 0) {
            //     $this->db->insert('api_avize', $params);
            //     //return $this->db->insert_id();
            // }
        }
        
        //$this->db->insert_batch('api_avize', $params);
    }
    
    /*
     * function to add new TITL URI  batch
     */

    function add_titl_uri_batch($params) {
        if(is_array($params[0])==1){
            foreach ($params as $param) {
            $query = $this->db->get_where('api_titluri', $param);
            $count = $query->num_rows();
            if ($count === 0) {
                $this->db->insert('api_titluri', $param);
                //return $this->db->insert_id();
            }
        }
        } else {
            $query = $this->db->get_where('api_titluri', $params);
            $count = $query->num_rows();
            if ($count === 0) {
                $this->db->insert('api_titluri', $params);
                //return $this->db->insert_id();
            }
        }
         
        
        //$this->db->insert_batch('api_titluri', $params);
    }

}
