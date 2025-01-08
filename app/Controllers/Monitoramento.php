<?php
namespace App\Controllers;

use App\Models\MyModel;
use App\Models\MonitoramentoModel;
use App\Models\AdmissoesModel;
use Calendar;

class Monitoramento extends BaseController{
    
    protected $myModel;
    protected $monitoramentoModel;
    protected $admissoesModel;

    public function __construct(){

        $this->myModel = new MyModel();
        $this->monitoramentoModel = new MonitoramentoModel();
        $this->admissoesModel = new AdmissoesModel();

    }
    
    public function index()
    {
        $redirect = $this->verificaPerfilUsuario('monitoramento');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $usuario  = $_SESSION["usuario_logado"];

        helper('form');
        $dados['nome_pagina']       = 'Monitoramento de Admissões';
        $dados['pagina']            = 'monitoramento_admissoes/index.php';
        $dados["link_pagina"]       = 'monitoramento_admissoes/index';
        $dados["tipo_perfil"]       = $usuario["TIPO_PERFIL"];

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
        $dia_atual_realizado = (date("d"));
        $dados["dia_atual_realizado"] = $dia_atual_realizado;
        
        include 'Calendar.php';
        $calendar           = new Calendar();
        $events             = array();
        $posicao_ofertadas  = 0;
       
        $ultima_atualizacao_admissoes           = $this->monitoramentoModel->retornaUltimaHoraAdmissoesPeriodicas();

        for($i=0;$i<count($ultima_atualizacao_admissoes);$i++){
            if(isset($ultima_atualizacao_admissoes[$i]["ULTIMA_ATUALIZACAO"]) && $ultima_atualizacao_admissoes[$i]["ULTIMA_ATUALIZACAO"] !=null){
                if($ultima_atualizacao_admissoes[$i]["IE_TIPO_ADMISSAO"]=='I'){
                    $dados["dia_ultima_atualizacao_admissoes_internas"] = $ultima_atualizacao_admissoes[$i]["DIA_REFERENCIA"];
                    $dados["mes_ultima_atualizacao_admissoes_internas"] = $ultima_atualizacao_admissoes[$i]["MES_REFERENCIA"];
                    $dados["ano_ultima_atualizacao_admissoes_internas"] = $ultima_atualizacao_admissoes[$i]["ANO_REFERENCIA"];
                    $dados["ultima_atualizacao_admissoes_internas"]     = date('H:i', strtotime($ultima_atualizacao_admissoes[$i]["ULTIMA_ATUALIZACAO"]));
                }else if($ultima_atualizacao_admissoes[$i]["IE_TIPO_ADMISSAO"]=='E'){
                    $dados["dia_ultima_atualizacao_admissoes_externas"] = $ultima_atualizacao_admissoes[$i]["DIA_REFERENCIA"];
                    $dados["mes_ultima_atualizacao_admissoes_externas"] = $ultima_atualizacao_admissoes[$i]["MES_REFERENCIA"];
                    $dados["ano_ultima_atualizacao_admissoes_externas"] = $ultima_atualizacao_admissoes[$i]["ANO_REFERENCIA"];
                    $dados["ultima_atualizacao_admissoes_externas"]     = date('H:i', strtotime($ultima_atualizacao_admissoes[$i]["ULTIMA_ATUALIZACAO"]));
                }else if($ultima_atualizacao_admissoes[$i]["IE_TIPO_ADMISSAO"]=='HD'){
                    $dados["dia_ultima_atualizacao_admissoes_hd"] = $ultima_atualizacao_admissoes[$i]["DIA_REFERENCIA"];
                    $dados["mes_ultima_atualizacao_admissoes_hd"] = $ultima_atualizacao_admissoes[$i]["MES_REFERENCIA"];
                    $dados["ano_ultima_atualizacao_admissoes_hd"] = $ultima_atualizacao_admissoes[$i]["ANO_REFERENCIA"];
                    $dados["ultima_atualizacao_admissoes_hd"]     = date('H:i', strtotime($ultima_atualizacao_admissoes[$i]["ULTIMA_ATUALIZACAO"]));
                }
            }
        }

        $ultima_atualizacao_ofertas             = $this->monitoramentoModel->retornaUltimaHoraOfertas();
        if(isset($ultima_atualizacao_ofertas) && $ultima_atualizacao_ofertas != null){
            $dados["ultima_atualizacao_ofertas"]    = $ultima_atualizacao_ofertas["ULTIMA_ATUALIZACAO"];
        
            $dados["dia_ultima_atualizacao_ofertas"]        = $ultima_atualizacao_ofertas["DIA_REFERENCIA"];
            $dados["mes_ultima_atualizacao_ofertas"]        = $ultima_atualizacao_ofertas["MES_REFERENCIA"];
            $dados["ano_ultima_atualizacao_ofertas"]        = $ultima_atualizacao_ofertas["ANO_REFERENCIA"];
        }
        
        $admissoes_mes  = $this->monitoramentoModel->retornaQuantAdmissoesMes($dados["mes_calendario"],$dados["ano_calendario"]);
        $ofertas_mes    = $this->monitoramentoModel->retornaTotaisOfertasPorDia($dados["mes_calendario"],$dados["ano_calendario"]);
        
        if(($dia_atual_realizado==1 || $dia_atual_realizado==01)  && count($admissoes_mes)==0){
            $html_texto         = "<span onclick='abrirModalInformacoes(".$dia_atual_realizado.",".date("m").",".date("Y").")'>";
            $posicao_ofertadas  = array_search($dia_atual_realizado,array_column($ofertas_mes,'dia'));
            if(count($ofertas_mes)>0){
                if(isset($posicao_ofertadas) && $posicao_ofertadas>=0 && (!isset($ofertas_mes[$posicao_ofertadas]["ok"]) || $ofertas_mes[$posicao_ofertadas]["ok"] == 0)){
                    if($dia_atual_realizado==$ofertas_mes[$posicao_ofertadas]["dia"]){
                        $html_texto .= "Ofertas: ".$ofertas_mes[$posicao_ofertadas]['quantidade']."<br />";
                        $ofertas_mes[$posicao_ofertadas]["ok"] = 1;
                    }
                }
            }
            $html_texto .="</span>";
            $events[] = array(
                'start' => date("Y")."-".date("m")."-".$dia_atual_realizado,
                'end' => date("Y")."-".date("m")."-".$dia_atual_realizado,
                'summary' => $html_texto,
                'mask' => false
            );
        }else{
            if(count($admissoes_mes)>0){
                for($i=0;$i<count($admissoes_mes);$i++){
                    $html_texto         = "<span onclick='abrirModalInformacoes(".$admissoes_mes[$i]['DIA_REFERENCIA'].",".$admissoes_mes[$i]['MES_REFERENCIA'].",".$admissoes_mes[$i]['ANO_REFERENCIA'].")'>";
                    
                    if($dia_atual_realizado != $admissoes_mes[$i]['DIA_REFERENCIA']){
                        $posicao_ofertadas  = array_search($admissoes_mes[$i]['DIA_REFERENCIA'],array_column($ofertas_mes,'dia'));
                        if(count($ofertas_mes)>0){
                            if(isset($posicao_ofertadas) && $posicao_ofertadas>=0 && (!isset($ofertas_mes[$posicao_ofertadas]["ok"]) || $ofertas_mes[$posicao_ofertadas]["ok"] == 0)){
                                if($admissoes_mes[$i]['DIA_REFERENCIA']==$ofertas_mes[$posicao_ofertadas]["dia"]){
                                    $html_texto .= "Ofertas: ".$ofertas_mes[$posicao_ofertadas]['quantidade']."<br />";
                                    $ofertas_mes[$posicao_ofertadas]["ok"] = 1;
                                }
                            }
                        }
                    }else{
                        $posicao_ofertadas  = array_search($dia_atual_realizado,array_column($ofertas_mes,'dia'));
                        if(count($ofertas_mes)>0){
                            if(isset($posicao_ofertadas) && $posicao_ofertadas>=0 && (!isset($ofertas_mes[$posicao_ofertadas]["ok"]) || $ofertas_mes[$posicao_ofertadas]["ok"] == 0)){
                                if($dia_atual_realizado==$ofertas_mes[$posicao_ofertadas]["dia"]){
                                    $html_texto .= "Ofertas: ".$ofertas_mes[$posicao_ofertadas]['quantidade']."<br />";
                                    $ofertas_mes[$posicao_ofertadas]["ok"] = 1;
                                }
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
        }

        //RESOLUÇÃO OFERTAS-ADMISSOES
        $existe_admissao_hoje = 0;
        foreach ($admissoes_mes as $elemento) {
            if ($elemento["DIA_REFERENCIA"] == $dia_atual_realizado) {
                $existe_admissao_hoje = 1;
            }
        }

        if($existe_admissao_hoje==0){
            $posicao_ofertadas  = array_search($dia_atual_realizado,array_column($ofertas_mes,'dia'));
            if(count($ofertas_mes)>0){
                $html_texto         = "<span onclick='abrirModalInformacoes(".$dia_atual_realizado.",".date("m").",".date("Y").")'>";
                if(isset($posicao_ofertadas) && $posicao_ofertadas>=0 && (!isset($ofertas_mes[$posicao_ofertadas]["ok"]) || $ofertas_mes[$posicao_ofertadas]["ok"] == 0)){
                    if($dia_atual_realizado==$ofertas_mes[$posicao_ofertadas]["dia"]){
                        $html_texto .= "Ofertas: ".$ofertas_mes[$posicao_ofertadas]['quantidade']."<br />";
                        $ofertas_mes[$posicao_ofertadas]["ok"] = 1;
                    }
                }
                $html_texto .="</span>";
                $events[] = array(
                    'start' => date("Y")."-".date("m")."-".$dia_atual_realizado,
                    'end' => date("Y")."-".date("m")."-".$dia_atual_realizado,
                    'summary' => $html_texto,
                    'mask' => false
                );
            }
        }

        $totais_admissoes_mes               = $this->monitoramentoModel->retornaTotaisMesSemCTIPeriodicas($dados["mes_calendario"],$dados["ano_calendario"]);
        $dados["totais_admissoes_mes"]      = $totais_admissoes_mes;
        $totais_admissoes_mes_cti           = $this->monitoramentoModel->retornaTotaisMesCTIPeriodicas($dados["mes_calendario"],$dados["ano_calendario"]);
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

        $meta_admissoes_internas_cti               = count($this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'I'))  == 0 ? 1 : $this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'I')[0]["QUANTIDADE"];
        $meta_admissoes_externas_cti               = count($this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'E'))  == 0 ? 1 : $this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'E')[0]["QUANTIDADE"];
        $meta_admissoes_externas_clinica_medica    = count($this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10, 'E'))== 0 ? 1 : $this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'E')[0]["QUANTIDADE"];
        $meta_admissoes_externas_avc               = count($this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],9,'E'))  == 0 ? 1 : $this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],9,'E')[0]["QUANTIDADE"];
        $meta_admissoes_externas_clinica_cirurgica = count($this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],11,'E')) == 0 ? 1 : $this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],11,'E')[0]["QUANTIDADE"];
        $meta_admissoes_hd                         = count($this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],13,'E')) == 0 ? 1 : $this->monitoramentoModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],13,'E')[0]["QUANTIDADE"];

        $dados["meta_admissoes_internas_cti"]               = $meta_admissoes_internas_cti;
        $dados["meta_admissoes_externas_cti"]               = $meta_admissoes_externas_cti;
        $dados["meta_admissoes_externas_clinica_medica"]    = $meta_admissoes_externas_clinica_medica;
        $dados["meta_admissoes_externas_avc"]               = $meta_admissoes_externas_avc;
        $dados["meta_admissoes_externas_clinica_cirurgica"] = $meta_admissoes_externas_clinica_cirurgica;
        $dados["meta_admissoes_hd"]                         = $meta_admissoes_hd;
    
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
        $calendar->useInitialDayNames();
        $dados["calendario"] = $calendar;
        return view('templates/template_padrao.php',$dados);   
	}

    public function retornaDetalhesAdmissoesMesPeriodicas(){
        $dia            = $this->request->getPost("dia");
        $mes            = $this->request->getPost("mes");
        $ano            = $this->request->getPost("ano");

        if(strlen((string)$dia)>0 && strlen((string)$mes)>0 && strlen((string)$ano)>0){
           
            print json_encode($this->monitoramentoModel->retornaDetalhesAdmissoesMesPeriodicas($dia,$mes,$ano));
        }else{
            $this->session->setFlashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }

    public function retornaDetalhesOfertasDiariasPeriodicas(){
        $dia            = $this->request->getPost("dia");
        $mes            = $this->request->getPost("mes");
        $ano            = $this->request->getPost("ano");

        if(strlen((string)$dia)>0 && strlen((string)$mes)>0 && strlen((string)$ano)>0){
            
            print json_encode($this->monitoramentoModel->retornaDetalhesOfertasDiariasPeriodicas($ano,$mes,$dia));
        }else{
            $this->session->setFlashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }

    public function retornaDetalhesAdmissoesMes(){
        $dia = $this->request->getPost("dia");
        $mes = $this->request->getPost("mes");
        $ano = $this->request->getPost("ano");

        if(strlen((string)$dia)>0 && strlen((string)$mes)>0 && strlen((string)$ano)>0){
            
            print json_encode($this->monitoramentoModel->retornaDetalhesAdmissoesMes($dia,$mes,$ano));
        }else{
            $this->session->setFlashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }

    public function retornaDetalhesOfertasDiarias(){
        $dia            = $this->request->getPost("dia");
        $mes            = $this->request->getPost("mes");
        $ano            = $this->request->getPost("ano");

        if(strlen((string)$dia)>0 && strlen((string)$mes)>0 && strlen((string)$ano)>0){
            
            print json_encode($this->admissoesModel->retornaDetalhesOfertasDiarias($ano,$mes,$dia));
        }else{
            $this->session->setFlashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }
       
}
