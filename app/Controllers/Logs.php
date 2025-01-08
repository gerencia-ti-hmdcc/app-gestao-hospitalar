<?php
namespace App\Controllers;

use App\Models\AdministradorModel;
use App\Models\LogsModel;

class Logs extends BaseController {

    protected $administradorModel;
    protected $logsModel;

    public function __construct(){

        $this->administradorModel = new AdministradorModel();
        $this->logsModel = new LogsModel();

    }

	public function index()
	{

        $redirect = $this->verificaPerfilUsuario('logs');
        if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $redirect;
        }

        helper('form');
        $dados['pagina']            = 'logs/logs_usuarios.php';
        $dados['nome_pagina']       = 'Logs de Usuários';
        $dados["link_pagina"]       = 'logs';
        // $dados["diretorio_raiz"]    = '../';
        return view('templates/template_padrao.php',$dados); 
	}

    public function retornaUsuarios(){
       
        $usuarios = $this->administradorModel->retornaUsuarios();
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
            $id_usuario = $this->request->getPost("usuario_escolhido");
        }else{
            $id_usuario = $id_usuario;
        }
        if($id_usuario!=0){
            
            $dados["dados_usuario"]                     =  $this->logsModel->retornaDadosBasicosUsuario($id_usuario);
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
            return view("templates/template_padrao.php",$dados);
        }else{
            return redirect()->to("../logs");
        }
    }

    public function retornaLogUsuarioPeriodo(){
        $id_usuario = $this->request->getPost("id_usuario");
        $data1      = $this->request->getPost("data1");
        $data2      = $this->request->getPost("data2");

        if(strlen((string)$id_usuario)!=0){
          
            $logs = $this->logsModel->retornaLogUsuarioPeriodo($id_usuario,$data1,$data2);
        }else{
            $logs["ERRO"] = "Erro!";
        }
        print json_encode($logs);
    }

}