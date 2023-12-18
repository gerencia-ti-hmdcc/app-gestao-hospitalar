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
            $funcao_permitida = $this->my_model->retornaSeFuncaoPermitida($usuario["ID"],'detalhada');
            if(!count($funcao_permitida)>0){
                header('Location: /../dashboard');
            }
        }
    }

	public function index()
	{
        $usuario  = $_SESSION["usuario_logado"];
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

        if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=="P"){
            $dados["mostrar_menus"]      = 0;
        }else{
            $dados["mostrar_menus"]      = 1;
        }

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

                $dados["ultima_atualizacao"] = $this->detalhada_model->retornaUltimaAtualizacaoLeitos();
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

    public function avaliacoesVerdeVermelho(){
        $nr_atendimento = (int) $_GET["a"];

        if(strlen($nr_atendimento)>0){
            $this->load->model('detalhada_model');
            // $dados["avaliacoes_verde_vermelho"] = $this->detalhada_model->retornaAvaliacoesVerdeVermelho($nr_atendimento);
            // $dados["movimentacoes_atendimento"] = $this->detalhada_model->retornaMovimentacoesAtendimento($nr_atendimento);
            $dados["dados_leito_atual"]         = $this->detalhada_model->retornaDadosLeitoPorAtendimento($nr_atendimento);
            $dados["historico_avaliacoes"]      = $this->detalhada_model->retornaHistoricoAvaliacoesVerdeVermelho($nr_atendimento);

            $dados['pagina']            = 'ocupacao_detalhada/setores/leitos/avaliacoes_verde_vermelho.php';
            $dados['nome_pagina']       = 'Avaliações verdes e vermelhos';
            $dados["link_pagina"]       = 'avaliacoesVerdeVermelho';
            unset($dados["diretorio_raiz"]);
            $dados["diretorio_raiz"]    = '../';
            $this->load->view('templates/template_padrao.php',$dados);   

        }else{
            header('Location: '.base_url('../').'detalhada');
        }

    }

    public function retornaDadosLeitoPorAtendimento(){
        $nr_atendimento         = $this->input->post("nr_atendimento");

        if(strlen($nr_atendimento)>0 ){
            $this->load->model('detalhada_model');
            print json_encode($this->detalhada_model->retornaDadosLeitoPorAtendimento($nr_atendimento));
        }else{
            $this->session->set_flashdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function retornaTotaisAvaliacoesVerdeVermelho(){
        $nr_atendimento         = $this->input->post("nr_atendimento");

        if(strlen($nr_atendimento)>0 ){
            $this->load->model('detalhada_model');
            print json_encode($this->detalhada_model->retornaTotaisAvaliacoesVerdeVermelho($nr_atendimento));
        }else{
            $this->session->set_flashdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function historicoEvolucoesPaciente(){
        $nr_atendimento = (int) $_GET["a"];
        include 'Rtf.php';
        if(strlen($nr_atendimento)>0){
            $this->load->model('detalhada_model');
            $dados["dados_leito_atual"]     = $this->detalhada_model->retornaDadosLeitoPorAtendimento($nr_atendimento);
            $historico_evolucoes            = $this->detalhada_model->retornaHistoricoEvolucoesPaciente($nr_atendimento);

            if(count($historico_evolucoes)>0){
                $html_evolucoes_paciente =    "<div class='flex flex-wrap'>
                            <div class='card z-index-2 w-full'>
                                <div class='w-full flex card-header pb-0'>
                                    
                                </div>
                                <div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'>
                                    
                                </div>

                                <div class='card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='historico_evolucoes' name='historico_evolucoes'>
                                    <table class='align-items-center justify-content-center' style='border-collapse: unset !important;' width='100%'>
                                        <tr>
                                            <td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>
                                                Últimas Evoluções
                                            </td>
                                        </tr>";
                $reader     = new RtfReader();
                $formatter  = new RtfHtml();
                for($i=0;$i<count($historico_evolucoes);$i++){
                    if($i==3){
                        break;
                    }
                    $result = $reader->Parse($historico_evolucoes[$i]["ds_conteudo"]);
                    $test = $formatter->Format($reader->root);

                    $html_evolucoes_paciente .= 
                    "<tr>
                        <td class='font-weight-bold text-wrap text-uppercase' style='color:#000'>
                            <b>Data</b>
                        </td>
                        <td class='text-wrap text-justify'>
                            ".date("d/m/Y H:i:s", strtotime($historico_evolucoes[$i]["dt_liberacao_evolucao"]))."
                        </td>
                    </tr>
                    <tr>
                        <td class='font-weight-bold text-wrap text-uppercase' style='color:#000'>
                            <b>Setor Atendimento</b>
                        </td>
                        <td class='text-wrap text-justify'>
                            ".$historico_evolucoes[$i]["ds_setor_atendimento"]."
                        </td>
                    </tr>
                    <tr>
                        <td class='font-weight-bold text-wrap text-uppercase' style='color:#000'>
                            <b>Leito</b>
                        </td>
                        <td class='text-wrap text-justify'>
                            ".$historico_evolucoes[$i]["ds_leito_atual"]."
                        </td>
                    </tr>
                    <tr> 
                        <td class='font-weight-bold text-wrap text-uppercase' style='color:#000'>
                            <b>Médico</b>
                        </td>
                        <td class='text-wrap text-justify'>
                            ".$historico_evolucoes[$i]["ds_nome_medico"]."
                        </td>
                    </tr>
                    <tr> 
                        <td colspan='2' class='text-center font-weight-bold text-wrap text-uppercase' style='color:#000'>
                            <b>Evolução</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='text-wrap text-justify'>
                            ".$test."
                        </td>
                    </tr>";
                    $html_evolucoes_paciente .= "<tr><td colspan='2'><hr style='height: 2px;background-color:#000; color: #000' ></hr></td></tr>";
                    // $reader->root->dump();
                }
                $html_evolucoes_paciente .=                "</table>
                                </div>
                            </div>
                        </div>";
            }else{
                $html_evolucoes_paciente = "<div class='flex flex-wrap'>
                                                <div class='card z-index-2 w-full'>
                                                    <div class='w-full flex card-header pb-0'>
                                                        
                                                    </div>
                                                    <div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'>
                                                        
                                                    </div>
                                                    <div class='card-body text-center' id='historico_evolucoes' name='historico_evolucoes'>
                                                        O paciente ainda não possui evoluções para serem mostradas.
                                                    </div>
                                                </div>
                                            </div>";
            }
            $dados["html_evolucoes_paciente"] = $html_evolucoes_paciente;

            $dados['pagina']            = 'ocupacao_detalhada/setores/leitos/historico_evolucoes_paciente.php';
            $dados['nome_pagina']       = 'Histórico de Evoluções do Paciente';
            $dados["link_pagina"]       = 'historicoEvolucoesPaciente';
            unset($dados["diretorio_raiz"]);
            $dados["diretorio_raiz"]    = '../';
            $this->load->view('templates/template_padrao.php',$dados);   

        }else{
            header('Location: '.base_url('../').'detalhada');
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
