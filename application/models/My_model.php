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

    public function retornaModulosPermitidosPerfil($id){
        return $this->db->query("SELECT DISTINCT
                                    ID_PERFIL,
                                    NOME_TIPO_PERFIL,
                                    ID_MODULO,
                                    NOME_MODULO
                                FROM 
                                    CONFIG_PERFIL_FUNCAO C
                                    JOIN CONFIG_FUNCAO F ON (C.ID_FUNCAO=F.ID)
                                    JOIN CONFIG_MODULO M ON (F.ID_MODULO=M.ID)
                                    JOIN CONFIG_USUARIO_TIPO_PERFIL P ON (C.ID_PERFIL=P.ID)
                                    JOIN USERS U ON (U.TIPO_PERFIL=P.SIGLA_TIPO_PERFIL)
                                WHERE 
                                    U.ID=$id
                                ORDER BY 
                                    M.ORDEM ASC, ID_FUNCAO ASC")->result_array();
    }

    public function retornaMenuPermitidoPerfil($id){
        return $this->db->query("SELECT 
                                    ID_PERFIL,
                                    NOME_TIPO_PERFIL,
                                    ID_MODULO,
                                    NOME_MODULO,
									ID_FUNCAO,
                                    NOME_FUNCAO,
                                    LINK_INICIAL_FUNCAO,
									ICONE_FONT_AWESOME
                                FROM 
                                    CONFIG_PERFIL_FUNCAO C
                                    JOIN CONFIG_FUNCAO F ON (C.ID_FUNCAO=F.ID)
                                    JOIN CONFIG_MODULO M ON (F.ID_MODULO=M.ID)
                                    JOIN CONFIG_USUARIO_TIPO_PERFIL P ON (C.ID_PERFIL=P.ID)
                                    JOIN USERS U ON (U.TIPO_PERFIL=P.SIGLA_TIPO_PERFIL)
                                WHERE 
                                    U.ID=$id
                                ORDER BY 
                                    M.ORDEM ASC, ID_FUNCAO ASC")->result_array();
    }

    public function retornaSeFuncaoPermitida($id,$funcao){
        return $this->db->query("SELECT 
                                    ID_PERFIL,
                                    NOME_TIPO_PERFIL,
                                    ID_MODULO,
                                    NOME_MODULO,
									ID_FUNCAO,
                                    NOME_FUNCAO,
                                    LINK_INICIAL_FUNCAO,
									ICONE_FONT_AWESOME
                                FROM 
                                    CONFIG_PERFIL_FUNCAO C
                                    JOIN CONFIG_FUNCAO F ON (C.ID_FUNCAO=F.ID)
                                    JOIN CONFIG_MODULO M ON (F.ID_MODULO=M.ID)
                                    JOIN CONFIG_USUARIO_TIPO_PERFIL P ON (C.ID_PERFIL=P.ID)
                                    JOIN USERS U ON (U.TIPO_PERFIL=P.SIGLA_TIPO_PERFIL)
                                WHERE 
                                    U.ID=$id
                                    AND LINK_INICIAL_FUNCAO='$funcao'
                                ORDER BY 
                                    M.ORDEM ASC, ID_FUNCAO ASC")->row_array();
    }
}
?>
