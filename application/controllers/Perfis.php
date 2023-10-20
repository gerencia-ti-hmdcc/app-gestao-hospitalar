<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfis extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $usuario = $this->session->userdata("usuario_logado");
        if(!$usuario){
            header('Location: '.base_url('../').'login/logout');
        }else{
            $this->load->model("my_model");
            $funcao_permitida = $this->my_model->retornaSeFuncaoPermitida($usuario["ID"],'perfis');
            if(!count($funcao_permitida)>0){
                header('Location: dashboard');
            }
            $this->load->database();
        }
    }

	public function index()
	{
        $this->load->model('perfis_model');
        $this->load->helper('form');
        $dados['pagina']            = 'gestao_perfis/index.php';
        $dados['nome_pagina']       = 'Gerenciar Perfis';
        $dados["link_pagina"]       = 'gestao_perfis';
        // $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
	}

    public function retornaPerfis(){
        $this->load->model('perfis_model');
        $perfis = $this->perfis_model->retornaTiposPerfis();
        
        print json_encode($perfis);
    }

    public function editarPerfil($id=0){

        //exit(print_r($this->input->post()));
        if($id==0){
            $id = $this->input->post("perfil_escolhido");
        }else{
            $id = $id;
        }

        if($id>0){
            $this->load->model('perfis_model');
            $this->load->helper('form');
            $dados["dados_perfil_escolhido"]          = $this->perfis_model->retornaDadosPerfil($id);
            $dados["funcoes_disponiveis_e_liberadas"] = $this->perfis_model->funcoesLiberasParaPerfil($id);

            $dados['pagina']            = 'gestao_perfis/editar_perfil.php';
            $dados['nome_pagina']       = 'Editar Perfil';
            $dados["link_pagina"]       = 'gestao_perfis/editarPerfil';
            $dados["diretorio_raiz"]    = '../';
            $this->load->view("templates/template_padrao.php",$dados);
        }else{
            redirect("../perfis");
        }
    }

    public function atualizarPerfil(){
        $this->load->model('perfis_model');
        // $dados_recebidos  =  $this->input->post();
        // print_r($dados_recebidos);
        // exit();
        $nome               = addslashes(trim($this->input->post("nome")));
        $sigla              = addslashes(trim($this->input->post("sigla")));
        $id_perfil          = addslashes(trim($this->input->post("id_perfil")));
        $funcoes_liberadas  = $this->input->post("funcoes_liberadas");
        if(strlen($nome)<3){
            $this->session->set_flashdata("danger","<br />O nome deve conter pelo menos 3 caracteres!");
            $this->editarPerfil($id_perfil);
        }else if(strlen($sigla)<1){
            $this->session->set_flashdata("danger","<br />A sigla deve conter pelo menos 1 caractere!");
            $this->editarPerfil($id_perfil);
        }else{
            $sigla_verifica = $this->perfis_model->verificaSeJaExisteSiglaAtualizaPerfil($sigla,$id_perfil);
            if(count($sigla_verifica)>0){
                $this->session->set_flashdata("danger","<br />Esta sigla já está sendo utilizada para outro tipo de perfil!");
                $this->editarPerfil($id_perfil);
            }else{
                $dados_perfil["NOME_TIPO_PERFIL"]   = $nome;
                $dados_perfil["SIGLA_TIPO_PERFIL"]  = $sigla;
                $dados_perfil["ATIVO"]              = 1;
                $atualizado = $this->perfis_model->atualizaDadosPerfil($id_perfil,$nome,$sigla);
                if($atualizado==true){
                    $funcoes_permitdas_deletadas = $this->perfis_model->excluirFuncoesPermitidasPerfil($id_perfil);
                    if($funcoes_permitdas_deletadas==true){
                        for($i = 0;$i<count($funcoes_liberadas);$i++){
                            $this->perfis_model->liberarFuncoesParaPerfil($id_perfil,$funcoes_liberadas[$i]);
                        }
                        $this->session->set_flashdata("success","<br />Perfil atualizado com sucesso!");
                        redirect("../perfis");
                    }else{
                        $this->session->set_flashdata("danger","<br />Não foi possível atualizar o perfil!");
                        redirect("../perfis");
                    }
                }else{
                    $this->session->set_flashdata("danger","<br />Não foi possível atualizar o perfil!");
                        redirect("../perfis");
                }
            }
        }
        
    }

    public function novoPerfil(){
        $this->load->model('perfis_model');
        $this->load->helper('form');

        $dados["funcoes_disponiveis_desenvolvidas"] = $this->perfis_model->retornaTodasFuncoes();
        
        $dados['pagina']            = 'gestao_perfis/adicionar_perfil.php';
        $dados['nome_pagina']       = 'Adicionar Perfil';
        $dados["link_pagina"]       = 'gestao_perfis/novoPerfil';
        $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
    }

    public function cadastrarPerfil(){
        $this->load->model('perfis_model');
        // $dados_recebidos  =  $this->input->post();
        // print_r($dados_recebidos);
        // exit();
        $nome               = addslashes(trim($this->input->post("nome")));
        $sigla              = addslashes(trim($this->input->post("sigla")));
        $funcoes_liberadas  = $this->input->post("funcoes_liberadas");
        if(strlen($nome)<3){
            $this->session->set_flashdata("danger","<br />O nome deve conter pelo menos 3 caracteres!");
            $this->novoPerfil();
        }else if(strlen($sigla)<1){
            $this->session->set_flashdata("danger","<br />A sigla deve conter pelo menos 1 caractere!");
            $this->novoPerfil();
        }else{
            $sigla_verifica = $this->perfis_model->verificaSeJaExisteSigla($sigla);
            if(count($sigla_verifica)>0){
                $this->session->set_flashdata("danger","<br />Esta sigla já está sendo utilizada para outro tipo de perfil!");
                $this->novoPerfil();
            }else{
                $dados_perfil["NOME_TIPO_PERFIL"]   = $nome;
                $dados_perfil["SIGLA_TIPO_PERFIL"]  = $sigla;
                $dados_perfil["ATIVO"]              = 1;
                // $cadastrado = $this->perfis_model->cadastraPerfil($nome,$sigla);
                $cadastrado = $this->db->insert("CONFIG_USUARIO_TIPO_PERFIL",$dados_perfil);
                $id_perfil_cadastrado = $this->db->insert_id();
                if($cadastrado==true){
                    for($i = 0;$i<count($funcoes_liberadas);$i++){
                        $this->perfis_model->liberarFuncoesParaPerfil($id_perfil_cadastrado,$funcoes_liberadas[$i]);
                    }
                    $this->session->set_flashdata("success","<br />Perfil cadastrado com sucesso!");
                    redirect("../perfis");
                }
            }
        }
    }

}
