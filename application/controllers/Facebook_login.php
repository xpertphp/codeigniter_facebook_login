<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Facebook_login extends CI_Controller { 
    function __construct() { 
        parent::__construct(); 		
        $this->load->library('facebook');  
        $this->load->model('facebook_model'); 
    } 
     
    public function index(){ 
        $addData = array(); 
        if($this->facebook->is_authenticated()){ 
            $fbUserData = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,picture'); 
 
            $addData['oauth_provider'] = 'facebook'; 
            $addData['oauth_uid']    = !empty($fbUserData['id'])?$fbUserData['id']:'';; 
            $addData['first_name']    = !empty($fbUserData['first_name'])?$fbUserData['first_name']:''; 
            $addData['last_name']    = !empty($fbUserData['last_name'])?$fbUserData['last_name']:''; 
            $addData['email']        = !empty($fbUserData['email'])?$fbUserData['email']:''; 
            $addData['gender']        = !empty($fbUserData['gender'])?$fbUserData['gender']:''; 
            $addData['picture_url']    = !empty($fbUserData['picture']['data']['url'])?$fbUserData['picture']['data']['url']:''; 
            $addData['link_url']        = !empty($fbUserData['link'])?$fbUserData['link']:'https://www.facebook.com/'; 
            $userID = $this->facebook_model->checkUser($addData); 
            if(!empty($userID)){ 
                $data['fbData'] = $addData; 
                $this->session->set_userdata('fbData', $addData); 
            }else{ 
               $data['fbData'] = array(); 
            }              
            $data['logoutURL'] = $this->facebook->logout_url(); 
        }else{ 
            $data['authURL'] =  $this->facebook->login_url(); 
        } 
        $this->load->view('facebook_login',$data); 
    } 
 
    public function logout() { 
        $this->facebook->destroy_session(); 
        $this->session->unset_userdata('fbData'); 
        redirect('facebook'); 
    } 
}
?>