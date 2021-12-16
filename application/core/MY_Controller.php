<?php 

class MY_Controller extends CI_Controller {

 	public function __construct()
       {
            parent::__construct();
			
			$usuario = $this->session->userdata("usuario_logado");
			
			if (!$usuario){
                redirect('login');				
            } 
		}
}