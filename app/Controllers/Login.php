<?php
namespace App\Controllers;
use App\Models\MyModel;
use App\Models\LoginModel;
use CodeIgniter\I18n\Time;

class Login extends BaseController{

        protected $myModel;
        protected $loginModel;

        public function __construct(){

                $this->myModel = new MyModel();
                $this->loginModel = new LoginModel();
                /*if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
        		$url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        		redirect($url);
        		exit;
    		}*/
        }

        public function logAcaoUsuario($tipo, $nr_atendimento=NULL, $funcao=NULL, $parametro=NULL){
               
                $data_hora          = date('Y-m-d H:i:s');
                $link               = $_SERVER["REQUEST_URI"];
                $info_dispositivo   = $this->PegarDispositivo();
                $usuario            = $this->session->get("usuario_logado");

                $this->myModel->logAcaoUsuario($usuario["ID"], $tipo, $nr_atendimento, $funcao, $parametro, $data_hora, $link, $info_dispositivo);
        }

	public function index()
	{
                //$this->load->model('login_model');
                //$this->load->view('templates/template_login.php');
                if($this->session->get("usuario_logado")){
                        //LOGANDO PAINEL DE ACORDO COM SUA ESPECIFICIDADE  
                        if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=='P'){
                                // $this->load->model("my_model");
                                $painel_acesso = $this->myModel->retornaLinkPainel($_SESSION["usuario_logado"]["ID"]);
                                return redirect()->to($painel_acesso["LINK_PAINEL"]);
                        }else{
                                return redirect()->to('dashboard');
                        }
                }else{
                        helper('form');
                        return view('login/index.php');   
                }
	}

        public function autenticar(){
                $dt = new Time();
                $dt-> setTimezone('America/Fortaleza');
                $data_atual = $dt->format("Y-m-d H:i:s");

                $token          = md5(uniqid()."".date("YmdHis")."@".uniqid());
 		$validade       = date("Y-m-d H:i:s",strtotime("+5 days")); 

                $email          = $this->request->getPost("email");
                $senha          = $this->request->getPost("senha");
                $senha          = md5((string)$senha);
                
                $usuario = $this->loginModel->logar($email,$senha);
                unset($usuario['SENHA']);
                if($usuario && isset($usuario)){
                        $this->session->remove("usuario_logado");
                        if($senha=='e10adc3949ba59abbe56e057f20f883e'){
                                $this->session->setFlashdata("warning","<br />Primeiro acesso. Por favor, atualize sua senha!");

                                helper('form');
                                $dados["email"] = $email;
                                return view('login/primeiro_acesso.php',$dados);
                        }else{
                                if($usuario["IE_STATUS"]=='A'){
                        
                                        //VENCIMENTO DE SESSAO NAVEGADOR
                                        if($usuario["TIPO_PERFIL"]=="P"){
                                                $this->session->sess_expiration = 0;   
                                                $this->session->set("usuario_logado",$usuario);
                                        }else{
                                                $this->session->sess_expiration = 60*60*2;  
                                                $this->session->setTempdata('usuario_logado', $usuario, 60*60*2); // 2 HORAS
                                        }
                                        $_SESSION['usuario_logado']['TOKEN'] = $token;
                                        $this->loginModel->atualizaToken($usuario["ID"],$data_atual,$token,$validade);
                                        $this->loginModel->atualizaDisp($this->PegarDispositivo(),$usuario["ID"]);

                                        $this->logAcaoUsuario("login");

                                        if($_SESSION["usuario_logado"]["TIPO_PERFIL"]=='P'){
                                                $painel_acesso = $this->myModel->retornaLinkPainel($_SESSION["usuario_logado"]["ID"]);
                                                return redirect()->to('../'.$painel_acesso["LINK_PAINEL"]);
                                        }else{
                                                //DEFININDO MENU DO USUARIO COM BASE EM TABELA GESTÃO DE ACESSOS
                                                $menu_usuario_perfil    = $this->myModel->retornaModulosPermitidosPerfil($usuario["ID"]);
                                                $funcoes_usuario_perfil = $this->myModel->retornaMenuPermitidoPerfil($usuario["ID"]);
                                                for($i=0;$i<count($menu_usuario_perfil);$i++){
                                                        $menu_usuario_perfil[$i]["funcoes_permitidas"] = [];
                                                        for($j=0;$j<count($funcoes_usuario_perfil);$j++){
                                                                if($menu_usuario_perfil[$i]["ID_MODULO"]==$funcoes_usuario_perfil[$j]["ID_MODULO"]){
                                                                        array_push($menu_usuario_perfil[$i]["funcoes_permitidas"],$funcoes_usuario_perfil[$j]);
                                                                }
                                                        }
                                                }
                                                $_SESSION["usuario_logado"]["menu_permitido_usuario_perfil"] = $menu_usuario_perfil;
                                                return redirect()->to('dashboard');
                                        }
                                }else{
                                        $this->session->setFlashdata("danger","Permissão negada. Comunique a TI!");
                                        helper('form');
                                        return redirect()->route('login');
                                }
                        }
                }else{
                        $this->session->setFlashdata("danger","Usuário ou senha inválido(s)!");
                        helper('form');
                        return redirect()->route('login');
                }
        }

        public function autenticarPrimeiroAcesso(){
                // $this->load->model("login_model");
                if($this->request->getPost()){
                        $nova_senha1    = $this->request->getPost('nova_senha1');
                        $nova_senha2    = $this->request->getPost('nova_senha2');
                        $email          = $this->request->getPost("email");
                        if(strlen((string)$nova_senha1)>=6 OR strlen((string)$nova_senha2)>=6){
                                $nova_senha1 = md5((string)$nova_senha1);
                                $nova_senha2 = md5((string)$nova_senha2);
                                if($nova_senha1==$nova_senha2){
                                        if($nova_senha1=='e10adc3949ba59abbe56e057f20f883e'){
                                                $this->session->setFlashdata("danger","<br />A senha utilizada não pode ser '123456'");
                                                helper('form');
                                                $dados["email"] = $email;
                                                return view('login/primeiro_acesso.php',$dados);
                                        }else{
                                                $c_primeiro_acesso = $this->loginModel->cadastrarPrimeiroAcesso($email,$nova_senha1);
                                                if($c_primeiro_acesso==true){
                                                        $this->session->setFlashdata("success","Nova senha cadastrada com sucesso.<br />Efetue Login!");
                                                }else{
                                                        $this->session->setFlashdata("danger","<br />Há um erro em seu cadastro. Por favor, comunique ao TI.");
                                                }
                                                return redirect()->to('../');
                                        }
                                }else{  
                                        $this->session->setFlashdata("danger","As senhas não coincidem!");
                                        helper('form');
                                        $dados["email"] = $email;
                                        return view('login/primeiro_acesso.php',$dados);
                                }
                        }else{
                                $this->session->setFlashdata("danger","<br />A senha deve conter pelo menos 6 caracteres!");
                                helper('form');
                                $dados["email"] = $email;
                                return view('login/primeiro_acesso.php',$dados);
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

        public function logout(){
                $this->session->remove("usuario_logado");
                $this->session->destroy();
                return redirect()->route('login');
        }
}
