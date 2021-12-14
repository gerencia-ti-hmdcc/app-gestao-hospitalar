<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
                //$this->load->model('login_model');
                //$this->load->view('templates/template_login.php');
                if($this->input->is_ajax_request()){

                }else{
                        $this->load->helper('form');
                        $this->load->view('login/index.php');   
                }
	}

        public function autenticar(){
                exit("TESTE");
        }
}
