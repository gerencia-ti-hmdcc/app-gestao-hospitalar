<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $usuario = $this->session->userdata("usuario_logado");
        if(!$usuario){
            header('Location: '.base_url('../').'login/logout');
        }else{
            $funcao_permitida = $this->my_model->retornaSeFuncaoPermitida($usuario["ID"],'dashboard');
            if(!count($funcao_permitida)>0){
                header('Location: '.base_url('../app/').'login/logout');
            }
        }
    }

	public function index()
	{
        $usuario  = $_SESSION["usuario_logado"];
        if($usuario["TIPO_PERFIL"]=="P"){
            $dados["mostrar_menus"]      = 0;
            $dados["tamanho_grafico"]   = "250";
        }else{
            $dados["mostrar_menus"]      = 1;
            $dados["tamanho_grafico"]   = "300";
        }
        $this->load->helper('form');
        $dados['pagina']        = 'dashboard/index.php';
        $dados['nome_pagina']   = 'Ocupação Hospitalar';
        $dados["link_pagina"]   = 'dashboard';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];
        if($dados["tipo_perfil"]=="P"){
            $this->load->model('dashboard_model');
            $ultimo_usuario_painel = $this->dashboard_model->retornaUltimoSetorGeralAtivo();
            $dados["setor_ultimo_painel"] = $ultimo_usuario_painel["NR_SETOR"];
        }
        $this->load->view('templates/template_padrao.php',$dados);   
	}

    public function percentuaisGeraisOcupacao(){
        if($this->input->is_ajax_request()){
            $this->load->model('dashboard_model');
            print json_encode($this->dashboard_model->percentuaisGeraisOcupacao());
        }
    }

    public function percentuaisSetorOcupacao(){
        $id_area = $this->input->post("id_area");
        if(strlen($id_area)>0){
            $this->load->model('dashboard_model');
            //print_r($this->dashboard_model->percentuaisSetorOcupacao($id_area));
            print json_encode($this->dashboard_model->percentuaisSetorOcupacao($id_area));
        }else{
            $this->session->set_flashdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function percentuaisSetorOcupacaoClinicaCirurgica(){
        $id_area = $this->input->post("id_area");
        if(strlen($id_area)>0){
            $this->load->model('dashboard_model');
            //print_r($this->dashboard_model->percentuaisSetorOcupacao($id_area));
            print json_encode($this->dashboard_model->percentuaisSetorOcupacao($id_area));
        }else{
            $this->session->set_flashdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function retornaSetorLoopPainel(){
        $this->load->model("dashboard_model");
        $atual = $this->input->post("atual");
        $setores_painel = $this->dashboard_model->retornaSetorLoopPainel();
        for($i = 0;$i<count($setores_painel);$i++){
            if($setores_painel[$i]["NR_SETOR"]==$atual){
                $anterior   = $setores_painel[$i]["NR_SETOR"];
                //$proximo    = $setores_painel[$i+1]["NR_SETOR"];
                if($i+1==count($setores_painel)){
                    $proximo = $setores_painel[0]["NR_SETOR"];
                }else{
                    $proximo    = $setores_painel[$i+1]["NR_SETOR"];
                } 
            }
        }
        //exit("$anterior | $proximo");
        //$atualizado = $this->dashboard_model->atualizaSetorLoopPainel($anterior,$proximo);
        //if($atualizado==true){
        if($proximo){
            print json_encode(array("PROXIMO"=>$proximo));
        }
    }

    function atualizarVariavelPainelControleSessao(){
        $proximo = $this->input->post("proximo");
        $usuario = $this->session->userdata("usuario_logado");
        $usuario["painel_variavel_controle"] = $proximo;
        $this->session->set_userdata("usuario_logado",$usuario);
        print json_encode(array("RES"=>"OK"));
    }

       
}
