<?php
namespace App\Controllers;

use App\Models\MyModel;
use App\Models\PerfisModel;

class Perfis extends BaseController {

    protected $myModel;
    protected $perfisModel;

    public function __construct(){

        $this->myModel = new MyModel();
        $this->perfisModel = new PerfisModel();

    }
    
	public function index()
	{
        $redirect = $this->verificaPerfilUsuario('perfis');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        helper('form');
        $dados['pagina']            = 'gestao_perfis/index.php';
        $dados['nome_pagina']       = 'Gerenciar Perfis';
        $dados["link_pagina"]       = 'gestao_perfis';
        // $dados["diretorio_raiz"]    = '../';
        return view('templates/template_padrao.php',$dados); 
	}

    public function retornaPerfis(){
        
        $perfis = $this->perfisModel->retornaTiposPerfis();
        
        print json_encode($perfis);
    }

    public function editarPerfil($id=0){
        if($id==0){
            $id = $this->request->getPost("perfil_escolhido");
        }else{
            $id = $id;
        }

        if($id>0){
          
            helper('form');
            $dados["dados_perfil_escolhido"]          = $this->perfisModel->retornaDadosPerfil($id);
            $dados["funcoes_disponiveis_e_liberadas"] = $this->perfisModel->funcoesLiberasParaPerfil($id);

            $dados['pagina']            = 'gestao_perfis/editar_perfil.php';
            $dados['nome_pagina']       = 'Editar Perfil';
            $dados["link_pagina"]       = 'gestao_perfis/editarPerfil';
            $dados["diretorio_raiz"]    = '../';
            return view("templates/template_padrao.php",$dados);
        }else{
            return redirect()->to('perfis');
        }
    }

    public function atualizarPerfil(){

        $nome               = addslashes(trim((string)$this->request->getPost("nome")));
        $sigla              = addslashes(trim((string)$this->request->getPost("sigla")));
        $id_perfil          = addslashes(trim((string)$this->request->getPost("id_perfil")));
        $funcoes_liberadas  = $this->request->getPost("funcoes_liberadas");
        if(strlen($nome)<3){
            $this->session->setFlashdata("danger","<br />O nome deve conter pelo menos 3 caracteres!");
            $this->editarPerfil($id_perfil);
        }else if(strlen($sigla)<1){
            $this->session->setFlashdata("danger","<br />A sigla deve conter pelo menos 1 caractere!");
            $this->editarPerfil($id_perfil);
        }else{
            $sigla_verifica = $this->perfisModel->verificaSeJaExisteSiglaAtualizaPerfil($sigla,$id_perfil);
            if(count($sigla_verifica)>0){
                $this->session->setFlashdata("danger","<br />Esta sigla já está sendo utilizada para outro tipo de perfil!");
                $this->editarPerfil($id_perfil);
            }else{
                $dados_perfil["NOME_TIPO_PERFIL"]   = $nome;
                $dados_perfil["SIGLA_TIPO_PERFIL"]  = $sigla;
                $dados_perfil["ATIVO"]              = 1;
                $atualizado = $this->perfisModel->atualizaDadosPerfil($id_perfil,$nome,$sigla);
                if($atualizado==true){
                    $funcoes_permitdas_deletadas = $this->perfisModel->excluirFuncoesPermitidasPerfil($id_perfil);
                    if($funcoes_permitdas_deletadas==true){
                        for($i = 0;$i<count($funcoes_liberadas);$i++){
                            $this->perfisModel->liberarFuncoesParaPerfil($id_perfil,$funcoes_liberadas[$i]);
                        }
                        $this->logAcaoUsuario("atualização de perfil - id $id_perfil", NULL, NULL, NULL, $id_perfil);
                        // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

                        $this->session->setFlashdata("success","<br />Perfil atualizado com sucesso!");
                        return redirect()->to("../perfis");
                    }else{
                        $this->session->setFlashdata("danger","<br />Não foi possível atualizar o perfil!");
                        return redirect()->to("../perfis");
                    }
                }else{
                    $this->session->setFlashdata("danger","<br />Não foi possível atualizar o perfil!");
                        return redirect()->to("../perfis");
                }
            }
        }
        
    }

    public function novoPerfil(){
        
        helper('form');

        $dados["funcoes_disponiveis_desenvolvidas"] = $this->perfisModel->retornaTodasFuncoes();
        
        $dados['pagina']            = 'gestao_perfis/adicionar_perfil.php';
        $dados['nome_pagina']       = 'Adicionar Perfil';
        $dados["link_pagina"]       = 'gestao_perfis/novoPerfil';
        $dados["diretorio_raiz"]    = '../';
        return view('templates/template_padrao.php',$dados); 
    }

    public function cadastrarPerfil(){

        $this->perfisModel = model('PerfisModel');
    
        $nome               = addslashes(trim((string)$this->request->getPost("nome")));
        $sigla              = addslashes(trim((string)$this->request->getPost("sigla")));
        $funcoes_liberadas  = $this->request->getPost("funcoes_liberadas");
        if(strlen($nome)<3){
            $this->session->setFlashdata("danger","<br />O nome deve conter pelo menos 3 caracteres!");
            $this->novoPerfil();
        }else if(strlen($sigla)<1){
            $this->session->setFlashdata("danger","<br />A sigla deve conter pelo menos 1 caractere!");
            $this->novoPerfil();
        }else{
            $sigla_verifica = $this->perfisModel->verificaSeJaExisteSigla($sigla);
            if(count($sigla_verifica)>0){
                $this->session->setFlashdata("danger","<br />Esta sigla já está sendo utilizada para outro tipo de perfil!");
                $this->novoPerfil();
            }else{
                $banco = \Config\Database::connect('hmdcc_app_dev', true);

                $dados_perfil["NOME_TIPO_PERFIL"]   = $nome;
                $dados_perfil["SIGLA_TIPO_PERFIL"]  = $sigla;
                $dados_perfil["ATIVO"]              = 1;
                $cadastrado = $this->perfisModel->cadastraPerfil($nome,$sigla);
                $cadastrado = $this->$banco->insert("CONFIG_USUARIO_TIPO_PERFIL",$dados_perfil);
                $id_perfil_cadastrado = $this->$banco->insert_id();
                if($cadastrado==true){
                    for($i = 0;$i<count($funcoes_liberadas);$i++){
                        $this->perfisModel->liberarFuncoesParaPerfil($id_perfil_cadastrado,$funcoes_liberadas[$i]);
                    }
                    $this->logAcaoUsuario("atualização de perfil - id $id_perfil_cadastrado", NULL, NULL, NULL, $id_perfil_cadastrado);
                    // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);
                    
                    $this->session->setFlashdata("success","<br />Perfil cadastrado com sucesso!");
                    return redirect()->to("../perfis");
                }
            }
        }
    }

}
