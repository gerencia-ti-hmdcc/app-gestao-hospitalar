<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Metas extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("my_model");
        $usuario = $this->session->userdata("usuario_logado");
        if(!$usuario){
            header('Location: '.base_url('../').'login/logout');
        }else{
            $funcao_permitida = $this->my_model->retornaSeFuncaoPermitida($usuario["ID"],'metas');
            if(!count($funcao_permitida)>0){
                header('Location: /../dashboard');
            }
        }
    }

    public function index(){
        $this->load->model('metas_model');
        $this->load->helper('form');
        $dados['pagina']            = 'metas/metas_admissoes.php';
        $dados['nome_pagina']       = 'Gerenciar Metas';
        $dados["link_pagina"]       = 'metas';
        // $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
    }

    public function retornaMetasAdmissoes(){
        $this->load->model('metas_model');
        $metas = $this->metas_model->retornaMetasAdmissoes();
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
        $this->load->model('metas_model');
        $this->load->helper('form');

        $dados["agrupamentos"]      = $this->metas_model->retornaTodosAgrupamentos();
        
        $dados['pagina']            = 'metas/adicionar_meta.php';
        $dados['nome_pagina']       = 'Adicionar Meta';
        $dados["link_pagina"]       = 'metas/novaMeta';
        $dados["diretorio_raiz"]    = '../';
        $this->load->view('templates/template_padrao.php',$dados); 
    }

    public function cadastrarMeta(){
        $this->load->model('metas_model');
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
           
            $cadastrado = $this->metas_model->cadastraMeta($ano,$quadrimestre,$tipo,$linha,$quantidade);
            
            if($cadastrado==true){
                $this->logAcaoUsuario("cadastro de meta de admissão - ano $ano - quadrimestre $quadrimestre - tipo $tipo - linha $linha - quantidade $quantidade");
                // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

                $this->session->set_flashdata("success","<br />Meta cadastrada com sucesso!");
                redirect("../metas");
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
            $this->load->model('metas_model');
            $this->load->helper('form');
            $dados["meta"]           = $this->metas_model->retornaMeta($id);
            
            $dados["agrupamentos"]      = $this->metas_model->retornaTodosAgrupamentos();
            
            $dados["id_meta"]        = $id;
            $dados['pagina']            = 'metas/editar_meta.php';
            $dados['nome_pagina']       = 'Editar Meta';
            $dados["link_pagina"]       = 'metas/editarMeta';
            $dados["diretorio_raiz"]    = '../';
            $this->load->view("templates/template_padrao.php",$dados);
        }else{
            redirect("../metas");
        }
    }

    public function atualizarMeta(){
        //exit(print_r($this->input->post()));
        $this->load->model('metas_model');
        $id             = (int) $this->input->post("id_meta");
        $ano            = addslashes(trim($this->input->post("ano")));
        $quadrimestre   = addslashes($this->input->post("quadrimestre"));
        $tipo           = addslashes($this->input->post("tipo"));
        $linha          = addslashes($this->input->post("linha"));
        $quantidade     = addslashes(trim($this->input->post("quantidade")));

        if($this->input->post("excluir_meta")){
            $this->metas_model->excluirMeta($id);
            $this->session->set_flashdata("success","<br />Meta excluída com sucesso!");
            redirect("../metas");
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
            $atualizado = $this->metas_model->atualizaMeta($id,$ano,$quadrimestre,$tipo,$linha,$quantidade);
            
            if($atualizado==true){
                $this->logAcaoUsuario("atualização de meta de admissão - ano $ano - quadrimestre $quadrimestre - tipo $tipo - linha $linha - quantidade $quantidade");
                // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

                $this->session->set_flashdata("success","<br />Meta atualizada com sucesso!");
                redirect("../metas");
            }
        }
        
    }

       
}
