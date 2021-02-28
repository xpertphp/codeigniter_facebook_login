<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook_model extends CI_Model {
	
    public	function __construct() {
        parent::__construct();
        $this->load->database();
    }
   
    public function checkUser($data = array()){
        if(!empty($data)){
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where(array('oauth_provider'=>$data['oauth_provider'], 'oauth_uid'=>$data['oauth_uid']));
            $query = $this->db->get();
            $total_user = $query->num_rows();
            
            if($total_user > 0){
                $result = $query->row_array();
                $data['updated_at'] = date("Y-m-d H:i:s");
                $update = $this->db->update('users', $data, array('id' => $result['id']));
                $userID = $result['id'];
            }else{
                $data['created_at']  = date("Y-m-d H:i:s");
                $data['updated_at'] = date("Y-m-d H:i:s");
                $insert = $this->db->insert('users', $data);
                $userID = $this->db->insert_id();
            }
        }
        return $userID?$userID:FALSE;
    }
}
?>