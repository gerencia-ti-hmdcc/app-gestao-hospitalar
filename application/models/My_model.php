<?php
class My_model extends CI_Model {
    public function verificaToken($token){
        $usuario = $this->db->query("SELECT ID FROM USERS WHERE TOKEN='$token' LIMIT 1")->row_array();
        return $usuario;
    }
}
?>
