<?php

namespace App\Models;
use CodeIgniter\Model;

class PerfisModel extends Model {
    
    public function retornaTiposPerfis(){
        return $this->db->query("SELECT 
                                    *
                                FROM 
                                    CONFIG_USUARIO_TIPO_PERFIL
                                ORDER BY
                                    ID ASC")->getResultArray();
    }

    public function retornaTodasFuncoes(){
        return $this->db->query("SELECT 
                                    F.ID_MODULO,
                                    M.NOME_MODULO,
                                    F.ID,
                                    F.NOME_FUNCAO
                                FROM 
                                    CONFIG_FUNCAO F
                                    JOIN CONFIG_MODULO M ON (M.id=F.ID_MODULO) 
                                ORDER BY
                                    ID_MODULO ASC, F.ID ASC")->getResultArray();
    }

    public function verificaSeJaExisteSigla($sigla){
        return $this->db->query("SELECT 
                                    *
                                FROM
                                    CONFIG_USUARIO_TIPO_PERFIL
                                WHERE
                                    SIGLA_TIPO_PERFIL='$sigla'")->getResultArray();
    }


    public function cadastraPerfil($nome,$sigla){
        return $this->db->query("INSERT INTO
                                    CONFIG_USUARIO_TIPO_PERFIL(SIGLA_TIPO_PERFIL,NOME_TIPO_PERFIL,ATIVO)
                                VALUES('$sigla','$nome',1)");
    }
    
    public function liberarFuncoesParaPerfil($id_perfil,$id_funcao){
        return $this->db->query("INSERT INTO
                                    CONFIG_PERFIL_FUNCAO(ID_PERFIL,ID_FUNCAO)
                                VALUES($id_perfil,$id_funcao)");
    }

    public function funcoesLiberasParaPerfil($id_perfil){
        return $this->db->query("SELECT 
                                    F.ID_MODULO,
                                    M.NOME_MODULO,
                                    F.ID,
                                    F.NOME_FUNCAO,
                                    CASE
                                        WHEN
                                            (SELECT
                                                1
                                                FROM
                                                CONFIG_PERFIL_FUNCAO C
                                            WHERE 
                                                    C.ID_PERFIL=$id_perfil
                                                and C.ID_FUNCAO =F.ID
                                            )=1 THEN 1
                                        ELSE 0
                                    END FUNCAO_LIBERADA
                                FROM 
                                    CONFIG_FUNCAO F
                                    JOIN CONFIG_MODULO M ON (M.id=F.ID_MODULO) 
                                ORDER BY
                                    ID_MODULO ASC, F.ID ASC")->getResultArray();
    }

    public function retornaDadosPerfil($id_perfil){
        return $this->db->query("SELECT
                                    *
                                FROM
                                    CONFIG_USUARIO_TIPO_PERFIL
                                WHERE
                                    ID=$id_perfil")->getRowArray();
    }

    public function atualizaDadosPerfil($id_perfil,$nome,$sigla){
        return $this->db->query("UPDATE
                                    CONFIG_USUARIO_TIPO_PERFIL
                                SET
                                    NOME_TIPO_PERFIL='$nome',
                                    SIGLA_TIPO_PERFIL='$sigla'
                                WHERE
                                    ID=$id_perfil");
    }

    public function excluirFuncoesPermitidasPerfil($id_perfil){
        return $this->db->query("DELETE  FROM CONFIG_PERFIL_FUNCAO WHERE ID_PERFIL=$id_perfil");
    }

    public function verificaSeJaExisteSiglaAtualizaPerfil($sigla,$id_perfil){
        return $this->db->query("SELECT 
                                    *
                                FROM
                                    CONFIG_USUARIO_TIPO_PERFIL
                                WHERE
                                    SIGLA_TIPO_PERFIL='$sigla'
                                    AND ID <> $id_perfil")->getResultArray();
    }
   
}
?>
