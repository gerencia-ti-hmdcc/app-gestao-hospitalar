<?php

namespace App\Models;
use CodeIgniter\Model;

class MyModel extends Model {
    public function verificaToken($token){
        $usuario = $this->db->query("SELECT ID FROM USERS WHERE TOKEN='$token' LIMIT 1")->getRowArray();
        return $usuario;
    }

    public function tipoPerfilUsuario($id){
        return $this->db->query("SELECT TIPO_PERFIL FROM USERS WHERE ID=$id")->getRowArray();
    }

    public function retornaLinkPainel($id){
        return $this->db->query("SELECT * FROM CONFIG_PAINEL_LINK WHERE ID_USUARIO=$id")->getRowArray();
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
                                    M.ORDEM ASC, ID_FUNCAO ASC")->getResultArray();
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
                                    M.ORDEM ASC, ID_FUNCAO ASC")->getResultArray();
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
                                    M.ORDEM ASC, ID_FUNCAO ASC")->getRowArray();
    }

    public function logAcaoUsuario($usuario, $tipo, $nr_atendimento, $funcao, $parametro, $data_hora, $link, $info_dispositivo){

        if($nr_atendimento!=NULL){
            $coluna_atendimento = "NR_ATENDIMENTO, ";
            $valor_atendimento  = $nr_atendimento.",";
        }else{
            $coluna_atendimento = ""; 
            $valor_atendimento  = "";
        }

        if($funcao!=NULL){
            $coluna_funcao = "FUNCAO_ACESSO, ";
            $valor_funcao  = "'".$funcao."',";
        }else{
            $coluna_funcao = "";
            $valor_funcao  = "";
        }

        if($parametro!=NULL){
            $coluna_parametro = "PARAMETRO_ADMIN, ";
            $valor_parametro  = "'".$parametro."',";
        }else{
            $coluna_parametro = "";
            $valor_parametro  = "";
        }
        return $this->db->query("INSERT INTO
                                    LOG_ACESSO(ID_USUARIO, LINK_ACESSO, TIPO_LOG, $coluna_atendimento $coluna_funcao $coluna_parametro DATA_ACESSO, INFO_DISPOSITIVO)
                                VALUES
                                    ($usuario, '$link','$tipo', $valor_atendimento $valor_funcao $valor_parametro '$data_hora', '$info_dispositivo')");
    }
}
?>
