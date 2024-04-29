<?php 

class MY_Controller extends CI_Controller {

 	public function __construct(){

        parent::__construct();
        
        $usuario = $this->session->userdata("usuario_logado");
        //unset($usuario);
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );

        $this->session->set_tempdata('usuario_logado', $usuario, 60*60*2); //SETANDO 2 HORAS DE SESSAO DE NAVEGADOR A CADA ATUALIZAÇÃO

        //VERIFICA SE EXISTE USUARIO LOGADO NA SESSÃO DO NAVEGADOR
        if (!$usuario){
            $this->session->set_flashdata("warning","<br />Token expirado. Efetue login novamente!");
            if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
                // header("Location: https://app.hmdcc.com.br");
                redirect("../");
            }else{
                redirect("../app");
            }
            //redirect('login');				
        }else{
            if($usuario["TIPO_PERFIL"]!="P"){
                //VERIFICA SE O TOKEN DO BANCO É VALIDO (IMPOSSIBILITA UM USUARIO LOGAR EM DOIS DISPOSITIVOS SIMULTANEAMENTE)
                $this->load->model("my_model");
                $us_token = $this->my_model->verificaToken($usuario['TOKEN']);
                if(!(isset($us_token['ID']) && $us_token['ID']>0)){ 
                    $this->session->set_flashdata("warning","<br />Token expirado. Efetue login novamente!");
                    $this->session->unset_userdata("usuario_logado");
                    if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
                        // header("Location: https://app.hmdcc.com.br");
                        redirect("../");
                    }else{
                        redirect("../app");
                    }
                }
            }
        }
	}
}