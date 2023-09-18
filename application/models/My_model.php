<?php
class My_model extends CI_Model {
    public function verificaToken($token){
        $usuario = $this->db->query("SELECT ID FROM USERS WHERE TOKEN='$token' LIMIT 1")->row_array();
        return $usuario;
    }

    public function tipoPerfilUsuario($id){
        return $this->db->query("SELECT TIPO_PERFIL FROM USERS WHERE ID=$id")->row_array();
    }

    public function retornaLinkPainel($id){
        return $this->db->query("SELECT * FROM CONFIG_PAINEL_LINK WHERE ID_USUARIO=$id")->row_array();
    }
}
?>
