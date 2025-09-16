<?php

namespace App\Controllers;
use App\Models\DashboardModel;
use App\Models\MyModel;

class Dashboard extends BaseController{

    protected $dashboardModel;
    protected $myModel;

    public function __construct(){

        $this->dashboardModel = new DashboardModel();
        $this->myModel = new MyModel();

    }

	public function index()
	{
        $redirect = $this->verificaPerfilUsuario('dashboard');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        $usuario  = $_SESSION["usuario_logado"];

        if($usuario["TIPO_PERFIL"]=="P"){
            $dados["mostrar_menus"]      = 0;
            $dados["tamanho_grafico"]   = "250";
        }else{
            $dados["mostrar_menus"]      = 1;
            $dados["tamanho_grafico"]   = "300";
        }

        helper('form');

        $dados['pagina']        = 'dashboard/index.php';
        $dados['nome_pagina']   = 'Ocupação Hospitalar';
        $dados["link_pagina"]   = 'dashboard';
        $dados["tipo_perfil"]   = $usuario["TIPO_PERFIL"];
        if($dados["tipo_perfil"]=="P"){
    
            $ultimo_usuario_painel = $this->dashboardModel->retornaUltimoSetorGeralAtivo();
            $dados["setor_ultimo_painel"] = $ultimo_usuario_painel["NR_SETOR"];
        }
        // print_r($_SESSION);
        // exit();
        return view('templates/template_padrao.php',$dados);   
	}

    public function percentuaisGeraisOcupacao(){
        if($this->request->isAJAX()){
            
            print json_encode($this->dashboardModel->percentuaisGeraisOcupacao());
        }
    }

    public function percentuaisSetorOcupacao(){
        $id_area = $this->request->getPost("id_area");
        if(is_string($id_area) && strlen($id_area)>0){

            print json_encode($this->dashboardModel->percentuaisSetorOcupacao($id_area));
        }else{
            $this->session->setFlashdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function percentuaisSetorOcupacaoClinicaCirurgica(){
        $id_area = $this->request->getPost("id_area");
        if(is_string($id_area) && strlen($id_area)>0){
            
            print json_encode($this->dashboardModel->percentuaisSetorOcupacao($id_area));
        }else{
            $this->session->setFlashdata("danger","Houve um erro inesperado. Atualize a página!");
            return;
        }
    }

    public function retornaSetorLoopPainel(){
        
        $atual = $this->request->getPost("atual");
        $setores_painel = $this->dashboardModel->retornaSetorLoopPainel();
        for($i = 0;$i<count($setores_painel);$i++){
            if($setores_painel[$i]["NR_SETOR"]==$atual){
                $anterior   = $setores_painel[$i]["NR_SETOR"];
                if($i+1==count($setores_painel)){
                    $proximo = $setores_painel[0]["NR_SETOR"];
                }else{
                    $proximo    = $setores_painel[$i+1]["NR_SETOR"];
                } 
            }
        }
        
        if($proximo){
            print json_encode(array("PROXIMO"=>$proximo));
        }
    }

    function atualizarVariavelPainelControleSessao(){
        $proximo = $this->request->getPost("proximo");
        $usuario = $this->session->get("usuario_logado");
        $usuario["painel_variavel_controle"] = $proximo;
        $this->session->set("usuario_logado",$usuario);
        print json_encode(array("RES"=>"OK"));
    }

       
}
