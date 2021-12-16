<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct(){
        parent::__construct();
    }

	public function index()
	{
        //$this->load->model('login_model');
        //$this->load->view('templates/template_login.php');
        if($this->input->is_ajax_request()){

        }else{
                $this->load->helper('form');
                $dados['pagina']        = 'dashboard/index.php';
                $dados['nome_pagina']   = 'Dashboard';
                $dados["link_pagina"]   = 'dashboard';
                $this->load->view('templates/template_padrao.php',$dados);   
        }
	}

    public function percentuaisGeraisOcupacao(){
        if($this->input->is_ajax_request()){
            $this->load->model('dashboard_model');
            return json_encode($this->dashboard_model->percentuaisGeraisOcupacao());
        }
    }

       
}
