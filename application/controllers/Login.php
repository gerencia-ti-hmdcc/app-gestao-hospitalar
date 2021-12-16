<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

        public function __construct(){
                parent::__construct();
        }

	public function index()
	{
                //$this->load->model('login_model');
                //$this->load->view('templates/template_login.php');
                if($this->session->userdata("usuario_logado")){
                        redirect('dashboard');
                }else{
                        $this->load->helper('form');
                        $this->load->view('login/index.php');   
                }
	}

        public function autenticar(){
                //exit("TESTE");
                $this->load->model("login_model");

                $data_atual     = date("Y-m-d H:i:s");
                $token          = md5(uniqid()."".date("YmdHis")."@".uniqid());
 		$validade       = date("Y-m-d H:i:s",strtotime("+5 days")); 

                $email          = $this->input->post("email");
                $senha          = $this->input->post("senha");
                $senha          = md5($senha);
                
                $usuario = $this->login_model->logar($email,$senha);
                if($usuario && isset($usuario)){
                        $this->session->unset_userdata("usuario_logado");
                        //exit(print_r($usuario));
                        $this->login_model->atualizaToken($usuario["ID"],$data_atual,$token,$validade);
                        $this->session->set_userdata("usuario_logado",$usuario);
                        $this->session->set_flashdata("success","Usuário logado!");
                        redirect('../dashboard');
                }else{
                        $this->session->set_flashdata("danger","Usuário ou senha inválido(s)!");
                        $this->load->helper('form');
                        redirect('../');
                }
        }

        public function logout(){
                $this->session->unset_userdata("usuario_logado");
                redirect('../');
        }
}
