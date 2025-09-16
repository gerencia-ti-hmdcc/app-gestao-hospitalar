<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MyModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    /**
     * @return void
     */

     protected $myModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();
        
        $this->myModel = new MyModel();
    }


    function verificaPerfilUsuario($nome_controller){

        $this->session = \Config\Services::session();

        $usuario = $this->session->get("usuario_logado");

        if(!$usuario){
            return redirect()->to('login/logout');
        }else{
            //REDIRECIONA USUÁRIO QUE TENTA ACESSAR O MÓDULO QUE NÃO É PERMITIDO PARA ESSE USUÁRIO

            $funcao_permitida = $this->myModel->retornaSeFuncaoPermitida($usuario["ID"], $nome_controller);

            if(!count((array)$funcao_permitida)>0){
                // exit('teste');
                return redirect()->to('dashboard');
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

        $data_hora          = date('Y-m-d H:i:s');
        $link               = $_SERVER["REQUEST_URI"];
        $info_dispositivo   = $this->PegarDispositivo();
        $usuario            = $this->session->get("usuario_logado");

        $this->myModel->logAcaoUsuario($usuario["ID"], $tipo, $nr_atendimento, $funcao, $parametro, $data_hora, $link, $info_dispositivo);
    }
}
