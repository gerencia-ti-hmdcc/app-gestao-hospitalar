<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("administrador_model");
        //DESLOGA USUARIO QUE TENTA ACESSAR O MÓDULO ADMINISTRADOR QUE NÃO É ADM
        $perfil = $this->administrador_model->tipoPerfilUsuario($_SESSION["usuario_logado"]["ID"]);
        if($_SESSION["usuario_logado"]["TIPO_PERFIL"]!='A' OR $perfil["TIPO_PERFIL"]!='A'){
            header('Location: '.base_url('../').'login/logout');
        }
    }

	public function index()
	{
        // //$this->load->model('login_model');
        // //$this->load->view('templates/template_login.php');
        // if($this->input->is_ajax_request()){

        // }else{
        //         $this->load->helper('form');
        //         $dados['pagina']        = 'dashboard/index.php';
        //         $dados['nome_pagina']   = 'Ocupação Hospitalar';
        //         $dados["link_pagina"]   = 'dashboard';
        //         $this->load->view('templates/template_padrao.php',$dados);   
        // }
	}

    public function usuarios(){
        $this->load->model('administrador_model');
        $this->load->helper('form');
        $dados['pagina']            = 'administrador/gerenciar_usuarios.php';
        $dados['nome_pagina']       = 'Gerenciar Usuários';
        $dados["link_pagina"]       = 'administrador/usuarios';
        $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
    }

    public function retornaUsuarios(){
        $this->load->model('administrador_model');
        $usuarios = $this->administrador_model->retornaUsuarios();
        $i = 0;
        for($i = 0; $i<count($usuarios);$i++){
            if($usuarios[$i]["ULTIMO_LOGIN"]!=null){
                $usuarios[$i]["ULTIMO_LOGIN"] = date("d/m/Y H:i", strtotime($usuarios[$i]["ULTIMO_LOGIN"]));
            }else{
                $usuarios[$i]["ULTIMO_LOGIN"] = "-";
            }

            if($usuarios[$i]["TIPO_PERFIL"]=='A'){
                $usuarios[$i]["TIPO_PERFIL"] = "Administrador";
            }else if($usuarios[$i]["TIPO_PERFIL"]=='C'){
                $usuarios[$i]["TIPO_PERFIL"] = "Comum";
            }else if($usuarios[$i]["TIPO_PERFIL"]=='D'){
                $usuarios[$i]["TIPO_PERFIL"] = "Diretoria";
            }else if($usuarios[$i]["TIPO_PERFIL"]=='G'){
                $usuarios[$i]["TIPO_PERFIL"] = "Gerência";
            }
            
            if($usuarios[$i]["IE_STATUS"]=='A'){
                $usuarios[$i]["IE_STATUS"] = "Ativo";
            }else if($usuarios[$i]["IE_STATUS"]=='I'){
                $usuarios[$i]["IE_STATUS"] = "Inativo";
            }
        }
        print json_encode($usuarios);
    }

    public function editarUsuario($id=0){

        //exit(print_r($this->input->post()));
        if($id==0){
            $id = $this->input->post("usuario_escolhido");
        }else{
            $id = $id;
        }

        if($id>0){
            $this->load->model('administrador_model');
            $this->load->helper('form');
            $dados["usuario"]           = $this->administrador_model->retornaUsuario($id);
            if($dados["usuario"]["TIPO_PERFIL"]=='A'){
                $dados["usuario"]["TIPO_PERFIL"] = "Administrador";
            }else if($dados["usuario"]["TIPO_PERFIL"]=='C'){
                $dados["usuario"]["TIPO_PERFIL"] = "Comum";
            }else if($dados["usuario"]["TIPO_PERFIL"]=='D'){
                $dados["usuario"]["TIPO_PERFIL"] = "Diretoria";
            }else if($dados["usuario"]["TIPO_PERFIL"]=='G'){
                $dados["usuario"]["TIPO_PERFIL"] = "Gerência";
            }

            if($dados["usuario"]["IE_STATUS"]=='A'){
                $dados["usuario"]["IE_STATUS"] = "Ativo";
            }else if($dados["usuario"]["IE_STATUS"]=='I'){
                $dados["usuario"]["IE_STATUS"] = "Inativo";
            }

            $dados["tipos_perfil"]      = $this->administrador_model->retornaTodosTiposPerfis();
            for($i=0;$i<count($dados["tipos_perfil"]);$i++){
                if($dados["tipos_perfil"][$i]["TIPO_PERFIL"]=='A'){
                    $dados["tipos_perfil"][$i] = "Administrador";
                }else if($dados["tipos_perfil"][$i]["TIPO_PERFIL"]=='C'){
                    $dados["tipos_perfil"][$i] = "Comum";
                }else if($dados["tipos_perfil"][$i]["TIPO_PERFIL"]=='D'){
                    $dados["tipos_perfil"][$i] = "Diretoria";
                }else if($dados["tipos_perfil"][$i]["TIPO_PERFIL"]=='G'){
                    $dados["tipos_perfil"][$i] = "Gerência";
                }
            }
            
            $dados["status_possiveis"]  = $this->administrador_model->retornaTodosStatus();
            for($i=0;$i<count($dados["status_possiveis"]);$i++){
                if($dados["status_possiveis"][$i]["IE_STATUS"]=='A'){
                    $dados["status_possiveis"][$i] = "Ativo";
                }else if($dados["status_possiveis"][$i]["IE_STATUS"]=='I'){
                    $dados["status_possiveis"][$i] = "Inativo";
                }
            }
            $dados["id_usuario"]        = $id;
            $dados['pagina']            = 'administrador/editar_usuario.php';
            $dados['nome_pagina']       = 'Editar Usuário';
            $dados["link_pagina"]       = 'administrador/editarUsuario';
            $dados["diretorio_raiz"]    = '../';
            $this->load->view("templates/template_padrao.php",$dados);
        }else{
            redirect("usuarios");
        }
    }

    public function atualizarUsuario(){
        //exit(print_r($this->input->post()));
        $this->load->model('administrador_model');
        $nome           = addslashes(trim($this->input->post("nome")));
        $email          = addslashes(trim($this->input->post("email")));
        $status         = addslashes($this->input->post("status"));
        $tipo_perfil    = addslashes($this->input->post("tipo_perfil"));
        $id             = (int) $this->input->post("id_usuario");

        if(strlen($nome)<3){
            $this->session->set_flashdata("danger","<br />O nome deve conter pelo menos 3 caracteres!");
            $this->editarUsuario($id);
        }else if(strlen($email)<3){
            $this->session->set_flashdata("danger","<br />E-mail inválido!");
            $this->editarUsuario($id);         
        }else if(strlen($status)<1){
            $this->session->set_flashdata("danger","<br />Status inválido!");
            $this->editarUsuario($id);       
        }else if(strlen($tipo_perfil)<1){
            $this->session->set_flashdata("danger","<br />Perfil inválido!");
            $this->editarUsuario($id);   
        }else{
            $email_verifica = $this->administrador_model->existeEmailUsuario($email,$id);
            if(strlen($email_verifica["EMAIL"])>0){
                $this->session->set_flashdata("danger","<br />Este e-mail já está cadastrado no sistema!");
                $this->editarUsuario($id);
            }else{
                if($status=='Ativo'){
                    $status = "A";
                }else if($status=='Inativo'){
                    $status = "I";
                }
        
                if($tipo_perfil=='Administrador'){
                    $tipo_perfil = "A";
                }else if($tipo_perfil=='Comum'){
                    $tipo_perfil = "C";
                }else if($tipo_perfil=='Diretoria'){
                    $tipo_perfil = "D";
                }else if($tipo_perfil=='Gerência'){
                    $tipo_perfil = "G";
                }
        
                $atualizado = $this->administrador_model->atualizaUsuario($id,$nome,$email,$status,$tipo_perfil);
                
                if($atualizado==true){
                    if($this->input->post("resetar_senha")){
                        $this->administrador_model->resetaSenha($id);
                    }
                    $this->session->set_flashdata("success","<br />Usuário atualizado com sucesso!");
                    redirect("usuarios");
                }
            }
        }
        
    }

    public function novoUsuario(){
        $this->load->model('administrador_model');
        $this->load->helper('form');

        $dados["tipos_perfil"]      = $this->administrador_model->retornaTodosTiposPerfis();
        for($i=0;$i<count($dados["tipos_perfil"]);$i++){
            if($dados["tipos_perfil"][$i]["TIPO_PERFIL"]=='A'){
                $dados["tipos_perfil"][$i] = "Administrador";
            }else if($dados["tipos_perfil"][$i]["TIPO_PERFIL"]=='C'){
                $dados["tipos_perfil"][$i] = "Comum";
            }else if($dados["tipos_perfil"][$i]["TIPO_PERFIL"]=='D'){
                $dados["tipos_perfil"][$i] = "Diretoria";
            }else if($dados["tipos_perfil"][$i]["TIPO_PERFIL"]=='G'){
                $dados["tipos_perfil"][$i] = "Gerência";
            }
        }
        
        $dados["status_possiveis"]  = $this->administrador_model->retornaTodosStatus();
        for($i=0;$i<count($dados["status_possiveis"]);$i++){
            if($dados["status_possiveis"][$i]["IE_STATUS"]=='A'){
                $dados["status_possiveis"][$i] = "Ativo";
            }else if($dados["status_possiveis"][$i]["IE_STATUS"]=='I'){
                $dados["status_possiveis"][$i] = "Inativo";
            }
        }
        $dados['pagina']            = 'administrador/adicionar_usuario.php';
        $dados['nome_pagina']       = 'Adicionar Usuário';
        $dados["link_pagina"]       = 'administrador/novoUsuario';
        $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
    }

    public function cadastrarUsuario(){
        $this->load->model('administrador_model');
        $nome           = addslashes(trim($this->input->post("nome")));
        $email          = addslashes(trim($this->input->post("email")));
        $status         = addslashes($this->input->post("status"));
        $tipo_perfil    = addslashes($this->input->post("tipo_perfil"));

        if(strlen($nome)<3){
            $this->session->set_flashdata("danger","<br />O nome deve conter pelo menos 3 caracteres!");
            $this->novoUsuario();
        }else if(strlen($email)<3){
            $this->session->set_flashdata("danger","<br />E-mail inválido!");
            $this->novoUsuario();
        }else if(strlen($status)<1){
            $this->session->set_flashdata("danger","<br />Status inválido!");
            $this->novoUsuario();
        }else if(strlen($tipo_perfil)<1){
            $this->session->set_flashdata("danger","<br />Perfil inválido!");
            $this->novoUsuario();
        }else{
            $email_verifica = $this->administrador_model->existeEmailUsuario($email);
            if(strlen($email_verifica["EMAIL"])>0){
                $this->session->set_flashdata("danger","<br />Este e-mail já existe no sistema!");
                $this->novoUsuario();
            }else{
                if($status=='Ativo'){
                    $status = "A";
                }else if($status=='Inativo'){
                    $status = "I";
                }
        
                if($tipo_perfil=='Administrador'){
                    $tipo_perfil = "A";
                }else if($tipo_perfil=='Comum'){
                    $tipo_perfil = "C";
                }else if($tipo_perfil=='Diretoria'){
                    $tipo_perfil = "D";
                }else if($tipo_perfil=='Gerência'){
                    $tipo_perfil = "G";
                }
        
                $cadastrado = $this->administrador_model->cadastraUsuario($nome,$email,$status,$tipo_perfil);
                
                if($cadastrado==true){
                    $this->session->set_flashdata("success","<br />Usuário cadastrado com sucesso!");
                    redirect("usuarios");
                }
            }
        }
    }

       
}
