<?php
namespace App\Controllers;

use App\Models\MyModel;
use App\Models\MetasModel;

class Metas extends BaseController {

    protected $myModel;
    protected $metasModel;

    public function __construct(){

        $this->myModel = new MyModel();
        $this->metasModel = new MetasModel();

    }
    
    public function index()
    {
        $redirect = $this->verificaPerfilUsuario('metas');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        helper('form');
        $dados['pagina']            = 'metas/metas_admissoes.php';
        $dados['nome_pagina']       = 'Gerenciar Metas';
        $dados["link_pagina"]       = 'metas';
        // $dados["diretorio_raiz"]    = '../';
        return view('templates/template_padrao.php',$dados); 
    }

    public function retornaMetasAdmissoes(){

        $metas = $this->metasModel->retornaMetasAdmissoes();
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

        helper('form');

        $dados["agrupamentos"]      = $this->metasModel->retornaTodosAgrupamentos();
        
        $dados['pagina']            = 'metas/adicionar_meta.php';
        $dados['nome_pagina']       = 'Adicionar Meta';
        $dados["link_pagina"]       = 'metas/novaMeta';
        $dados["diretorio_raiz"]    = '../';
        return view('templates/template_padrao.php',$dados); 
    }

    public function cadastrarMeta(){
        
        $ano            = addslashes(trim((string)$this->request->getPost("ano")));
        $quadrimestre   = addslashes((string)$this->request->getPost("quadrimestre"));
        $tipo           = addslashes((string)$this->request->getPost("tipo"));
        $linha          = addslashes((string)$this->request->getPost("linha"));
        $quantidade     = addslashes(trim((string)$this->request->getPost("quantidade")));

        if(strlen($ano)<4){
            $this->session->setFlashdata("danger","<br />O ano deve conter pelo menos 4 caracteres!");
            $this->novaMeta();
        }else if(strlen($quadrimestre)<1){
            $this->session->setFlashdata("danger","<br />O campo quadrimestre é obrigatório!");
            $this->novaMeta();
        }else if(strlen($tipo)<1){
            $this->session->setFlashdata("danger","<br />O campo tipo é obrigatório!");
            $this->novaMeta();
        }else if(strlen($linha)<1){
            $this->session->setFlashdata("danger","<br />O campo linha é obrigatório!");
            $this->novaMeta();
        }else if(strlen($quantidade)<1 || $quantidade<=0){
            $this->session->setFlashdata("danger","<br />A quantidade é obrigatória e deve ser diferente de 0!");
            $this->novaMeta();
        }else{
           
            $cadastrado = $this->metasModel->cadastraMeta($ano,$quadrimestre,$tipo,$linha,$quantidade);
            
            if($cadastrado==true){

                $this->logAcaoUsuario("cadastro de meta de admissão - ano $ano - quadrimestre $quadrimestre - tipo $tipo - linha $linha - quantidade $quantidade");
                // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

                $this->session->setFlashdata("success","<br />Meta cadastrada com sucesso!");
                return redirect()->to('metas');

            }
        }
    }

    public function editarMeta($id=0){

        if($id==0){
            $id = $this->request->getPost("meta_escolhida");
        }else{
            $id = $id;
        }

        if($id>0){
            
            helper('form');
            $dados["meta"]           = $this->metasModel->retornaMeta($id);
            
            $dados["agrupamentos"]      = $this->metasModel->retornaTodosAgrupamentos();
            
            $dados["id_meta"]        = $id;
            $dados['pagina']            = 'metas/editar_meta.php';
            $dados['nome_pagina']       = 'Editar Meta';
            $dados["link_pagina"]       = 'metas/editarMeta';
            $dados["diretorio_raiz"]    = '../';
            return view("templates/template_padrao.php",$dados);
        }else{
            return redirect()->to('metas');
        }
    }

    public function atualizarMeta(){
    
        $id             = (int) $this->request->getPost("id_meta");
        $ano            = addslashes(trim((string)$this->request->getPost("ano")));
        $quadrimestre   = addslashes((string)$this->request->getPost("quadrimestre"));
        $tipo           = addslashes((string)$this->request->getPost("tipo"));
        $linha          = addslashes((string)$this->request->getPost("linha"));
        $quantidade     = addslashes(trim((string)$this->request->getPost("quantidade")));

        if($this->request->getPost("excluir_meta")){
            $this->metasModel->excluirMeta($id);
            $this->session->setFlashdata("success","<br />Meta excluída com sucesso!");
            return redirect()->to('metas');
        }else if(strlen($ano)<4){
            $this->session->setFlashdata("danger","<br />O ano deve conter pelo menos 4 caracteres!");
            $this->novaMeta();
        }else if(strlen($quadrimestre)<1){
            $this->session->setFlashdata("danger","<br />O campo quadrimestre é obrigatório!");
            $this->novaMeta();
        }else if(strlen($tipo)<1){
            $this->session->setFlashdata("danger","<br />O campo tipo é obrigatório!");
            $this->novaMeta();
        }else if(strlen($linha)<1){
            $this->session->setFlashdata("danger","<br />O campo linha é obrigatório!");
            $this->novaMeta();
        }else if(strlen($quantidade)<1 || $quantidade<=0){
            $this->session->setFlashdata("danger","<br />A quantidade é obrigatória e deve ser diferente de 0!");
            $this->novaMeta();
        }else{
            $atualizado = $this->metasModel->atualizaMeta($id,$ano,$quadrimestre,$tipo,$linha,$quantidade);
            
            if($atualizado==true){

                $this->logAcaoUsuario("atualização de meta de admissão - ano $ano - quadrimestre $quadrimestre - tipo $tipo - linha $linha - quantidade $quantidade");
                // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);
                
                $this->session->setFlashdata("success","<br />Meta atualizada com sucesso!");
                return redirect()->to('metas');
            }
        }
        
    }

       
}
