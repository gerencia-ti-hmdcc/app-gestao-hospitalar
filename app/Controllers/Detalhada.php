<?php
namespace App\Controllers;
use App\Models\MyModel;
use App\Models\DetalhadaModel;
use RtfHtml;
use RtfReader;
 
class Detalhada extends BaseController {

    protected $myModel;
    protected $detalhadaModel;

    public function __construct(){

        $this->myModel = new MyModel();
        $this->detalhadaModel = new DetalhadaModel();
    }

	public function index()
	{
        $redirect = $this->verificaPerfilUsuario('detalhada');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $usuario  = $_SESSION["usuario_logado"];
        helper('form');
        
        $dados["linhas_de_cuidado"] = $this->detalhadaModel->retornaLinhasDeCuidado($usuario);
        $dados['pagina']            = 'ocupacao_detalhada/index.php';
        $dados['nome_pagina']       = 'Ocupação Detalhada';
        $dados["link_pagina"]       = 'detalhada';
        $dados["tipo_perfil"]       = $usuario["TIPO_PERFIL"];
        $this->logAcaoUsuario("visualização - ocupação detalhada");
        
        return view('templates/template_padrao.php',$dados);   
	}

    public function setores(){
        $linha = $_GET["l"];
        if($linha){
            if(trim(strlen($linha))>0){
                $this->logAcaoUsuario("visualização - setores");
                
                $dados["setores"]           = $this->detalhadaModel->retornaSetoresPorLinha($linha);
                $dados["linha_cuidado"]     = $this->detalhadaModel->retornaDadosLinhaCuidado($linha);
                $dados['pagina']            = 'ocupacao_detalhada/setores/index.php';
                $dados['nome_pagina']       = 'Setores';
                $dados["link_pagina"]       = 'setores';
                $dados["diretorio_raiz"]    = '../';
                return view('templates/template_padrao.php',$dados);   
            }else{
                return redirect()->to(base_url('../').'detalhada');
            }
        }else{
            return redirect()->to(base_url('../').'detalhada');
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
                $this->logAcaoUsuario("visualização - leitos");
                
                $dados["leitos"]            = $this->detalhadaModel->retornaLeitosClassifSetor($linha,$cd_setor_atendimento);
                $dados["setor_atend"]       = $this->detalhadaModel->retornaDadosSetorAtendimento($cd_setor_atendimento);
                $ultimas_avaliacoes_braden  = $this->detalhadaModel->retornaUltimasAvaliacoesBradenPaciente();
                $ultimas_avaliacoes_morse   = $this->detalhadaModel->retornaUltimasAvaliacoesMorsePaciente();

                for($j=0;$j<count($dados["leitos"]);$j++){
                    $posicao_atendimento_braden = array_search($dados["leitos"][$j]['nr_atendimento'],array_column($ultimas_avaliacoes_braden,'NR_ATENDIMENTO'));
                    $posicao_atendimento_morse  = array_search($dados["leitos"][$j]['nr_atendimento'],array_column($ultimas_avaliacoes_morse,'NR_ATENDIMENTO'));

                    if(isset($posicao_atendimento_braden) && $posicao_atendimento_braden>=0){
                        if($dados["leitos"][$j]["nr_atendimento"]==$ultimas_avaliacoes_braden[$posicao_atendimento_braden]["NR_ATENDIMENTO"]){
                            $dados["leitos"][$j]["braden"]  = $ultimas_avaliacoes_braden[$posicao_atendimento_braden];
                        }
                    }
                    if(isset($posicao_atendimento_morse) && $posicao_atendimento_morse>=0){
                        if($dados["leitos"][$j]["nr_atendimento"]==$ultimas_avaliacoes_morse[$posicao_atendimento_morse]["NR_ATENDIMENTO"]){
                            $dados["leitos"][$j]["morse"]   = $ultimas_avaliacoes_morse[$posicao_atendimento_morse];
                        }
                    }
                }

                for($i=0;$i<count($dados["leitos"]);$i++){  
                    $movimentacoes_atendimento  = $this->detalhadaModel->retornaMovimentacoesAtendimento($dados["leitos"][$i]["nr_atendimento"]);
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

                $dados["ultima_atualizacao"] = $this->detalhadaModel->retornaUltimaAtualizacaoLeitos();
                $dados['pagina']            = 'ocupacao_detalhada/setores/leitos/index.php';
                $dados['nome_pagina']       = 'Leitos';
                $dados["link_pagina"]       = 'leitos';
                unset($dados["diretorio_raiz"]);
                $dados["diretorio_raiz"]    = '../';
                return view('templates/template_padrao.php',$dados);   
            }else{
                return redirect()->to(base_url('../').'detalhada');
            }
        }else{
            return redirect()->to(base_url('../').'detalhada');
        }
    }

    public function retornaDadosLeito(){
        $nr_atendimento         = $this->request->getPost("nr_atendimento");
        $leito_atual            = $this->request->getPost("leito_atual");
        $cd_setor_atendimento   = $this->request->getPost("cd_setor_atendimento");
        $this->logAcaoUsuario("visualização - dados do leito - leito $leito_atual - nr_atendimento $nr_atendimento", $nr_atendimento);
        // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

        if(strlen((string)$nr_atendimento)>0 && strlen((string)$leito_atual)>0 && strlen((string)$cd_setor_atendimento)>0){
            
            print json_encode($this->detalhadaModel->retornaDadosLeito($nr_atendimento,$leito_atual,$cd_setor_atendimento));
        }else{
            $this->session->setFlasdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function retornaMovimentacoesAtendimento(){
        $nr_atendimento         = $this->request->getPost("nr_atendimento");
        $this->logAcaoUsuario("visualização - movimentações do paciente - nr_atendimento $nr_atendimento", $nr_atendimento);
        // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

        if(strlen((string)$nr_atendimento)>0){
            
            print json_encode($this->detalhadaModel->retornaMovimentacoesAtendimento($nr_atendimento));
        }else{
            $this->session->setFlasdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function avaliacoesVerdeVermelho(){
        $nr_atendimento = (int) $_GET["a"];

        if(strlen($nr_atendimento)>0){
            $this->logAcaoUsuario("visualização - avaliações verde/vermelho -  nr_atendimento $nr_atendimento", $nr_atendimento, 'avaliações verde/vermelho');
            // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);
        
            $dados["dados_leito_atual"]         = $this->detalhadaModel->retornaDadosLeitoPorAtendimento($nr_atendimento);
            $dados["historico_avaliacoes"]      = $this->detalhadaModel->retornaHistoricoAvaliacoesVerdeVermelho($nr_atendimento);

            $dados['pagina']            = 'ocupacao_detalhada/setores/leitos/avaliacoes_verde_vermelho.php';
            $dados['nome_pagina']       = 'Avaliações verdes e vermelhos';
            $dados["link_pagina"]       = 'avaliacoesVerdeVermelho';
            unset($dados["diretorio_raiz"]);
            $dados["diretorio_raiz"]    = '../';
            return view('templates/template_padrao.php',$dados);   

        }else{
            return redirect()->to(base_url('../').'detalhada');
        }

    }

    public function retornaDadosLeitoPorAtendimento(){
        $nr_atendimento         = $this->request->getPost("nr_atendimento");

        if(strlen((string)$nr_atendimento)>0 ){
           
            print json_encode($this->detalhadaModel->retornaDadosLeitoPorAtendimento($nr_atendimento));
        }else{
            $this->session->setFlasdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function retornaTotaisAvaliacoesVerdeVermelho(){
        $nr_atendimento         = $this->request->getPost("nr_atendimento");

        if(strlen((string)$nr_atendimento)>0 ){
           
            print json_encode($this->detalhadaModel->retornaTotaisAvaliacoesVerdeVermelho($nr_atendimento));
        }else{
            $this->session->setFlasdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function historicoEvolucoesPaciente(){
        $nr_atendimento = (int) $_GET["a"];
        include 'Rtf.php';
        if(strlen($nr_atendimento)>0){

            $this->logAcaoUsuario("visualização - evoluções do paciente - nr_atendimento $nr_atendimento", $nr_atendimento, 'evoluções');
            // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);
            
            $historico_evolucoes            = $this->detalhadaModel->retornaHistoricoEvolucoesPaciente($nr_atendimento);

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
                        <td colspan='2' class='cor_solicitacao_interconsulta text-center font-weight-bold text-wrap text-uppercase' style='color:#000'>
                            <b>Evolução</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$test."
                        </td>
                    </tr>";
                    $html_evolucoes_paciente .= "<tr><td colspan='2'><hr style='height: 2px;background-color:#000; color: #000' ></hr></td></tr>";
                    
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
            return view('templates/template_padrao.php',$dados);   

        }else{
            return redirect()->to(base_url('../').'detalhada');
        }

    }

    public function historicoInterconsultasPaciente(){
        $nr_atendimento = (int) $_GET["a"];
        include 'Rtf.php';
        if(strlen($nr_atendimento)>0){

            $this->logAcaoUsuario("visualização - interconsultas do paciente - nr_atendimento $nr_atendimento", $nr_atendimento, 'interconsultas');
            // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

            $historico_interconsultas            = $this->detalhadaModel->retornaHistoricoInterconsultasPaciente($nr_atendimento);

            if(count($historico_interconsultas)>0){
                $html_interconsultas_paciente =    
                        "<div class='flex flex-wrap'>
                            <div class='card z-index-2 w-full'>
                                <div class='w-full flex card-header pb-0'>
                                    
                                </div>
                                <div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'>
                                    
                                </div>

                                <div class='card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='historico_interconsultas' name='historico_interconsultas'>
                                    <table class='table align-items-center justify-content-center' width='100%'>
                                        <tr>
                                            <td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>
                                                Últimas Interconsultas
                                            </td>
                                        </tr>";
                $reader     = new RtfReader();
                $formatter  = new RtfHtml();
                for($i=0;$i<count($historico_interconsultas);$i++){
                    
                    $result     = $reader->Parse($historico_interconsultas[$i]["ds_motivo"]);
                    $conteudo   = $formatter->Format($reader->root);

                    $html_interconsultas_paciente .= 
                    "<tr>
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Data solicitação</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".date("d/m/Y H:i:s", strtotime($historico_interconsultas[$i]["dt_liberacao_solicitacao"]))."
                        </td>
                    </tr>
                    <tr>
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Leito</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$historico_interconsultas[$i]["ds_leito"]."
                        </td>
                    </tr>
                    <tr> 
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Solicitante</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$historico_interconsultas[$i]["ds_medico_solicitante"]."
                        </td>
                    </tr>
                    <tr> 
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Especialidade</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$historico_interconsultas[$i]["ds_especialidade_solicitante"]."
                        </td>
                    </tr>
                    <tr> 
                        <td colspan='2' class='cor_solicitacao_interconsulta text-center font-weight-bold text-wrap text-uppercase' >
                            <b>Motivo</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$conteudo."
                        </td>
                    </tr>";

                    if(strlen($historico_interconsultas[$i]["dt_liberacao_parecer"])>0){
                        
                        $result1            = $reader->Parse($historico_interconsultas[$i]["ds_parecer"]);
                        $conteudo_parecer   = $formatter->Format($reader->root);

                        if(strlen($historico_interconsultas[$i]["ds_especialidade_parecer"])>0){
                            $especialidade_parecer = $historico_interconsultas[$i]["ds_especialidade_parecer"];
                        }else{
                            $especialidade_parecer = " - ";
                        }

                        if(strlen($historico_interconsultas[$i]["ds_equipe_parecer"])>0){
                            $equipe_parecer = $historico_interconsultas[$i]["ds_equipe_parecer"];
                        }else{
                            $equipe_parecer = " - ";
                        }

                        $html_interconsultas_paciente .= 
                        "<tr>
                            <td class='cor_parecer_interconsulta font-weight-bold text-wrap text-uppercase'>
                                <b>Data parecer</b>
                            </td>
                            <td class='cor_parecer_interconsulta text-wrap text-justify'>
                                ".date("d/m/Y H:i:s", strtotime($historico_interconsultas[$i]["dt_liberacao_parecer"]))."
                            </td>
                        </tr>
                        <tr>
                            <td class='cor_parecer_interconsulta font-weight-bold text-wrap text-uppercase'>
                                <b>Profissional parecer</b>
                            </td>
                            <td class='cor_parecer_interconsulta text-wrap text-justify'>
                                ".$historico_interconsultas[$i]["ds_medico_parecer"]."
                            </td>
                        </tr>
                        <tr>
                            <td class='cor_parecer_interconsulta font-weight-bold text-wrap text-uppercase'>
                                <b>Especialidade parecer</b>
                            </td>
                            <td class='cor_parecer_interconsulta text-wrap text-justify'>
                                ".$especialidade_parecer."
                            </td>
                        </tr>
                        <tr>
                            <td class='cor_parecer_interconsulta font-weight-bold text-wrap text-uppercase'>
                                <b>Equipe parecer</b>
                            </td>
                            <td class='cor_parecer_interconsulta text-wrap text-justify'>
                                ".$equipe_parecer."
                            </td>
                        </tr>
                        <tr> 
                            <td colspan='2' class='cor_parecer_interconsulta text-center font-weight-bold text-wrap text-uppercase'>
                                <b>Parecer</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2' class='cor_parecer_interconsulta text-wrap text-justify'>
                                ".$conteudo_parecer."
                            </td>
                        </tr>";
                    }else{
                        $html_interconsultas_paciente .= "";
                    }

                    $html_interconsultas_paciente .= "<tr><td colspan='2'><hr style='height: 2px;background-color:#000; color: #000' ></hr></td></tr>";
                    // $reader->root->dump();
                }
                $html_interconsultas_paciente .=                "</table>
                                </div>
                            </div>
                        </div>";
            }else{
                $html_interconsultas_paciente = "<div class='flex flex-wrap'>
                                                    <div class='card z-index-2 w-full'>
                                                        <div class='w-full flex card-header pb-0'>
                                                            
                                                        </div>
                                                        <div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'>
                                                            
                                                        </div>
                                                        <div class='card-body text-center' id='historico_interconsultas' name='historico_interconsultas'>
                                                            O paciente ainda não possui interconsultas para serem mostradas.
                                                        </div>
                                                    </div>
                                                </div>";
            }
            $dados["html_interconsultas_paciente"] = $html_interconsultas_paciente;

            $dados['pagina']            = 'ocupacao_detalhada/setores/leitos/historico_interconsultas_paciente.php';
            $dados['nome_pagina']       = 'Histórico de Interconsultas do Paciente';
            $dados["link_pagina"]       = 'historicoInterconsultasPaciente';
            unset($dados["diretorio_raiz"]);
            $dados["diretorio_raiz"]    = '../';
            return view('templates/template_padrao.php',$dados);   

        }else{
            return redirect()->to(base_url('../').'detalhada');
        }

    }

    public function encontraArquivosExamesLaboratoriaisExternos($nr_atendimento){
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $path1      = "assets/exames";
        }else{
            $path1      = "C:/laragon/www/ci4-app/public/assets/exames";
            // exit("teste");
        }

        $diretorio1 = dir($path1);

        while($arquivo1 = $diretorio1->read()){
            $arquivos_externos_pasta[] = $arquivo1;
        }

        $diretorio1->close();
        if(count($arquivos_externos_pasta)>0){
            $arquivos_exames_externos_atendimento = [];
            for($i=0;$i<count($arquivos_externos_pasta);$i++){
                if(str_contains($arquivos_externos_pasta[$i],$nr_atendimento)){
                    $arquivos_exames_externos_atendimento[] = $arquivos_externos_pasta[$i];
                }
            }
            return $arquivos_exames_externos_atendimento; 
        }else{
            return [];
        }
    }


    public function historicoExamesLabPaciente(){

        $nr_atendimento = (int) $_GET["a"];
        include 'Rtf.php';
        if(strlen($nr_atendimento)>0){

            $this->logAcaoUsuario("visualização - exames laboratoriais do paciente - nr_atendimento $nr_atendimento", $nr_atendimento, 'exames laboratoriais');
            // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);
            
            $historico_exames_lab            = $this->detalhadaModel->retornaHistoricoExamesLaboratoriaisPaciente($nr_atendimento);

            $exames_laboratoriais_externos_servidor = $this->encontraArquivosExamesLaboratoriaisExternos($nr_atendimento);

            // SE EXISTE EXAME LABORATORIAL EXTERNO
            $se_existe_exames_externos = "";
            if($exames_laboratoriais_externos_servidor){
                if(count($exames_laboratoriais_externos_servidor)>0){
                    $se_existe_exames_externos = "<tr>
                                                    <td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>
                                                        Externos
                                                    </td>
                                                </tr>";
                    for($j=0;$j<count($exames_laboratoriais_externos_servidor);$j++){
                        $se_existe_exames_externos  .=   "<tr> 
                                                            <td class='text-wrap text-uppercase'>
                                                                <b>".$exames_laboratoriais_externos_servidor[$j]."</b>
                                                            </td>
                                                            <td class='cor_solicitacao_interconsulta text-wrap text-center flex' style='color:#cb0c9f'>
                                                                <a class='w-full' href='exameLaboratorialExternoPdf?ne=".$exames_laboratoriais_externos_servidor[$j]."&na=".$nr_atendimento."'><div class='w-full btn btn-primary' style='margin:0px !important'><b>ABRIR EXAME</b></div></a>
                                                            </td>
                                                        </tr>";
                    }
                    $se_existe_exames_externos .= "<tr>
                                                    <td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>
                                                        Internos
                                                    </td>
                                                </tr>";
                }
            }

            // SE EXISTE EXAME LABORATORIAL INTERNO
            if(count($historico_exames_lab)>0){
                $html_exames_lab_paciente =    
                        "<div class='flex flex-wrap'>
                            <div class='card z-index-2 w-full'>
                                <div class='w-full flex card-header pb-0'>
                                    
                                </div>
                                <div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'>
                                    
                                </div>

                                <div class='card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='historico_exames_laboratoriais' name='historico_exames_laboratoriais'>
                                    <table class='table text-break text-wrap align-items-center justify-content-center' width='100%'>
                                        <tr>
                                            <td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>
                                                Exames Laboratoriais
                                            </td>
                                        </tr>
                                        $se_existe_exames_externos";
                $reader     = new RtfReader();
                $formatter  = new RtfHtml();
                for($i=0;$i<count($historico_exames_lab);$i++){
                    // if($i==3){
                    //     break;
                    // }
                    if(str_contains($historico_exames_lab[$i]["ds_resultado"],'srvhmdccfiles')){
                        $conteudo     = "Exame externo. Temporariamente disponível somente no Tasy.";
                    }else{
                       $result     = $reader->Parse($historico_exames_lab[$i]["ds_resultado"]);
                       $conteudo   = $formatter->Format($reader->root);
                    }
   
                    $info_prescricao = 
                    "<tr> 
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Prescrição</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify' style='color:#cb0c9f'>
                            <b>".$historico_exames_lab[$i]["nr_prescricao"]."</b>
                        </td>
                    </tr>
                    <tr> 
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Data Prescrição</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".date("d/m/Y H:i:s", strtotime($historico_exames_lab[$i]["dt_liberacao_prescricao"]))."
                        </td>
                    </tr>";

                    if($i==0){
                        $html_exames_lab_paciente .= $info_prescricao;
                        
                    }else{
                        if($historico_exames_lab[$i]["nr_prescricao"]!=$historico_exames_lab[$i-1]["nr_prescricao"]){
                            $html_exames_lab_paciente .= $info_prescricao;
                        }
                    }

                    $html_exames_lab_paciente .= 
                    "<!--
                    <tr> 
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Prescrição</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$historico_exames_lab[$i]["nr_prescricao"]."
                        </td>
                    </tr>
                    -->
                    <!--
                    <tr>
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Leito</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$historico_exames_lab[$i]["ds_leito_atual"]."
                        </td>
                    </tr>
                    -->
                    <tr> 
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Exame</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$historico_exames_lab[$i]["nm_exame"]."
                        </td>
                    </tr>
                    <tr>
                        <td class='cor_solicitacao_interconsulta font-weight-bold text-wrap text-uppercase'>
                            <b>Data baixa</b>
                        </td>
                        <td class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".date("d/m/Y H:i:s", strtotime($historico_exames_lab[$i]["dt_baixa"]))."
                        </td>
                    </tr>
                    <tr> 
                        <td colspan='2' class='cor_solicitacao_interconsulta text-center font-weight-bold text-wrap text-uppercase' >
                            <b>Resultado</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='cor_solicitacao_interconsulta text-wrap text-justify'>
                            ".$conteudo."
                        </td>
                    </tr>";

                    
                    $html_exames_lab_paciente .= "<tr><td colspan='2'><hr style='height: 2px;background-color:#000; color: #000' ></hr></td></tr>";
                    // $reader->root->dump();
                }
                $html_exames_lab_paciente .=                "</table>
                                </div>
                            </div>
                        </div>";
            }else{
                //ADICIONAR: EXAME LAB EXTERNO MESMO SE NÃO HOUVER INTERNO - VARIAVEL $se_existe_exames_externos

                if(strlen($se_existe_exames_externos)>0){
                    $html_exames_lab_paciente .= $se_existe_exames_externos;
                    $html_exames_lab_paciente .=        "</table>
                                                    </div>
                                                </div>
                                            </div>";
                }else{
                    $html_exames_lab_paciente = "<div class='flex flex-wrap'>
                                                    <div class='card z-index-2 w-full'>
                                                        <div class='w-full flex card-header pb-0'>
                                                            
                                                        </div>
                                                        <div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'>
                                                            
                                                        </div>
                                                        <div class='card-body text-center' id='historico_exames_laboratoriais' name='historico_exames_laboratoriais'>
                                                            O paciente ainda não possui exames laboratoriais para serem mostrados.
                                                        </div>
                                                    </div>
                                                </div>";
                }
            }
            
            $dados["html_exames_lab_paciente"] = $html_exames_lab_paciente;

            $dados['pagina']            = 'ocupacao_detalhada/setores/leitos/historico_exames_laboratoriais_paciente.php';
            $dados['nome_pagina']       = 'Histórico de Exames Laboratoriais do Paciente';
            $dados["link_pagina"]       = 'historicoExamesLabPaciente';
            unset($dados["diretorio_raiz"]);
            $dados["diretorio_raiz"]    = '../';
            return view('templates/template_padrao.php',$dados);   

        }else{
            return redirect()->to(base_url('../').'detalhada');
        }

    }

    public function historicoExamesImagemPaciente(){
        helper('url');
        $nr_atendimento = (int) $_GET["a"];

        if(strlen($nr_atendimento)>0){

            $this->logAcaoUsuario("visualização - laudos e exames de imagem do paciente - nr_atendimento $nr_atendimento", $nr_atendimento, 'laudos e exames de imagem');
            // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);
            
            $dados["nr_prontuario"] = $this->detalhadaModel->retornaProntuarioAtendimento($nr_atendimento);
            $dados["html_exames_imagem_paciente"] = "<div class='flex flex-wrap'>
                                                        <div class='card z-index-2 w-full'>
                                                            <div class='w-full flex card-header pb-0'>
                                                                
                                                            </div>
                                                            <div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'>
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type='hidden' id='nr_prontuario_id' name='nr_prontuario_id' value='".$dados["nr_prontuario"]["nr_prontuario"]."'/>";

            $dados['pagina']            = 'ocupacao_detalhada/setores/leitos/historico_exames_imagem_paciente.php';
            $dados['nome_pagina']       = 'Exames de imagem';
            $dados["link_pagina"]       = 'historicoExamesImagemPaciente';
            unset($dados["diretorio_raiz"]);
            $dados["diretorio_raiz"]    = '../';
            return view('templates/template_padrao.php',$dados);   

        }else{
            return redirect()->to(base_url('../').'detalhada');
        }

    }

    public function retornaTabelaExames(){
        $nr_prontuario  = (int) $_POST["nr_prontuario"];
        // $this->load->model('detalhada_model');
        $exames_imagem  = $this->detalhadaModel->retornaExamesImagemPaciente($nr_prontuario);
        $tabela_exames  = "<table class='table align-items-center justify-content-center' width='100%'>
                            <tr>
                                <td class='text-xs font-weight-bold text-wrap'>
                                    Exame
                                </td>
                                <td class='text-xs font-weight-bold text-wrap'>
                                    Data
                                </td>
                                <td class='text-xs font-weight-bold text-wrap'>
                                    Situação
                                </td>
                            <tr>";
        $cont_exames = 0;
        for($i = 0;$i<count($exames_imagem);$i++){
            // $onclick_linha = "onclick='acessarExamePaciente(".$exames_imagem[$i]["nr_acesso_dicom"].")'";
            $onclick_linha = "onclick='chamarFuncaoPhp(".$exames_imagem[$i]["nr_acesso_dicom"].")'";
            $tabela_exames .=   "<tr> 
                                    <td class='cursor-pointer text-xs font-weight-bold text-wrap' $onclick_linha>".$exames_imagem[$i]["ds_exame"]."</td>
                                    <td class='cursor-pointer text-xs font-weight-bold text-wrap' $onclick_linha>".date('d/m/Y H:i:s',strtotime($exames_imagem[$i]["dt_exame"]))."</td>
                                    <td class='cursor-pointer text-xs font-weight-bold text-wrap' $onclick_linha>".$exames_imagem[$i]["ds_status"]."</td>
                                </tr>";
            $cont_exames ++;
        }

        $tabela_exames .= "<tr><td class='text-xs font-weight-bold text-wrap' colspan='2'><b>Total</b></td><td class='text-xs font-weight-bold text-wrap'><b>$cont_exames exames</b></td></tr></table>";
        print json_encode($tabela_exames);
    }

    public function exameLaboratorialExternoPdf(){
        $nome_exame_pdf = $_GET["ne"];
        $nr_atendimento = $_GET["na"];
        helper('file');

        $usuario = $this->session->get("usuario_logado");
        if($usuario){
            // GARANTIR NOVAMENTE A SESSAO DE BANCO E DE NAVEGADOR
            $us_token = $this->myModel->verificaToken($usuario['TOKEN']);

            if(isset($us_token['ID']) && $us_token['ID']>0){
                $this->logAcaoUsuario("visualização de exame laboratorial- exames laboratoriais do paciente - nr_atendimento $nr_atendimento - exame $nome_exame_pdf", $nr_atendimento, 'exames laboratoriais');
                // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

                $whitelist = array(
                    '127.0.0.1',
                    '::1'
                );

                if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
                    $filepath = "assets/exames/$nome_exame_pdf";
                }else{
                    $filepath = "C:/laragon/www/app/assets/exames/$nome_exame_pdf";
                }

                $contents = readFile($filepath);
                if (!file_exists($filepath)) {
                    throw new \Exception("O arquivo $filepath não existe!");
                }
                if (!is_readable($filepath)) {
                    throw new \Exception("Não foi possível ler o arquivo $filepath");
                }
                $contents = readFile($filepath);

                /* PARA BAIXAR DIRETO O ARQUIVO
                http_response_code(200);
                header('Content-Length: '.filesize($filepath));
                header("Content-Type: application/pdf");
                header('Content-Disposition: attachment; filename="'.$nome_exame_pdf.'"'); // feel free to change the suggested filename
                readfile($filepath);
                
                */

                //PARA VISUALIZAR O ARQUIVO
                return $this->response
                ->setStatusCode(200)
                ->setContentType('application/pdf')
                ->setBody($contents);
                 exit;
            }else{
                return;
            }
        }else{
            return;
        }
    }
        
    }

       
