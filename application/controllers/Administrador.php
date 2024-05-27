<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("administrador_model");
        $usuario = $this->session->userdata("usuario_logado");
        if(!$usuario){
            header('Location: '.base_url('../').'login/logout');
        }else{
            //REDIRECIONA USUARIO QUE TENTA ACESSAR O MÓDULO ADMINISTRADOR QUE NÃO É ADM
            $this->load->model("my_model");
            $funcao_permitida = $this->my_model->retornaSeFuncaoPermitida($usuario["ID"],'administrador');
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
        $this->load->model('administrador_model');
        $this->load->helper('form');
        $dados['pagina']            = 'administrador/gerenciar_usuarios.php';
        $dados['nome_pagina']       = 'Gerenciar Usuários';
        $dados["link_pagina"]       = 'administrador';
        // $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
	}

    // public function usuarios(){
    //     $this->load->model('administrador_model');
    //     $this->load->helper('form');
    //     $dados['pagina']            = 'administrador/gerenciar_usuarios.php';
    //     $dados['nome_pagina']       = 'Gerenciar Usuários';
    //     $dados["link_pagina"]       = 'administrador/usuarios';
    //     $dados["diretorio_raiz"]    = '../';
    //     $this->load->view('templates/template_padrao.php',$dados); 
    // }

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

            // if($usuarios[$i]["TIPO_PERFIL"]=='A'){
            //     $usuarios[$i]["TIPO_PERFIL"] = "Administrador";
            // }else if($usuarios[$i]["TIPO_PERFIL"]=='C'){
            //     $usuarios[$i]["TIPO_PERFIL"] = "Comum";
            // }else if($usuarios[$i]["TIPO_PERFIL"]=='D'){
            //     $usuarios[$i]["TIPO_PERFIL"] = "Diretoria";
            // }else if($usuarios[$i]["TIPO_PERFIL"]=='E'){
            //     $usuarios[$i]["TIPO_PERFIL"] = "Diretor Executivo";
            // }else if($usuarios[$i]["TIPO_PERFIL"]=='G'){
            //     $usuarios[$i]["TIPO_PERFIL"] = "Gerência";
            // }else if($usuarios[$i]["TIPO_PERFIL"]=='P'){
            //     $usuarios[$i]["TIPO_PERFIL"] = "Painel";
            // }
            $usuarios[$i]["TIPO_PERFIL"] = $usuarios[$i]["NOME_TIPO_PERFIL"];
            
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
            // if($dados["usuario"]["TIPO_PERFIL"]=='A'){
            //     $dados["usuario"]["TIPO_PERFIL"] = "Administrador";
            // }else if($dados["usuario"]["TIPO_PERFIL"]=='C'){
            //     $dados["usuario"]["TIPO_PERFIL"] = "Comum";
            // }else if($dados["usuario"]["TIPO_PERFIL"]=='D'){
            //     $dados["usuario"]["TIPO_PERFIL"] = "Diretoria";
            // }else if($dados["usuario"]["TIPO_PERFIL"]=='E'){
            //     $dados["usuario"]["TIPO_PERFIL"] = "Diretor Executivo";
            // }else if($dados["usuario"]["TIPO_PERFIL"]=='G'){
            //     $dados["usuario"]["TIPO_PERFIL"] = "Gerência";
            // }else if($dados["usuario"]["TIPO_PERFIL"]=='P'){
            //     $dados["usuario"]["TIPO_PERFIL"] = "Painel";
            // }
            $dados["usuario"]["TIPO_PERFIL"] = $dados["usuario"]["NOME_TIPO_PERFIL"];

            if($dados["usuario"]["IE_STATUS"]=='A'){
                $dados["usuario"]["IE_STATUS"] = "Ativo";
            }else if($dados["usuario"]["IE_STATUS"]=='I'){
                $dados["usuario"]["IE_STATUS"] = "Inativo";
            }

            $dados["tipos_perfil"]      = $this->administrador_model->retornaTodosTiposPerfis();
            
            $dados["status_possiveis"]  = $this->administrador_model->retornaTodosStatus();
            
            $dados["id_usuario"]        = $id;
            $dados['pagina']            = 'administrador/editar_usuario.php';
            $dados['nome_pagina']       = 'Editar Usuário';
            $dados["link_pagina"]       = 'administrador/editarUsuario';
            $dados["diretorio_raiz"]    = '../';
            $this->load->view("templates/template_padrao.php",$dados);
        }else{
            redirect("../administrador");
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
        }else if(strlen($status)<1 OR strlen($status)>1){
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
                $atualizado = $this->administrador_model->atualizaUsuario($id,$nome,$email,$status,$tipo_perfil);
                
                if($atualizado==true){
                    $this->logAcaoUsuario("atualização de usuário - id_usuario $id", NULL, NULL, $id);
                    // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

                    if($this->input->post("resetar_senha")){
                        $this->administrador_model->resetaSenha($id);
                    }
                    $this->session->set_flashdata("success","<br />Usuário atualizado com sucesso!");
                    redirect("../administrador");
                }
            }
        }
        
    }

    public function novoUsuario(){
        $this->load->model('administrador_model');
        $this->load->helper('form');

        $dados["tipos_perfil"]      = $this->administrador_model->retornaTodosTiposPerfis();
        
        $dados["status_possiveis"]  = $this->administrador_model->retornaTodosStatus();
        
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
        }else if(strlen($status)<1 OR strlen($status)>1){
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
                if($tipo_perfil=='Administrador'){
                    $tipo_perfil = "A";
                }else if($tipo_perfil=='Comum'){
                    $tipo_perfil = "C";
                }else if($tipo_perfil=='Diretoria'){
                    $tipo_perfil = "D";
                }else if($tipo_perfil=='Diretor Executivo'){
                    $tipo_perfil = "E";
                }else if($tipo_perfil=='Gerência'){
                    $tipo_perfil = "G";
                }else if($tipo_perfil=='Painel'){
                    $tipo_perfil = "P";
                }else if($tipo_perfil=='Ocupação (Internação)'){
                    $tipo_perfil = "I";
                }else if($tipo_perfil=='Central de Leitos'){
                    $tipo_perfil = "L";
                }
        
                $cadastrado = $this->administrador_model->cadastraUsuario($nome,$email,$status,$tipo_perfil);
                
                if($cadastrado==true){
                    $this->logAcaoUsuario("cadastro de usuário - email $email", NULL, NULL, $email);
                    // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

                    $this->session->set_flashdata("success","<br />Usuário cadastrado com sucesso!");
                    redirect("../administrador");
                }
            }
        }
    }

    /*
    public function metas_admissoes(){
        $this->load->model('administrador_model');
        $this->load->helper('form');
        $dados['pagina']            = 'administrador/metas_admissoes.php';
        $dados['nome_pagina']       = 'Gerenciar Metas';
        $dados["link_pagina"]       = 'administrador/metas_admissoes';
        $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
    }

    public function retornaMetasAdmissoes(){
        $this->load->model('administrador_model');
        $metas = $this->administrador_model->retornaMetasAdmissoes();
        $i = 0;
        for($i = 0; $i<count($metas);$i++){
            if($metas[$i]["TIPO_ADMISSAO"]=="I"){
                $metas[$i]["TIPO_ADMISSAO_COMPLETO"] = "Internas";
            }else if($metas[$i]["TIPO_ADMISSAO"]=="E"){
                $metas[$i]["TIPO_ADMISSAO_COMPLETO"] = "Externas";
            }
        }
        print json_encode($metas);
    }

    public function novaMeta(){
        $this->load->model('administrador_model');
        $this->load->helper('form');

        $dados["agrupamentos"]      = $this->administrador_model->retornaTodosAgrupamentos();
        
        $dados['pagina']            = 'administrador/adicionar_meta.php';
        $dados['nome_pagina']       = 'Adicionar Meta';
        $dados["link_pagina"]       = 'administrador/novaMeta';
        $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
    }

    public function cadastrarMeta(){
        $this->load->model('administrador_model');
        $ano            = addslashes(trim($this->input->post("ano")));
        $quadrimestre   = addslashes($this->input->post("quadrimestre"));
        $tipo           = addslashes($this->input->post("tipo"));
        $linha          = addslashes($this->input->post("linha"));
        $quantidade     = addslashes(trim($this->input->post("quantidade")));

        if(strlen($ano)<4){
            $this->session->set_flashdata("danger","<br />O ano deve conter pelo menos 4 caracteres!");
            $this->novaMeta();
        }else if(strlen($quadrimestre)<1){
            $this->session->set_flashdata("danger","<br />O campo quadrimestre é obrigatório!");
            $this->novaMeta();
        }else if(strlen($tipo)<1){
            $this->session->set_flashdata("danger","<br />O campo tipo é obrigatório!");
            $this->novaMeta();
        }else if(strlen($linha)<1){
            $this->session->set_flashdata("danger","<br />O campo linha é obrigatório!");
            $this->novaMeta();
        }else if(strlen($quantidade)<1 || $quantidade<=0){
            $this->session->set_flashdata("danger","<br />A quantidade é obrigatória e deve ser diferente de 0!");
            $this->novaMeta();
        }else{
           
            $cadastrado = $this->administrador_model->cadastraMeta($ano,$quadrimestre,$tipo,$linha,$quantidade);
            
            if($cadastrado==true){
                $this->session->set_flashdata("success","<br />Meta cadastrada com sucesso!");
                redirect("metas_admissoes");
            }
        }
    }

    public function editarMeta($id=0){

        //exit(print_r($this->input->post()));
        if($id==0){
            $id = $this->input->post("meta_escolhida");
        }else{
            $id = $id;
        }

        if($id>0){
            $this->load->model('administrador_model');
            $this->load->helper('form');
            $dados["meta"]           = $this->administrador_model->retornaMeta($id);
            
            $dados["agrupamentos"]      = $this->administrador_model->retornaTodosAgrupamentos();
            
            $dados["id_meta"]        = $id;
            $dados['pagina']            = 'administrador/editar_meta.php';
            $dados['nome_pagina']       = 'Editar Meta';
            $dados["link_pagina"]       = 'administrador/editarMeta';
            $dados["diretorio_raiz"]    = '../';
            $this->load->view("templates/template_padrao.php",$dados);
        }else{
            redirect("metas_admissoes");
        }
    }

    public function atualizarMeta(){
        //exit(print_r($this->input->post()));
        $this->load->model('administrador_model');
        $id             = (int) $this->input->post("id_meta");
        $ano            = addslashes(trim($this->input->post("ano")));
        $quadrimestre   = addslashes($this->input->post("quadrimestre"));
        $tipo           = addslashes($this->input->post("tipo"));
        $linha          = addslashes($this->input->post("linha"));
        $quantidade     = addslashes(trim($this->input->post("quantidade")));

        if($this->input->post("excluir_meta")){
            $this->administrador_model->excluirMeta($id);
            $this->session->set_flashdata("success","<br />Meta excluída com sucesso!");
            redirect("metas_admissoes");
        }else if(strlen($ano)<4){
            $this->session->set_flashdata("danger","<br />O ano deve conter pelo menos 4 caracteres!");
            $this->novaMeta();
        }else if(strlen($quadrimestre)<1){
            $this->session->set_flashdata("danger","<br />O campo quadrimestre é obrigatório!");
            $this->novaMeta();
        }else if(strlen($tipo)<1){
            $this->session->set_flashdata("danger","<br />O campo tipo é obrigatório!");
            $this->novaMeta();
        }else if(strlen($linha)<1){
            $this->session->set_flashdata("danger","<br />O campo linha é obrigatório!");
            $this->novaMeta();
        }else if(strlen($quantidade)<1 || $quantidade<=0){
            $this->session->set_flashdata("danger","<br />A quantidade é obrigatória e deve ser diferente de 0!");
            $this->novaMeta();
        }else{
            $atualizado = $this->administrador_model->atualizaMeta($id,$ano,$quadrimestre,$tipo,$linha,$quantidade);
            
            if($atualizado==true){
                $this->session->set_flashdata("success","<br />Meta atualizada com sucesso!");
                redirect("metas_admissoes");
            }
        }
        
    }
    */
}
