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

    public function PegarDispositivo(){
        $ip             =   $_SERVER['REMOTE_ADDR'];
        $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
        $os_platform    =   "Unknown OS Platform";

        $os_array       =   array(
                                '/windows nt 10/i'     =>  'Windows 10',
                                '/windows nt 6.3/i'     =>  'Windows 8.1',
                                '/windows nt 6.2/i'     =>  'Windows 8',
                                '/windows nt 6.1/i'     =>  'Windows 7',
                                '/windows nt 6.0/i'     =>  'Windows Vista',
                                '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                                '/windows nt 5.1/i'     =>  'Windows XP',
                                '/windows xp/i'         =>  'Windows XP',
                                '/windows nt 5.0/i'     =>  'Windows 2000',
                                '/windows me/i'         =>  'Windows ME',
                                '/win98/i'              =>  'Windows 98',
                                '/win95/i'              =>  'Windows 95',
                                '/win16/i'              =>  'Windows 3.11',
                                '/macintosh|mac os x/i' =>  'Mac OS X',
                                '/mac_powerpc/i'        =>  'Mac OS 9',
                                '/linux/i'              =>  'Linux',
                                '/ubuntu/i'             =>  'Ubuntu',
                                '/iphone/i'             =>  'iPhone',
                                '/ipod/i'               =>  'iPod',
                                '/ipad/i'               =>  'iPad',
                                '/android/i'            =>  'Android',
                                '/blackberry/i'         =>  'BlackBerry',
                                '/webos/i'              =>  'Mobile'
                                );

        foreach ($os_array as $regex => $value) {
                if (preg_match($regex, $user_agent)) {
                        $os_platform    =   $value;
                }
        }
        
        $browser        =   "Unknown Browser";
        $browser_array  =   array(
                                '/msie/i'       =>  'Internet Explorer',
                                '/firefox/i'    =>  'Firefox',
                                '/safari/i'     =>  'Safari',
                                '/chrome/i'     =>  'Chrome',
                                '/edge/i'       =>  'Edge',
                                '/opera/i'      =>  'Opera',
                                '/netscape/i'   =>  'Netscape',
                                '/maxthon/i'    =>  'Maxthon',
                                '/konqueror/i'  =>  'Konqueror',
                                '/mobile/i'     =>  'Handheld Browser'
                                );

        foreach ($browser_array as $regex => $value) { 
                if (preg_match($regex, $user_agent)) {
                $browser    =   $value;
                }
        }
        return "$os_platform - $browser - $ip";
    }

    public function logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL){
        $this->load->model("my_model");
        $data_hora          = date('Y-m-d H:i:s');
        $link               = $_SERVER["REQUEST_URI"];
        $info_dispositivo   = $this->PegarDispositivo();
        $usuario            = $this->session->userdata("usuario_logado");

        $this->my_model->logAcaoUsuario($usuario["ID"], $tipo, $nr_atendimento, $funcao, $parametro, $data_hora, $link, $info_dispositivo);
    }
}