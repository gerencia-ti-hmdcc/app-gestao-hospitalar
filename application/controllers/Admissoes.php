<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admissoes extends MY_Controller {

    public function __construct(){
        parent::__construct();
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
        $dados['pagina']        = 'admissoes/index.php';
        $dados['nome_pagina']   = 'Gestão de Admissões';
        $dados["link_pagina"]   = 'admissoes';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];
        // include 'Calendario.php';
        // $calendario = new Calendario();
        // $calendario->add_event('Holiday', '2022-12-08');
        // $calendario->add_event('Holiday', '2022-12-08');
        // $calendario->add_event('Holiday', '2022-12-08');
        // $calendario->add_event('Teste', '2022-12-08',7);
        // $calendario->add_event('Teste', '2022-12-08',1,'red');
        // $dados["calendario1"] = $calendario;
        if(isset($_GET["a"]) && isset($_GET["m"])){
            $dados["ano_calendario"] = $_GET["a"];
            $dados["mes_calendario"] = $_GET["m"];
        }else{
            $dados["ano_calendario"] = 0;
            $dados["mes_calendario"] = 0;
        }
        

        include 'Calendar.php';
        $events = array();
        
        $this->load->model('admissoes_model');
        $admissoes_mes = $this->admissoes_model->retornaQuantAdmissoesMes($dados["mes_calendario"],$dados["ano_calendario"]);
        if(count($admissoes_mes)>0){
            for($i=0;$i<count($admissoes_mes);$i++){
                if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
                    $html_texto = "Internas: ".$admissoes_mes[$i]['QUANTIDADE']."<br />";
                }else if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
                    $html_texto = "Externas: ".$admissoes_mes[$i]['QUANTIDADE']."<br />";
                }else if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="HD"){
                    $html_texto = "HD: ".$admissoes_mes[$i]['QUANTIDADE']."<br />";
                }
                $events[] = array(
                    'start' => $admissoes_mes[$i]['ANO_REFERENCIA']."-".$admissoes_mes[$i]['MES_REFERENCIA']."-".$admissoes_mes[$i]['DIA_REFERENCIA'],
                    'end' => $admissoes_mes[$i]['ANO_REFERENCIA']."-".$admissoes_mes[$i]['MES_REFERENCIA']."-".$admissoes_mes[$i]['DIA_REFERENCIA'],
                    'summary' => $html_texto,
                    'mask' => false
                );
            }
        }


        // $events[] = array(
        //     'start' => '2022-12-22',
        //     'end' => '2022-12-22',
        //     'summary' => 'Teste<br />',
        //     'mask' => true
        // );
        // $events[] = array(
        //     'start' => '2022-12-22',
        //     'end' => '2022-12-22',
        //     'summary' => 'Teste 2',
        //     'mask' => true
        // );
        $calendar = new Calendar();
        // $calendar->addEvent('2022-12-22 14:00','2022-12-22 15:00','Evento',true);
        $calendar->addEvents($events);
        $calendar->useFullDayNames();
        //$calendar->useInitialDayNames();
        $dados["calendario"] = $calendar;
        $this->load->view('templates/template_padrao.php',$dados);   
	}

    public function meses(){
        $usuario  = $_SESSION["usuario_logado"];
        // if($usuario["TIPO_PERFIL"]=="P"){
        //     $dados["mostrar_menus"]      = 0;
        //     $dados["tamanho_grafico"]   = "250";
        // }else{
        //     $dados["mostrar_menus"]      = 1;
        //     $dados["tamanho_grafico"]   = "300";
        // }
        $this->load->helper('form');
        $dados['pagina']        = 'admissoes/meses.php';
        $dados['nome_pagina']   = 'Gestão de Admissões';
        $dados["link_pagina"]   = 'admissoes/meses';
        $dados["diretorio_raiz"]    = '../';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];
    
        
        include 'Calendar.php';
        $events = array();
        
        $calendar = new Calendar();
        // $calendar->addEvent('2022-12-22 14:00','2022-12-22 15:00','Evento',true);
        // $calendar->useFullDayNames();
        $calendar->useInitialDayNames();
        $dados["calendario"] = $calendar;
        $this->load->view('templates/template_padrao.php',$dados);   
    }

    // public function percentuaisGeraisOcupacao(){
    //     if($this->input->is_ajax_request()){
    //         $this->load->model('dashboard_model');
    //         print json_encode($this->dashboard_model->percentuaisGeraisOcupacao());
    //     }
    // }

    // public function percentuaisSetorOcupacao(){
    //     $id_area = $this->input->post("id_area");
    //     if(strlen($id_area)>0){
    //         $this->load->model('dashboard_model');
    //         //print_r($this->dashboard_model->percentuaisSetorOcupacao($id_area));
    //         print json_encode($this->dashboard_model->percentuaisSetorOcupacao($id_area));
    //     }else{
    //         $this->session->set_flashdata("danger","Houve um erro inesperado. Atualize a página!");
    //         return;
    //     }
    // }

    // public function percentuaisSetorOcupacaoClinicaCirurgica(){
    //     $id_area = $this->input->post("id_area");
    //     if(strlen($id_area)>0){
    //         $this->load->model('dashboard_model');
    //         //print_r($this->dashboard_model->percentuaisSetorOcupacao($id_area));
    //         print json_encode($this->dashboard_model->percentuaisSetorOcupacao($id_area));
    //     }else{
    //         $this->session->set_flashdata("danger","Houve um erro inesperado. Atualize a página!");
    //         return;
    //     }
    // }

    // public function retornaSetorLoopPainel(){
    //     $this->load->model("dashboard_model");
    //     $atual = $this->input->post("atual");
    //     $setores_painel = $this->dashboard_model->retornaSetorLoopPainel();
    //     for($i = 0;$i<count($setores_painel);$i++){
    //         if($setores_painel[$i]["NR_SETOR"]==$atual){
    //             $anterior   = $setores_painel[$i]["NR_SETOR"];
    //             //$proximo    = $setores_painel[$i+1]["NR_SETOR"];
    //             if($i+1==count($setores_painel)){
    //                 $proximo = $setores_painel[0]["NR_SETOR"];
    //             }else{
    //                 $proximo    = $setores_painel[$i+1]["NR_SETOR"];
    //             } 
    //         }
    //     }
    //     //exit("$anterior | $proximo");
    //     //$atualizado = $this->dashboard_model->atualizaSetorLoopPainel($anterior,$proximo);
    //     //if($atualizado==true){
    //     if($proximo){
    //         print json_encode(array("PROXIMO"=>$proximo));
    //     }
    // }

    // function atualizarVariavelPainelControleSessao(){
    //     $proximo = $this->input->post("proximo");
    //     $usuario = $this->session->userdata("usuario_logado");
    //     $usuario["painel_variavel_controle"] = $proximo;
    //     $this->session->set_userdata("usuario_logado",$usuario);
    //     print json_encode(array("RES"=>"OK"));
    // }

       
}
