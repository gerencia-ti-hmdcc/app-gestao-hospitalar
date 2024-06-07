<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("administrador_model");
        $usuario = $this->session->userdata("usuario_logado");
        if(!$usuario){
            header('Location: '.base_url('../').'login/logout');
        }else{
            //REDIRECIONA USUARIO QUE TENTA ACESSAR O MÓDULO ADMINISTRADOR QUE NÃO É ADM.
            $this->load->model("my_model");
            $funcao_permitida = $this->my_model->retornaSeFuncaoPermitida($usuario["ID"],'logs');
            if(!count($funcao_permitida)>0){
                header('Location: /../dashboard');
            }
            // $perfil = $this->administrador_model->tipoPerfilUsuario($_SESSION["usuario_logado"]["ID"]);
            // if($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='A' OR $perfil["TIPO_PERFIL"]!='A'){
            //     header('Location: '.base_url('../').'login/logout');
            // }
        }
    }

	public function index()
	{
        // $this->load->model('administrador_model');
        $this->load->helper('form');
        $dados['pagina']            = 'logs/logs_usuarios.php';
        $dados['nome_pagina']       = 'Logs de Usuários';
        $dados["link_pagina"]       = 'logs';
        // $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
	}

    public function retornaUsuarios(){
        $this->load->model('administrador_model');
        $usuarios = $this->administrador_model->retornaUsuarios();
        $i = 0;
        for($i = 0; $i<count($usuarios);$i++){
            if($usuarios[$i]["ULTIMO_LOGIN"]!=null){
                $usuarios[$i]["ULTIMO_LOGIN"] = date("d/m/Y H:i:s", strtotime($usuarios[$i]["ULTIMO_LOGIN"]));
            }else{
                $usuarios[$i]["ULTIMO_LOGIN"] = "-";
            }

            $usuarios[$i]["TIPO_PERFIL"] = $usuarios[$i]["NOME_TIPO_PERFIL"];
            
            if($usuarios[$i]["IE_STATUS"]=='A'){
                $usuarios[$i]["IE_STATUS"] = "Ativo";
            }else if($usuarios[$i]["IE_STATUS"]=='I'){
                $usuarios[$i]["IE_STATUS"] = "Inativo";
            }
        }
        print json_encode($usuarios);
    }

    public function logUsuario($id_usuario=0){
        if($id_usuario==0){
            $id_usuario = $this->input->post("usuario_escolhido");
        }else{
            $id_usuario = $id_usuario;
        }
        if($id_usuario!=0){
            $this->load->model('logs_model');
            $dados["dados_usuario"]                     =  $this->logs_model->retornaDadosBasicosUsuario($id_usuario);
            if($dados["dados_usuario"]["IE_STATUS"]=="A"){
                $dados["dados_usuario"]["STATUS"]    = "Ativo";
            }else{
                $dados["dados_usuario"]["STATUS"]    = "Inativo";
            }

            if(isset($dados["dados_usuario"]["ULTIMO_LOGIN"])){
                if(strlen($dados["dados_usuario"]["ULTIMO_LOGIN"])>0){
                    $dados["dados_usuario"]["ULTIMO_LOGIN"]     = date($dados["dados_usuario"]["ULTIMO_LOGIN"],strtotime('d/m/Y H:i:s'));
                }else{
                    $dados["dados_usuario"]["ULTIMO_LOGIN"]     = "-";
                }
            }else{
                $dados["dados_usuario"]["ULTIMO_LOGIN"]     = "-";
            }

            $dados['pagina']                            = 'logs/log_usuario.php';
            $dados['nome_pagina']                       = 'Log Usuário';
            $dados["link_pagina"]                       = 'logs/logUsuario';
            $dados["diretorio_raiz"]                    = '../';
            $this->load->view("templates/template_padrao.php",$dados);
        }else{
            redirect("../logs");
        }
    }

    public function retornaLogUsuarioPeriodo(){
        $id_usuario = $this->input->post("id_usuario");
        $data1      = $this->input->post("data1");
        $data2      = $this->input->post("data2");

        if(strlen($id_usuario)!=0){
            $this->load->model('logs_model');
            $logs = $this->logs_model->retornaLogUsuarioPeriodo($id_usuario,$data1,$data2);
        }else{
            $logs["ERRO"] = "Erro!";
        }
        print json_encode($logs);
    }

}
