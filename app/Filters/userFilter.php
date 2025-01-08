<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MyModel;

class userFilter implements FilterInterface
{
    protected $myModel;

    public function before(RequestInterface $request, $arguments = null)
    {
        $this->myModel = new MyModel();
        $session = \Config\Services::session();
        $usuario = $session->get("usuario_logado");
        
        if (!$usuario){
            $session->setFlashdata("warning","<br />Token expirado. Efetue login novamente!");
            return redirect()->route('login');
           				
        }else{
            if($usuario["TIPO_PERFIL"]!="P"){
                //VERIFICA SE O TOKEN DO BANCO É VALIDO (IMPOSSIBILITA UM USUARIO LOGAR EM DOIS DISPOSITIVOS SIMULTANEAMENTE)
                $this->myModel = model("MyModel");
                $us_token = $this->myModel->verificaToken($usuario['TOKEN']);
                if(!(isset($us_token['ID']) && $us_token['ID']>0)){ 
                    $session->setFlashdata("warning","<br />Token expirado. Efetue login novamente!");
                    $session->remove("usuario_logado");
                    return redirect()->to('login');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}