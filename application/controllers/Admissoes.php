<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admissoes extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("my_model");
        $usuario = $this->session->userdata("usuario_logado");
        if(!$usuario){
            header('Location: '.base_url('../').'login/logout');
        }else{
            //REDIRECIONA USUARIO QUE TENTA ACESSAR O MÓDULO ADMISSOES QUE NÃO É ADM, GERENCIA OU DIRETORIA
            $perfil = $this->my_model->tipoPerfilUsuario($_SESSION["usuario_logado"]["ID"]);
            if(($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='A' || $perfil["TIPO_PERFIL"]!='A') &&
            ($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='D' || $perfil["TIPO_PERFIL"]!='D') &&
            ($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='G' || $perfil["TIPO_PERFIL"]!='G')){
                header('Location: /../dashboard');
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
        $dados['pagina']        = 'admissoes/index.php';
        $dados['nome_pagina']   = 'Gestão de Admissões';
        $dados["link_pagina"]   = 'admissoes';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];

        if(isset($_GET["a"]) && isset($_GET["m"])){
            $dados["ano_calendario"] = $_GET["a"];
            $dados["mes_calendario"] = $_GET["m"];
            $L = new DateTime($dados["ano_calendario"]."-".$dados["mes_calendario"]."-01"); 
            $dados["ultimo_dia_mes"] = $L->format('t');
            if($_GET["a"]==date("Y") && $_GET["m"]==date("m")){
                $dia_atual_realizado = (date("d")-1);
                if(date("m")<=4){
                    $dados["quadrimestre"]   = 1;
                }else if(date("m")>4 && date("m")<=8){
                    $dados["quadrimestre"]   = 2;
                }else if(date("m")>8 && date("m")<=12){
                    $dados["quadrimestre"]   = 3;
                }
            }else{
                $dia_atual_realizado = $dados["ultimo_dia_mes"];
                if($_GET["m"]<=4){
                    $dados["quadrimestre"]   = 1;
                }else if($_GET["m"]>4 && $_GET["m"]<=8){
                    $dados["quadrimestre"]   = 2;
                }else if($_GET["m"]>8 && $_GET["m"]<=12){
                    $dados["quadrimestre"]   = 3;
                }
            }
        }else{
            $dados["ano_calendario"] = 0;
            $dados["mes_calendario"] = 0;
            if(date("m")<=4){
                $dados["quadrimestre"]   = 1;
            }else if(date("m")>4 && date("m")<=8){
                $dados["quadrimestre"]   = 2;
            }else if(date("m")>8 && date("m")<=12){
                $dados["quadrimestre"]   = 3;
            }
            $dados["ultimo_dia_mes"] = date("t");
            $dia_atual_realizado = (date("d")-1);
        }
        $dados["dia_atual_realizado"] = $dia_atual_realizado;

        include 'Calendar.php';
        $calendar           = new Calendar();
        $events             = array();
        $posicao_ofertadas  = 0;
        
        $this->load->model('admissoes_model');
        $admissoes_mes  = $this->admissoes_model->retornaQuantAdmissoesMes($dados["mes_calendario"],$dados["ano_calendario"]);
        $ofertas_mes    = $this->admissoes_model->retornaTotaisOfertasPorDia($dados["mes_calendario"],$dados["ano_calendario"]);
        if(count($admissoes_mes)>0){
            for($i=0;$i<count($admissoes_mes);$i++){
                $html_texto         = "<span onclick='abrirModalInformacoes(".$admissoes_mes[$i]['DIA_REFERENCIA'].",".$admissoes_mes[$i]['MES_REFERENCIA'].",".$admissoes_mes[$i]['ANO_REFERENCIA'].")'>";
                $posicao_ofertadas  = array_search($admissoes_mes[$i]['DIA_REFERENCIA'],array_column($ofertas_mes,'dia'));
                
                if(count($ofertas_mes)>0){
                    if(isset($posicao_ofertadas) && $posicao_ofertadas>=0 && (!isset($ofertas_mes[$posicao_ofertadas]["ok"]) || $ofertas_mes[$posicao_ofertadas]["ok"] == 0)){
                        if($admissoes_mes[$i]['DIA_REFERENCIA']==$ofertas_mes[$posicao_ofertadas]["dia"]){
                            $html_texto .= "Ofertas: ".$ofertas_mes[$posicao_ofertadas]['quantidade']."<br />";
                            $ofertas_mes[$posicao_ofertadas]["ok"] = 1;
                        }
                    }
                }
                
                if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
                    $html_texto .=  "Internas: ".$admissoes_mes[$i]['QUANTIDADE'];
                }else if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
                    $html_texto .= "Externas: ".$admissoes_mes[$i]['QUANTIDADE']."<br />";
                }else if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="HD"){
                    $html_texto .= "HD: ".$admissoes_mes[$i]['QUANTIDADE']."<br />";
                }
                $html_texto .="</span>";
                $events[] = array(
                    'start' => $admissoes_mes[$i]['ANO_REFERENCIA']."-".$admissoes_mes[$i]['MES_REFERENCIA']."-".$admissoes_mes[$i]['DIA_REFERENCIA'],
                    'end' => $admissoes_mes[$i]['ANO_REFERENCIA']."-".$admissoes_mes[$i]['MES_REFERENCIA']."-".$admissoes_mes[$i]['DIA_REFERENCIA'],
                    'summary' => $html_texto,
                    'mask' => false
                );
            }
        }

        $totais_admissoes_mes           = $this->admissoes_model->retornaTotaisMesSemCTI($dados["mes_calendario"],$dados["ano_calendario"]);
        $dados["totais_admissoes_mes"]  = $totais_admissoes_mes;
        $totais_admissoes_mes_cti           = $this->admissoes_model->retornaTotaisMesCTI($dados["mes_calendario"],$dados["ano_calendario"]);
        $dados["totais_admissoes_mes_cti"]  = $totais_admissoes_mes_cti;

        $dados["admissoes_hd"]                   = 0;
        $dados["admissoes_internas_hd"]          = 0;
        $dados["admissoes_externas_hd"]          = 0;
        $dados["admissoes_internas_cti"]         = 0;
        $dados["admissoes_externas_cti"]         = 0;
        $dados["admissoes_internas_avc"]         = 0;
        $dados["admissoes_externas_avc"]         = 0;
        $dados["admissoes_internas_c_medica"]    = 0;
        $dados["admissoes_externas_c_medica"]    = 0;
        $dados["admissoes_internas_c_cirurgica"] = 0;
        $dados["admissoes_externas_c_cirurgica"] = 0;

        $dados["admissoes_internas_gerais"]      = 0;
        $dados["admissoes_externas_gerais"]      = 0;

        for($i=0; $i<count($totais_admissoes_mes);$i++){
            if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="HD"){
                // if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
                //     $dados["admissoes_internas_hd"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
                //     $dados["admissoes_internas_gerais"]  += $dados["admissoes_internas_hd"];
                // }else if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
                //     $dados["admissoes_externas_hd"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
                //     $dados["admissoes_externas_gerais"]  += $dados["admissoes_externas_hd"];
                // }
                $dados["admissoes_hd"]               += $totais_admissoes_mes[$i]["QUANTIDADE"];
                $dados["admissoes_externas_gerais"]  +=  $dados["admissoes_hd"];
            }else{
                if($totais_admissoes_mes[$i]["DESC_AGRUPAMENTO"]=="AVC"){
                    if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
                        $dados["admissoes_externas_avc"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
                        $dados["admissoes_externas_gerais"]  += $dados["admissoes_externas_avc"];
                    }else if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
                        $dados["admissoes_internas_avc"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
                        $dados["admissoes_internas_gerais"]  += $dados["admissoes_internas_avc"];
                    }
                }else if($totais_admissoes_mes[$i]["DESC_AGRUPAMENTO"]=="Clínica Médica"){
                    if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
                        $dados["admissoes_externas_c_medica"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
                        $dados["admissoes_externas_gerais"]  += $dados["admissoes_externas_c_medica"];
                    }else if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
                        $dados["admissoes_internas_c_medica"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
                        $dados["admissoes_internas_gerais"]  += $dados["admissoes_internas_c_medica"];
                    }
                }else if($totais_admissoes_mes[$i]["DESC_AGRUPAMENTO"]=="Clínica Cirúrgica"){
                    if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
                        $dados["admissoes_externas_c_cirurgica"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
                        $dados["admissoes_externas_gerais"]      += $dados["admissoes_externas_c_cirurgica"];
                    }else if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
                        $dados["admissoes_internas_c_cirurgica"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
                        $dados["admissoes_internas_gerais"]      += $dados["admissoes_internas_c_cirurgica"];
                    }
                }
            }
        }

        for($i=0; $i<count($totais_admissoes_mes_cti);$i++){
            if($totais_admissoes_mes_cti[$i]["IE_TIPO_ADMISSAO"]=="I"){
                $dados["admissoes_internas_cti"]     = $totais_admissoes_mes_cti[$i]["QUANTIDADE"];
                $dados["admissoes_internas_gerais"] += $dados["admissoes_internas_cti"];
            }else if($totais_admissoes_mes_cti[$i]["IE_TIPO_ADMISSAO"]=="E"){
                $dados["admissoes_externas_cti"]     = $totais_admissoes_mes_cti[$i]["QUANTIDADE"];
                $dados["admissoes_externas_gerais"] += $dados["admissoes_externas_cti"];
            }
        }

        // $dados["total_paciente_clinico"]     = ($dados["admissoes_externas_avc"] + $dados["admissoes_internas_avc"]) + ($dados["admissoes_internas_c_medica"] + $dados["admissoes_externas_c_medica"]); /* RETIRAR METAS INTERNAS DO CALCULO - JULIANA 01/2023*/
        $dados["total_clinica_medica"]       = $dados["admissoes_externas_c_medica"];
        $dados["total_paciente_critico"]     = $dados["admissoes_internas_cti"] + $dados["admissoes_externas_cti"];
        $dados["total_clinica_cirurgica"]    = $dados["admissoes_externas_c_cirurgica"] /*+ $dados["admissoes_internas_c_cirurgica"]*/;
        $dados["total_avc"]                  = ($dados["admissoes_externas_avc"] /*+ $dados["admissoes_internas_avc"]*/); 
        
        $dados["meta_admissoes_internas_cti"]               = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'I')[0]["QUANTIDADE"];
        $dados["meta_admissoes_externas_cti"]               = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'E')[0]["QUANTIDADE"];
        $dados["meta_admissoes_externas_clinica_medica"]    = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'E')[0]["QUANTIDADE"];
        $dados["meta_admissoes_externas_avc"]               = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],9,'E')[0]["QUANTIDADE"];
        $dados["meta_admissoes_externas_clinica_cirurgica"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],11,'E')[0]["QUANTIDADE"];
        $dados["meta_admissoes_hd"]                         = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],13,'E')[0]["QUANTIDADE"];
        
        // $dados["meta_total_paciente_clinico"]               = ($dados["meta_admissoes_internas_clinica_medica"] + $dados["meta_admissoes_externas_clinica_medica"]) + ($dados["meta_admissoes_externas_avc"]); /* RETIRAR METAS INTERNAS DO CALCULO - JULIANA 01/2023*/
        $dados["meta_total_clinica_medica"]                 = $dados["meta_admissoes_externas_clinica_medica"]; 
        $dados["meta_total_paciente_critico"]               = $dados["meta_admissoes_externas_cti"] + $dados["meta_admissoes_internas_cti"];
        $dados["meta_total_clinica_cirurgica"]              = $dados["meta_admissoes_externas_clinica_cirurgica"];
        $dados["meta_total_hd"]                             = $dados["meta_admissoes_hd"];
        $dados["meta_total_avc"]                            = $dados["meta_admissoes_externas_avc"];

        // $dados["porcentagem_geral_paciente_clinico"]     = ($dados["total_paciente_clinico"]/$dados["meta_total_paciente_clinico"]) * 100; /* RETIRAR METAS INTERNAS DO CALCULO - JULIANA 01/2023*/
        $dados["porcentagem_geral_clinica_medica"]      = ($dados["admissoes_externas_c_medica"]/$dados["meta_total_clinica_medica"]) * 100;
        $dados["porcentagem_geral_paciente_critico"]    = ($dados["total_paciente_critico"]/$dados["meta_total_paciente_critico"]) * 100;
        $dados["porcentagem_geral_clinica_cirurgica"]   = ($dados["total_clinica_cirurgica"]/$dados["meta_total_clinica_cirurgica"]) * 100;
        $dados["porcentagem_geral_hd"]                  = ($dados["admissoes_hd"]/$dados["meta_total_hd"]) * 100;
        $dados["porcentagem_avc"]                       = ($dados["admissoes_externas_avc"]/$dados["meta_total_avc"])*100;

        // if(($dados["mes_calendario"]==date("m") || $dados["mes_calendario"]==0) && ($dados["ano_calendario"]==date("Y") || $dados["ano_calendario"]==0)){
        $dados["ideal_realizado_atual_clm"] = (int)($dados["meta_total_clinica_medica"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
        if($dados["ideal_realizado_atual_clm"]==0) $dados["ideal_realizado_atual_clm"] = 1;
        $dados["porcentagem_realizado_clm"] = number_format(($dados["total_clinica_medica"]/$dados["ideal_realizado_atual_clm"])*100, 2,',','');
        if($dados["ideal_realizado_atual_clm"]==1) $dados["ideal_realizado_atual_clm"] = 0;

        $dados["ideal_realizado_atual_cti"] = (int)($dados["meta_total_paciente_critico"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
        if($dados["ideal_realizado_atual_cti"]==0) $dados["ideal_realizado_atual_cti"] = 1;
        $dados["porcentagem_realizado_cti"] = number_format(($dados["total_paciente_critico"]/$dados["ideal_realizado_atual_cti"])*100, 2,',','');
        if($dados["ideal_realizado_atual_cti"]==1) $dados["ideal_realizado_atual_cti"] = 0;

        $dados["ideal_realizado_atual_cir"] = (int)($dados["meta_total_clinica_cirurgica"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
        if($dados["ideal_realizado_atual_cir"]==0) $dados["ideal_realizado_atual_cir"] = 1;
        $dados["porcentagem_realizado_cir"] = number_format(($dados["admissoes_externas_c_cirurgica"]/$dados["ideal_realizado_atual_cir"])*100, 2,',','');
        if($dados["ideal_realizado_atual_cir"]==1) $dados["ideal_realizado_atual_cir"] = 0;

        $dados["ideal_realizado_atual_hd"]  = (int)($dados["meta_total_hd"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
        if($dados["ideal_realizado_atual_hd"]==0) $dados["ideal_realizado_atual_hd"] = 1;
        $dados["porcentagem_realizado_hd"]  = number_format(($dados["admissoes_hd"]/$dados["ideal_realizado_atual_hd"])*100, 2,',','');
        if($dados["ideal_realizado_atual_hd"]==1) $dados["ideal_realizado_atual_hd"] = 0;

        $dados["ideal_realizado_atual_avc"] = (int)($dados["meta_total_avc"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
        if($dados["ideal_realizado_atual_avc"]==0) $dados["ideal_realizado_atual_avc"] = 1;
        $dados["porcentagem_realizado_avc"] = number_format(($dados["admissoes_externas_avc"]/$dados["ideal_realizado_atual_avc"])*100, 2,',','');
        if($dados["ideal_realizado_atual_avc"]==1) $dados["ideal_realizado_atual_avc"] = 0;
        // }
        
        $calendar->addEvents($events);
        // $calendar->addEvent('2022-12-22 14:00','2022-12-22 15:00','Evento',true);
        // $calendar->useFullDayNames();
        $calendar->useInitialDayNames();
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
    
        if(isset($_GET["a"])){
            $dados["ano_calendario"] = $_GET["a"];
        }else{
            $dados["ano_calendario"] = 0;
        }

        include 'Calendar.php';
        $events = array();
        
        $calendar = new Calendar();
        // $calendar->addEvent('2022-12-22 14:00','2022-12-22 15:00','Evento',true);
        // $calendar->useFullDayNames();
        $calendar->useInitialDayNames();
        $dados["calendario"] = $calendar;
        $this->load->view('templates/template_padrao.php',$dados);   
    }

    public function anos(){
        $usuario  = $_SESSION["usuario_logado"];
        // if($usuario["TIPO_PERFIL"]=="P"){
        //     $dados["mostrar_menus"]      = 0;
        //     $dados["tamanho_grafico"]   = "250";
        // }else{
        //     $dados["mostrar_menus"]      = 1;
        //     $dados["tamanho_grafico"]   = "300";
        // }
        $this->load->helper('form');
        $dados['pagina']        = 'admissoes/anos.php';
        $dados['nome_pagina']   = 'Gestão de Admissões';
        $dados["link_pagina"]   = 'admissoes/anos';
        $dados["diretorio_raiz"]    = '../';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];
    
        if(isset($_GET["a"])){
            $dados["ano_calendario"] = $_GET["a"];
        }else{
            $dados["ano_calendario"] = 0;
        }

        // include 'Calendar.php';
        // $events = array();
        
        // $calendar = new Calendar();
        // $calendar->addEvent('2022-12-22 14:00','2022-12-22 15:00','Evento',true);
        // $calendar->useFullDayNames();
        // $calendar->useInitialDayNames();
        // $dados["calendario"] = $calendar;
        $this->load->view('templates/template_padrao.php',$dados);   
    }

    public function retornaDetalhesAdmissoesMes(){
        $dia = $this->input->post("dia");
        $mes = $this->input->post("mes");
        $ano = $this->input->post("ano");

        if(strlen($dia)>0 && strlen($mes)>0 && strlen($ano)>0){
            $this->load->model('admissoes_model');
            print json_encode($this->admissoes_model->retornaDetalhesAdmissoesMes($dia,$mes,$ano));
        }else{
            $this->session->set_flashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }

    public function retornaDetalhesAdmissoesMesPorLinha(){
        $dia            = $this->input->post("dia");
        $mes            = $this->input->post("mes");
        $ano            = $this->input->post("ano");
        $agrupamento    = $this->input->post("agrupamento");

        if(strlen($dia)>0 && strlen($mes)>0 && strlen($ano)>0){
            $this->load->model('admissoes_model');
            print json_encode($this->admissoes_model->retornaDetalhesAdmissoesMesPorLinha($dia,$mes,$ano,$agrupamento));
        }else{
            $this->session->set_flashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }

    public function retornaDetalhesOfertasDiarias(){
        $dia            = $this->input->post("dia");
        $mes            = $this->input->post("mes");
        $ano            = $this->input->post("ano");

        if(strlen($dia)>0 && strlen($mes)>0 && strlen($ano)>0){
            $this->load->model('admissoes_model');
            print json_encode($this->admissoes_model->retornaDetalhesOfertasDiarias($ano,$mes,$dia));
        }else{
            $this->session->set_flashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }


    public function admissoes_por_linha(){
        $usuario  = $_SESSION["usuario_logado"];
        if($usuario["TIPO_PERFIL"]=="P"){
            $dados["mostrar_menus"]      = 0;
            $dados["tamanho_grafico"]   = "250";
        }else{
            $dados["mostrar_menus"]      = 1;
            $dados["tamanho_grafico"]   = "300";
        }
        $this->load->helper('form');
        $dados['pagina']        = 'admissoes/admissoes_por_linha.php';
        $dados['nome_pagina']   = 'Gestão de Admissões';
        $dados["link_pagina"]   = 'admissoes/admissoes_por_linha';
        $dados["diretorio_raiz"]    = '../';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];

        // if(isset($_GET["a"]) && isset($_GET["m"])){
        //     $dados["ano_calendario"] = $_GET["a"];
        //     $dados["mes_calendario"] = $_GET["m"];
        // }else{
        //     $dados["ano_calendario"] = 0;
        //     $dados["mes_calendario"] = 0;
        // }

        if(isset($_GET["a"]) && isset($_GET["m"])){
            $dados["ano_calendario"] = $_GET["a"];
            $dados["mes_calendario"] = $_GET["m"];
            $L = new DateTime($dados["ano_calendario"]."-".$dados["mes_calendario"]."-01"); 
            $dados["ultimo_dia_mes"] = $L->format('t');
            if($_GET["a"]==date("Y") && $_GET["m"]==date("m")){
                $dia_atual_realizado = (date("d")-1);
                if(date("m")<=4){
                    $dados["quadrimestre"]   = 1;
                }else if(date("m")>4 && date("m")<=8){
                    $dados["quadrimestre"]   = 2;
                }else if(date("m")>8 && date("m")<=12){
                    $dados["quadrimestre"]   = 3;
                }
            }else{
                $dia_atual_realizado = $dados["ultimo_dia_mes"];
                if($_GET["m"]<=4){
                    $dados["quadrimestre"]   = 1;
                }else if($_GET["m"]>4 && $_GET["m"]<=8){
                    $dados["quadrimestre"]   = 2;
                }else if($_GET["m"]>8 && $_GET["m"]<=12){
                    $dados["quadrimestre"]   = 3;
                }
            }
        }else{
            $dados["ano_calendario"] = 0;
            $dados["mes_calendario"] = 0;
            if(date("m")<=4){
                $dados["quadrimestre"]   = 1;
            }else if(date("m")>4 && date("m")<=8){
                $dados["quadrimestre"]   = 2;
            }else if(date("m")>8 && date("m")<=12){
                $dados["quadrimestre"]   = 3;
            }
            $dados["ultimo_dia_mes"] = date("t");
            $dia_atual_realizado = (date("d")-1);
        }
        $dados["dia_atual_realizado"] = $dia_atual_realizado;

        if(isset($_GET["s"]) && strlen($_GET["s"])>0 && (int)$_GET["s"]>0){
            $dados["nr_agrupamento"] = $_GET["s"];
        }else{
            header('Location: ../admissoes/meses');
            die();
        }
        
        include 'Calendar.php';
        $calendar = new Calendar();
        $events = array();
        
        $this->load->model('admissoes_model');
        $admissoes_mes = $this->admissoes_model->retornaQuantAdmissoesMesPorLinha($dados["mes_calendario"],$dados["ano_calendario"],$dados["nr_agrupamento"]);
        if(count($admissoes_mes)>0){
            for($i=0;$i<count($admissoes_mes);$i++){
                $html_texto = "<span onclick='abrirModalInformacoes(".$admissoes_mes[$i]['DIA_REFERENCIA'].",".$admissoes_mes[$i]['MES_REFERENCIA'].",".$admissoes_mes[$i]['ANO_REFERENCIA'].",".$dados["nr_agrupamento"].")'>";
                if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
                    $html_texto .=  "Internas: ".$admissoes_mes[$i]['QUANTIDADE'];
                }else if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
                    $html_texto .= "Externas: ".$admissoes_mes[$i]['QUANTIDADE']."<br />";
                }else if($admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="HD"){
                    $html_texto .= "HD: ".$admissoes_mes[$i]['QUANTIDADE']."<br />";
                }
                $html_texto .="</span>";
                $events[] = array(
                    'start' => $admissoes_mes[$i]['ANO_REFERENCIA']."-".$admissoes_mes[$i]['MES_REFERENCIA']."-".$admissoes_mes[$i]['DIA_REFERENCIA'],
                    'end' => $admissoes_mes[$i]['ANO_REFERENCIA']."-".$admissoes_mes[$i]['MES_REFERENCIA']."-".$admissoes_mes[$i]['DIA_REFERENCIA'],
                    'summary' => $html_texto,
                    'mask' => false
                );
            }
        }
        

        $totais_admissoes_mes           = $this->admissoes_model->retornaTotaisMesPorLinha($dados["mes_calendario"],$dados["ano_calendario"],$dados["nr_agrupamento"]);
        $dados["totais_admissoes_mes"]  = $totais_admissoes_mes;
        

        $dados["admissoes_internas"]         = 0;
        $dados["admissoes_externas"]         = 0;
        $dados["admissoes_externas_gerais"]     = 0;
        $dados["admissoes_internas_gerais"]     = 0;

        for($i=0; $i<count($totais_admissoes_mes);$i++){
            if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
                $dados["admissoes_externas_gerais"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            }else{
                $dados["admissoes_internas_gerais"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            }
            // if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="HD"){
            //     $dados["admissoes_internas_hd"] = 0;
            //     if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
            //         $dados["admissoes_internas_hd"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            //     }
            //     $dados["admissoes_hd"]               = $totais_admissoes_mes[$i]["QUANTIDADE"];
            //     $dados["admissoes_hd"]               = $dados["admissoes_hd"] - $dados["admissoes_internas_hd"];
            //     $dados["admissoes_externas_gerais"]  +=  $dados["admissoes_hd"];
            // }else{
            //     if($totais_admissoes_mes[$i]["DESC_AGRUPAMENTO"]=="AVC"){
            //         if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
            //             $dados["admissoes_externas_avc"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            //             $dados["admissoes_externas_gerais"]  += $dados["admissoes_externas_avc"];
            //         }else if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
            //             $dados["admissoes_internas_avc"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            //             $dados["admissoes_internas_gerais"]  += $dados["admissoes_internas_avc"];
            //         }
            //     }else if($totais_admissoes_mes[$i]["DESC_AGRUPAMENTO"]=="Clínica Médica"){
            //         if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
            //             $dados["admissoes_externas_c_medica"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            //             $dados["admissoes_externas_gerais"]  += $dados["admissoes_externas_c_medica"];
            //         }else if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
            //             $dados["admissoes_internas_c_medica"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            //             $dados["admissoes_internas_gerais"]  += $dados["admissoes_internas_c_medica"];
            //         }
            //     }else if($totais_admissoes_mes[$i]["DESC_AGRUPAMENTO"]=="Clínica Cirúrgica"){
            //         if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="E"){
            //             $dados["admissoes_externas_c_cirurgica"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            //             $dados["admissoes_externas_gerais"]      += $dados["admissoes_externas_c_cirurgica"];
            //         }else if($totais_admissoes_mes[$i]["IE_TIPO_ADMISSAO"]=="I"){
            //             $dados["admissoes_internas_c_cirurgica"] = $totais_admissoes_mes[$i]["QUANTIDADE"];
            //             $dados["admissoes_internas_gerais"]      += $dados["admissoes_internas_c_cirurgica"];
            //         }
            //     }
            // }
        }

        $dados["meta_externa"] = 0;
        $dados["meta_interna"] = 0;

        $dados["total_realizado"] = $dados["admissoes_externas_gerais"];

        if($dados["nr_agrupamento"]==1){
            $dados["meta_interna"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'I')[0]["QUANTIDADE"];
            $dados["meta_externa"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'E')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "CTI";
            $dados["total_realizado"] = $dados["admissoes_externas_gerais"] + $dados["admissoes_internas_gerais"];
            $dados["porcentagem_geral"]         = (($dados["total_realizado"])/$dados["meta_externa"]) * 100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["admissoes_externas_gerais"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;

            $dados["ideal_realizado_interno"] = (int)($dados["meta_interna"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_interno"]==0) $dados["ideal_realizado_interno"] = 1;
            $dados["porcentagem_realizado_interno"] = number_format($dados["admissoes_internas_gerais"]/$dados["ideal_realizado_interno"]*100, 2,',','');
            if($dados["ideal_realizado_interno"]==1) $dados["ideal_realizado_interno"] = 0;
        }else if($dados["nr_agrupamento"]==9){
            $dados["meta_externa"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],9,'E')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "AVC";
            $dados["porcentagem_geral"]         = ($dados["admissoes_externas_gerais"]/$dados["meta_externa"])*100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["admissoes_externas_gerais"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;
        }else if($dados["nr_agrupamento"]==10){
            $dados["meta_externa"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'E')[0]["QUANTIDADE"];
            // $dados["meta_interna"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'I')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "Clínica Médica";
            $dados["porcentagem_geral"]         = ($dados["admissoes_externas_gerais"]/$dados["meta_externa"]) * 100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["admissoes_externas_gerais"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;
        }else if($dados["nr_agrupamento"]==11){
            $dados["meta_externa"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],11,'E')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "Clínica Cirúrgica";
            $dados["porcentagem_geral"]         = ($dados["admissoes_externas_gerais"]/$dados["meta_externa"]) * 100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["admissoes_externas_gerais"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;
        }else if($dados["nr_agrupamento"]==13){
            $dados["meta_externa"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],13,'E')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "Hospital Dia";
            $dados["total_realizado"] = $dados["admissoes_externas_gerais"] + $dados["admissoes_internas_gerais"];
            $dados["porcentagem_geral"]         = (($dados["total_realizado"])/$dados["meta_externa"]) * 100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["total_realizado"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;
        }

        

        // $dados["total_paciente_clinico"]     = ($dados["admissoes_externas_avc"] + $dados["admissoes_internas_avc"]) + ($dados["admissoes_internas_c_medica"] + $dados["admissoes_externas_c_medica"]); /* RETIRAR METAS INTERNAS DO CALCULO - JULIANA 01/2023*/
        // $dados["total_clinica_medica"]       = $dados["admissoes_externas_c_medica"];
        // $dados["total_paciente_critico"]     = $dados["admissoes_internas_cti"] + $dados["admissoes_externas_cti"];
        // $dados["total_clinica_cirurgica"]    = $dados["admissoes_externas_c_cirurgica"] + $dados["admissoes_internas_c_cirurgica"];
        // $dados["total_avc"]                  = ($dados["admissoes_externas_avc"] + $dados["admissoes_internas_avc"]); 

        
        // // $dados["meta_total_paciente_clinico"]               = ($dados["meta_admissoes_internas_clinica_medica"] + $dados["meta_admissoes_externas_clinica_medica"]) + ($dados["meta_admissoes_externas_avc"]); /* RETIRAR METAS INTERNAS DO CALCULO - JULIANA 01/2023*/
        // $dados["meta_total_clinica_medica"]                 = $dados["meta_admissoes_externas_clinica_medica"]; 
        // $dados["meta_total_paciente_critico"]               = $dados["meta_admissoes_externas_cti"] + $dados["meta_admissoes_internas_cti"];
        // $dados["meta_total_clinica_cirurgica"]              = $dados["meta_admissoes_externas_clinica_cirurgica"];
        // $dados["meta_total_hd"]                             = $dados["meta_admissoes_hd"];
        // $dados["meta_total_avc"]                            = $dados["meta_admissoes_externas_avc"];

        // // $dados["porcentagem_geral_paciente_clinico"]     = ($dados["total_paciente_clinico"]/$dados["meta_total_paciente_clinico"]) * 100; /* RETIRAR METAS INTERNAS DO CALCULO - JULIANA 01/2023*/
       
        $calendar->addEvents($events);
        // $calendar->useFullDayNames();
        $calendar->useInitialDayNames();
        $dados["calendario"] = $calendar;
        $this->load->view('templates/template_padrao.php',$dados);   
    }

       
}
