<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detalhada extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("my_model");
        $usuario = $this->session->userdata("usuario_logado");
        if(!$usuario){
            header('Location: '.base_url('../').'login/logout');
        }else{
            //REDIRECIONA USUARIO QUE TENTA ACESSAR O MÓDULO DE OCUPAÇÃO DETALHADA QUE NÃO É ADM, GERENCIA OU DIRETORIA
            $perfil = $this->my_model->tipoPerfilUsuario($_SESSION["usuario_logado"]["ID"]);
            if(($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='A' || $perfil["TIPO_PERFIL"]!='A') &&
            ($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='D' || $perfil["TIPO_PERFIL"]!='D') &&
            ($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='E' || $perfil["TIPO_PERFIL"]!='E') &&
            ($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='I' || $perfil["TIPO_PERFIL"]!='I')/*&&
            ($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='G' || $perfil["TIPO_PERFIL"]!='G')*/ || $usuario["ID"]==31){
                header('Location: /app/dashboard');
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
        $this->load->model('detalhada_model');
        $dados["linhas_de_cuidado"] = $this->detalhada_model->retornaLinhasDeCuidado($usuario);
        $dados['pagina']            = 'ocupacao_detalhada/index.php';
        $dados['nome_pagina']       = 'Ocupação Detalhada';
        $dados["link_pagina"]       = 'detalhada';
        $dados["tipo_perfil"]       = $usuario["TIPO_PERFIL"];
        // if($dados["tipo_perfil"]=="P"){
        //     $this->load->model('dashboard_model');
        //     $ultimo_usuario_painel = $this->dashboard_model->retornaUltimoSetorGeralAtivo();
        //     $dados["setor_ultimo_painel"] = $ultimo_usuario_painel["NR_SETOR"];
        // }
        $this->load->view('templates/template_padrao.php',$dados);   
	}

    // public function percentuaisGeraisOcupacao(){
    //     if($this->input->is_ajax_request()){
    //         $this->load->model('dashboard_model');
    //         print json_encode($this->dashboard_model->percentuaisGeraisOcupacao());
    //     }
    // }

    public function setores(){
        $linha = $_GET["l"];
        if($linha){
            if(trim(strlen($linha))>0){
                $this->load->model('detalhada_model');
                $dados["setores"]           = $this->detalhada_model->retornaSetoresPorLinha($linha);
                $dados["linha_cuidado"]     = $this->detalhada_model->retornaDadosLinhaCuidado($linha);
                $dados['pagina']            = 'ocupacao_detalhada/setores/index.php';
                $dados['nome_pagina']       = 'Setores';
                $dados["link_pagina"]       = 'setores';
                $dados["diretorio_raiz"]    = '../';
                $this->load->view('templates/template_padrao.php',$dados);   
            }else{
                header('Location: '.base_url('../').'detalhada');
            }
        }else{
            header('Location: '.base_url('../').'detalhada');
        }
    }

    public function leitos(){
        $cd_setor_atendimento   = $_GET["s"];
        $linha                  = $_GET["l"];

        if($linha && $cd_setor_atendimento){
            if(trim(strlen($linha))>0 && trim(strlen($cd_setor_atendimento))>0){
                $this->load->model('detalhada_model');
                $dados["leitos"]            = $this->detalhada_model->retornaLeitosClassifSetor($linha,$cd_setor_atendimento);
                $dados["setor_atend"]       = $this->detalhada_model->retornaDadosSetorAtendimento($cd_setor_atendimento);

                for($i=0;$i<count($dados["leitos"]);$i++){  
                    $movimentacoes_atendimento  = $this->detalhada_model->retornaMovimentacoesAtendimento($dados["leitos"][$i]["nr_atendimento"]);
                    //GUARDANDO PERMANENCIA (DIAS) NA MESMA LINHA DE CUIDADO
                    $somatoria_dias_linha_cuidado_atendimento = 0;
                    for($k=count($movimentacoes_atendimento)-1;$k>=0;$k--){
                        if($movimentacoes_atendimento[$k]["cd_agrupamento"]==$_GET["l"]){
                            $somatoria_dias_linha_cuidado_atendimento = $movimentacoes_atendimento[$k]["qt_dias_unidade"] + $somatoria_dias_linha_cuidado_atendimento;
                        }else{
                            break;
                        }
                    }
                    $dados["leitos"][$i]["permanencia_linha_cuidado"] = $somatoria_dias_linha_cuidado_atendimento;
                }

                $dados['pagina']            = 'ocupacao_detalhada/setores/leitos/index.php';
                $dados['nome_pagina']       = 'Leitos';
                $dados["link_pagina"]       = 'leitos';
                unset($dados["diretorio_raiz"]);
                $dados["diretorio_raiz"]    = '../';
                $this->load->view('templates/template_padrao.php',$dados);   
            }else{
                header('Location: '.base_url('../').'detalhada');
            }
        }else{
            header('Location: '.base_url('../').'detalhada');
        }
    }

    public function retornaDadosLeito(){
        $nr_atendimento         = $this->input->post("nr_atendimento");
        $leito_atual            = $this->input->post("leito_atual");
        $cd_setor_atendimento   = $this->input->post("cd_setor_atendimento");

        if(strlen($nr_atendimento)>0 && strlen($leito_atual)>0 && strlen($cd_setor_atendimento)>0){
            $this->load->model('detalhada_model');
            print json_encode($this->detalhada_model->retornaDadosLeito($nr_atendimento,$leito_atual,$cd_setor_atendimento));
        }else{
            $this->session->set_flashdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function retornaMovimentacoesAtendimento(){
        $nr_atendimento         = $this->input->post("nr_atendimento");

        if(strlen($nr_atendimento)>0){
            $this->load->model('detalhada_model');
            print json_encode($this->detalhada_model->retornaMovimentacoesAtendimento($nr_atendimento));
        }else{
            $this->session->set_flashdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

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
