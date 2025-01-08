<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Models\MyModel;
use App\Models\AdmissoesModel;
use Calendar;

class Admissoes extends BaseController{

    protected $myModel;
    protected $admissoesModel;

    public function __construct(){

        $this->myModel = new MyModel();
        $this->admissoesModel = new AdmissoesModel();

    }

	public function index()
	{
        $redirect = $this->verificaPerfilUsuario('admissoes');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $usuario  = $_SESSION["usuario_logado"];
        helper('form');
        $dados['pagina']        = 'admissoes/index.php';
        $dados['nome_pagina']   = 'Gestão de Admissões';
        $dados["link_pagina"]   = 'admissoes';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];

        if(isset($_GET["a"]) && isset($_GET["m"])){
            $dados["ano_calendario"] = $_GET["a"];
            $dados["mes_calendario"] = $_GET["m"];
            $L = new Time($dados["ano_calendario"]."-".$dados["mes_calendario"]."-01"); 
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
        
        $admissoes_mes  = $this->admissoesModel->retornaQuantAdmissoesMes($dados["mes_calendario"],$dados["ano_calendario"],'periodica');
        $ofertas_mes    = $this->admissoesModel->retornaTotaisOfertasPorDia($dados["mes_calendario"],$dados["ano_calendario"]);
        if(count($admissoes_mes)>0){
            for($i=0;$i<count($admissoes_mes);$i++){
                if($admissoes_mes[$i]['DIA_REFERENCIA']==date("d") && $admissoes_mes[$i]['MES_REFERENCIA']==date("m") && $admissoes_mes[$i]['ANO_REFERENCIA']==date("Y")){
                    continue;
                }
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

        $totais_admissoes_mes           = $this->admissoesModel->retornaTotaisMesSemCTI($dados["mes_calendario"],$dados["ano_calendario"]);
        $dados["totais_admissoes_mes"]  = $totais_admissoes_mes;
        $totais_admissoes_mes_cti           = $this->admissoesModel->retornaTotaisMesCTI($dados["mes_calendario"],$dados["ano_calendario"]);
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
        

        $meta_admissoes_internas_cti                = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'I'))  == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'I')[0]["QUANTIDADE"];
        $meta_admissoes_externas_cti                = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'E'))  == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'E')[0]["QUANTIDADE"];
        $meta_admissoes_externas_clinica_medica     = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'E')) == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'E')[0]["QUANTIDADE"];
        $meta_admissoes_externas_avc                = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],9,'E'))  == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],9,'E')[0]["QUANTIDADE"];
        $meta_admissoes_externas_clinica_cirurgica  = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],11,'E')) == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],11,'E')[0]["QUANTIDADE"];
        $meta_admissoes_hd                          = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],13,'E')) == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],13,'E')[0]["QUANTIDADE"];

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

    public function meses(){
        $usuario  = $_SESSION["usuario_logado"];
        helper('form');
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
        $calendar->useInitialDayNames();
        $dados["calendario"] = $calendar;
        return view('templates/template_padrao.php',$dados);   
    }

    public function anos(){
        $usuario  = $_SESSION["usuario_logado"];
        helper('form');
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
        return view('templates/template_padrao.php',$dados);   
    }

    public function retornaDetalhesAdmissoesMes(){
        $dia = $this->request->getPost("dia");
        $mes = $this->request->getPost("mes");
        $ano = $this->request->getPost("ano");

        if(strlen((string)$dia)>0 && strlen((string)$mes)>0 && strlen((string)$ano)>0){
            print json_encode($this->admissoesModel->retornaDetalhesAdmissoesMes($dia,$mes,$ano));
        }else{
            $this->session->setFlashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }

    public function retornaDetalhesAdmissoesMesPorLinha(){
        $dia            = $this->request->getPost("dia");
        $mes            = $this->request->getPost("mes");
        $ano            = $this->request->getPost("ano");
        $agrupamento    = $this->request->getPost("agrupamento");

        if(strlen((string)$dia)>0 && strlen((string)$mes)>0 && strlen((string)$ano)>0){
            print json_encode($this->admissoesModel->retornaDetalhesAdmissoesMesPorLinha($dia,$mes,$ano,$agrupamento));
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


    public function admissoes_por_linha(){
        $usuario  = $_SESSION["usuario_logado"];
        helper('form');
        $dados['pagina']        = 'admissoes/admissoes_por_linha.php';
        $dados['nome_pagina']   = 'Gestão de Admissões';
        $dados["link_pagina"]   = 'admissoes/admissoes_por_linha';
        $dados["diretorio_raiz"]    = '../';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];

        if(isset($_GET["a"]) && isset($_GET["m"])){
            $dados["ano_calendario"] = $_GET["a"];
            $dados["mes_calendario"] = $_GET["m"];
            $L = new Time($dados["ano_calendario"]."-".$dados["mes_calendario"]."-01"); 
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
            return redirect()->to('../admissoes/meses');
            die();
        }
        
        include 'Calendar.php';
        $calendar = new Calendar();
        $events = array();
        
        $admissoes_mes = $this->admissoesModel->retornaQuantAdmissoesMesPorLinha($dados["mes_calendario"],$dados["ano_calendario"],$dados["nr_agrupamento"]);
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
        

        $totais_admissoes_mes           = $this->admissoesModel->retornaTotaisMesPorLinha($dados["mes_calendario"],$dados["ano_calendario"],$dados["nr_agrupamento"]);
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
        }

        $dados["meta_externa"] = 0;
        $dados["meta_interna"] = 0;

        $dados["total_realizado"] = $dados["admissoes_externas_gerais"];

        if($dados["nr_agrupamento"]==1){
            $dados["meta_interna"] = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'I'))  == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'I')[0]["QUANTIDADE"];
            $dados["meta_externa"] = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'E'))  == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],1,'E')[0]["QUANTIDADE"];
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
            $dados["meta_externa"] = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],9,'E'))  == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],9,'E')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "AVC";
            $dados["porcentagem_geral"]         = ($dados["admissoes_externas_gerais"]/$dados["meta_externa"])*100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["admissoes_externas_gerais"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;
        }else if($dados["nr_agrupamento"]==10){
            $dados["meta_externa"] = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'E')) == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'E')[0]["QUANTIDADE"];
            // $dados["meta_interna"] = $this->admissoes_model->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],10,'I')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "Clínica Médica";
            $dados["porcentagem_geral"]         = ($dados["admissoes_externas_gerais"]/$dados["meta_externa"]) * 100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["admissoes_externas_gerais"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;
        }else if($dados["nr_agrupamento"]==11){
            $dados["meta_externa"] = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],11,'E')) == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],11,'E')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "Clínica Cirúrgica";
            $dados["porcentagem_geral"]         = ($dados["admissoes_externas_gerais"]/$dados["meta_externa"]) * 100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["admissoes_externas_gerais"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;
        }else if($dados["nr_agrupamento"]==13){
            $dados["meta_externa"] = count($this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],13,'E')) == 0 ? 1 : $this->admissoesModel->retornaMetasAnuaisPorLinha($dados["ano_calendario"],$dados["quadrimestre"],13,'E')[0]["QUANTIDADE"];
            $dados["linha_cuidado"] = "Hospital Dia";
            $dados["total_realizado"] = $dados["admissoes_externas_gerais"] + $dados["admissoes_internas_gerais"];
            $dados["porcentagem_geral"]         = (($dados["total_realizado"])/$dados["meta_externa"]) * 100;

            $dados["ideal_realizado_externo"] = (int)($dados["meta_externa"]*$dia_atual_realizado)/($dados["ultimo_dia_mes"]);
            if($dados["ideal_realizado_externo"]==0) $dados["ideal_realizado_externo"] = 1;
            $dados["porcentagem_realizado_externo"] = number_format($dados["total_realizado"]/$dados["ideal_realizado_externo"]*100, 2,',','');
            if($dados["ideal_realizado_externo"]==1) $dados["ideal_realizado_externo"] = 0;
        }

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
            print json_encode($this->admissoesModel->retornaDetalhesAdmissoesMesPeriodicas($dia,$mes,$ano));
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
            print json_encode($this->admissoesModel->retornaDetalhesOfertasDiariasPeriodicas($ano,$mes,$dia));
        }else{
            $this->session->setFlashdata("danger","Houve um erro ao gerar os detalhes das admissões. Atualize a página!");
            return;
        }
    }
       
}
