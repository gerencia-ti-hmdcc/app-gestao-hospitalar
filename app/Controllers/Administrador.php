<?php
namespace App\Controllers;

use App\Models\AdministradorModel;
use App\Models\MyModel;

class Administrador extends BaseController{

    protected $administradorModel;
    protected $myModel;

    public function __construct(){

        $this->administradorModel = new AdministradorModel();
        $this->myModel = new MyModel();
        
    }
    
	public function index()
	{
    $redirect = $this->verificaPerfilUsuario('administrador');
       if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
           return $redirect;
       }

        helper('form');
        $dados['pagina']            = 'administrador/gerenciar_usuarios.php';
        $dados['nome_pagina']       = 'Gerenciar Usuários';
        $dados["link_pagina"]       = 'administrador';
        // $dados["diretorio_raiz"]    = '../';
        return view('templates/template_padrao.php',$dados); 
	}


    public function retornaUsuarios(){
    
        $usuarios = $this->administradorModel->retornaUsuarios();
        $i = 0;
        for($i = 0; $i<count($usuarios);$i++){
            if($usuarios[$i]["ULTIMO_LOGIN"]!=null){
                $usuarios[$i]["ULTIMO_LOGIN"] = date("d/m/Y H:i", strtotime($usuarios[$i]["ULTIMO_LOGIN"]));
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

    public function editarUsuario($id=0){

        if($id==0){
            $id = $this->request->getPost("usuario_escolhido");
        }else{
            $id = $id;
        }

        if($id>0){
            helper('form');
            $dados["usuario"]           = $this->administradorModel->retornaUsuario($id);
            $dados["usuario"]["TIPO_PERFIL"] = $dados["usuario"]["NOME_TIPO_PERFIL"];

            if($dados["usuario"]["IE_STATUS"]=='A'){
                $dados["usuario"]["IE_STATUS"] = "Ativo";
            }else if($dados["usuario"]["IE_STATUS"]=='I'){
                $dados["usuario"]["IE_STATUS"] = "Inativo";
            }

            $dados["tipos_perfil"]      = $this->administradorModel->retornaTodosTiposPerfis();
            
            $dados["status_possiveis"]  = $this->administradorModel->retornaTodosStatus();
            
            $dados["id_usuario"]        = $id;
            $dados['pagina']            = 'administrador/editar_usuario.php';
            $dados['nome_pagina']       = 'Editar Usuário';
            $dados["link_pagina"]       = 'administrador/editarUsuario';
            $dados["diretorio_raiz"]    = '../';
            return view("templates/template_padrao.php",$dados);
        }else{
            return redirect()->to("administrador");
        }
    }

    public function atualizarUsuario(){
    
        $nome           = addslashes(trim((string)$this->request->getPost("nome")));
        $usuario_ad     = addslashes(trim((string)$this->request->getPost("usuario_ad")));
        $email          = addslashes(trim((string)$this->request->getPost("email")));
        $status         = addslashes((string)$this->request->getPost("status"));
        $tipo_perfil    = addslashes((string)$this->request->getPost("tipo_perfil"));
        $id             = (int) $this->request->getPost("id_usuario");

        if(strlen($nome)<3){
            $this->session->setFlashdata("danger","<br />O nome deve conter pelo menos 3 caracteres!");
            $this->editarUsuario($id);
        }else if(strlen($email)<3){
            $this->session->setFlashdata("danger","<br />E-mail inválido!");
            $this->editarUsuario($id);         
        }else if(strlen($status)<1 OR strlen($status)>1){
            $this->session->setFlashdata("danger","<br />Status inválido!");
            $this->editarUsuario($id);       
        }else if(strlen($tipo_perfil)<1){
            $this->session->setFlashdata("danger","<br />Perfil inválido!");
            $this->editarUsuario($id);   
        }else{
            $email_verifica = $this->administradorModel->existeEmailUsuario($email,$id);
            if(isset($email_verifica["EMAIL"])){
                $this->session->setFlashdata("danger","<br />Este e-mail já está cadastrado no sistema!");
                $this->editarUsuario($id);
            }else{

                if(!isset($usuario_ad)){
                    $usuario_ad = '';
                }

                $atualizado = $this->administradorModel->atualizaUsuario($id,$nome,$email,$status,$tipo_perfil,$usuario_ad);
                
                if($atualizado==true){
                    $this->logAcaoUsuario("atualização de usuário - id_usuario $id", NULL, NULL, $id);
                    // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);

                    if($this->request->getPost("resetar_senha")){
                        $this->administradorModel->resetaSenha($id);
                    }
                    $this->session->setFlashdata("success","<br />Usuário atualizado com sucesso!");
                    return redirect()->to("administrador");
                }
            }
        }
        
    }

    public function novoUsuario(){
        helper('form');

        $dados["tipos_perfil"]      = $this->administradorModel->retornaTodosTiposPerfis();
        
        $dados["status_possiveis"]  = $this->administradorModel->retornaTodosStatus();
        
        $dados['pagina']            = 'administrador/adicionar_usuario.php';
        $dados['nome_pagina']       = 'Adicionar Usuário';
        $dados["link_pagina"]       = 'administrador/novoUsuario';
        $dados["diretorio_raiz"]    = '../';
        return view('templates/template_padrao.php',$dados); 
    }

    public function cadastrarUsuario(){
        $nome           = addslashes(trim((string)$this->request->getPost("nome")));
        $usuario_ad         = addslashes((string)$this->request->getPost("usuario_ad"));
        $email          = addslashes(trim((string)$this->request->getPost("email")));
        $status         = addslashes((string)$this->request->getPost("status"));
        $tipo_perfil    = addslashes((string)$this->request->getPost("tipo_perfil"));

        if(strlen($nome)<3){
            $this->session->setFlashdata("danger","<br />O nome deve conter pelo menos 3 caracteres!");
            $this->novoUsuario();
        }else if(strlen($email)<3){
            $this->session->setFlashdata("danger","<br />E-mail inválido!");
            $this->novoUsuario();
        }else if(strlen($status)<1 OR strlen($status)>1){
            $this->session->setFlashdata("danger","<br />Status inválido!");
            $this->novoUsuario();
        }else if(strlen($tipo_perfil)<1){
            $this->session->setFlashdata("danger","<br />Perfil inválido!");
            $this->novoUsuario();
        }else{
            $email_verifica = $this->administradorModel->existeEmailUsuario($email);
            if(isset($email_verifica["EMAIL"])){
                $this->session->setFlashdata("danger","<br />Este e-mail já existe no sistema!");
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

                if(!isset($usuario_ad)){
                    $usuario_ad = '';
                }
        
                $cadastrado = $this->administradorModel->cadastraUsuario($nome,$email,$status,$tipo_perfil,$usuario_ad);
              
                if($cadastrado==true){
                    $this->logAcaoUsuario("cadastro de usuário - email $email", NULL, NULL, $email);
                    // $this->logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL);
                    
                    $this->session->setFlashdata("success","<br />Usuário cadastrado com sucesso!");
                    return redirect()->to("administrador");
                }
            }
        }
    }
}
