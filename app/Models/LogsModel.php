<?php
namespace App\Models;
use CodeIgniter\Model;

class LogsModel extends Model {

    public function retornaLogUsuarioPeriodo($id_usuario,$data1,$data2){
        return $this->db->query("SELECT 
                                    *
                                FROM 
                                    LOG_ACESSO L
                                    INNER JOIN USERS U ON (U.id=L.ID_USUARIO)
                                WHERE
                                    ID_USUARIO = $id_usuario
                                    AND DATA_ACESSO BETWEEN '$data1' AND '$data2'")->getResultArray();
    }

    public function retornaDadosBasicosUsuario($id_usuario){
        return $this->db->query("SELECT 
                                    U.*,
                                    P.NOME_TIPO_PERFIL
                                FROM 
                                    USERS U 
                                    INNER JOIN CONFIG_USUARIO_TIPO_PERFIL P ON (P.SIGLA_TIPO_PERFIL=U.TIPO_PERFIL)
                                WHERE
                                    U.ID = $id_usuario")->getRowArray();
    }

}
?>