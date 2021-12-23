<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

        public function __construct(){
                parent::__construct();
                /*if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
        		$url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        		redirect($url);
        		exit;
    		}*/
        }

	public function index()
	{
                //$this->load->model('login_model');
                //$this->load->view('templates/template_login.php');
                if($this->session->userdata("usuario_logado")){
                        redirect('dashboard');
                }else{
                        $this->load->helper('form');
                        $this->load->view('login/index.php');   
                }
	}

        public function autenticar(){
                //exit("TESTE");
                $this->load->model("login_model");

                $data_atual     = date("Y-m-d H:i:s");
                $token          = md5(uniqid()."".date("YmdHis")."@".uniqid());
 		$validade       = date("Y-m-d H:i:s",strtotime("+5 days")); 

                $email          = $this->input->post("email");
                $senha          = $this->input->post("senha");
                $senha          = md5($senha);
                
                $usuario = $this->login_model->logar($email,$senha);
                unset($usuario['SENHA']);
                if($usuario && isset($usuario)){
                        $this->session->unset_userdata("usuario_logado");
                        if($senha=='e10adc3949ba59abbe56e057f20f883e'){
                                $this->session->set_flashdata("warning","<br />Primeiro acesso. Por favor, atualize sua senha!");
                                //redirect("/primeiroAcesso");
                                //$this->primeiroAcesso($email);
                                $this->load->helper('form');
                                $dados["email"] = $email;
                                $this->load->view('login/primeiro_acesso.php',$dados);
                        }else{
                                //exit(print_r($usuario));
                                $this->session->set_userdata("usuario_logado",$usuario);
                                $_SESSION['usuario_logado']['TOKEN'] = $token;
                                $this->login_model->atualizaToken($usuario["ID"],$data_atual,$token,$validade);
                                $this->login_model->atualizaDisp($this->PegarDispositivo(),$usuario["ID"]);
                                //$this->session->set_flashdata("success","Usuário logado!");
                                redirect('../dashboard');
                        }
                }else{
                        $this->session->set_flashdata("danger","Usuário ou senha inválido(s)!");
                        $this->load->helper('form');
                        redirect('../');
                }
        }

        public function autenticarPrimeiroAcesso(){
                $this->load->model("login_model");
                if($this->input->post()){
                        $nova_senha1    = $this->input->post('nova_senha1');
                        $nova_senha2    = $this->input->post('nova_senha2');
                        $email          = $this->input->post("email");
                        if(strlen($nova_senha1)>=6 OR strlen($nova_senha2)>=6){
                                $nova_senha1 = md5($nova_senha1);
                                $nova_senha2 = md5($nova_senha2);
                                if($nova_senha1==$nova_senha2){
                                        if($nova_senha1=='e10adc3949ba59abbe56e057f20f883e'){
                                                $this->session->set_flashdata("danger","<br />A senha utilizada não pode ser '123456'");
                                                $this->load->helper('form');
                                                $dados["email"] = $email;
                                                $this->load->view('login/primeiro_acesso.php',$dados);
                                        }else{
                                                $c_primeiro_acesso = $this->login_model->cadastrarPrimeiroAcesso($email,$nova_senha1);
                                                if($c_primeiro_acesso==true){
                                                        $this->session->set_flashdata("success","Nova senha cadastrada com sucesso.<br />Efetue Login!");
                                                }else{
                                                        $this->session->set_flashdata("danger","<br />Há um erro em seu cadastro. Por favor, comunique ao TI.");
                                                }
                                                redirect('../');
                                        }
                                }else{  
                                        $this->session->set_flashdata("danger","As senhas não coincidem!");
                                        $this->load->helper('form');
                                        $dados["email"] = $email;
                                        $this->load->view('login/primeiro_acesso.php',$dados);
                                }
                        }else{
                                $this->session->set_flashdata("danger","<br />A senha deve conter pelo menos 6 caracteres!");
                                $this->load->helper('form');
                                $dados["email"] = $email;
                                $this->load->view('login/primeiro_acesso.php',$dados);
                        }
                }
        }

        public function PegarDispositivo(){
                $ip             =   $_SERVER["SERVER_ADDR"];
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

        public function logout(){
                $this->session->unset_userdata("usuario_logado");
                redirect('../');
        }
}
