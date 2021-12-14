<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
        //$this->load->model('login_model');
        //$this->load->view('templates/template_login.php');
        if($this->input->is_ajax_request()){

        }else{
                $this->load->helper('form');
                $dados['pagina'] = 'dashboard/index.php';
                $dados['nome_pagina'] = 'Dashboard';
                $this->load->view('templates/template_padrao.php',$dados);   
        }
	}

       
}
