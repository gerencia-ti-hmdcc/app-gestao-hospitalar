<?php

namespace App\Models;
use CodeIgniter\Model;

class DashboardModel extends Model {
    // public function listaPacientes(){
    //     return $this->db->get('atendimento_paciente')->result_array();
    // }

    // public function listaAtendimentos(){
    //     // return $this->db->query("SELECT 
    //     //                             CONCAT(SA.cd_setor_atendimento,' - ', SA.ds_setor_atendimento) as setor,AP.NR_ATENDIMENTO as atendimento,AP.CD_PESSOA_FISICA as paciente 
    //     //                         FROM 
    //     //                             db_dev.atendimento_paciente AP 
    //     //                             INNER JOIN setor_atendimento SA ON (AP.CD_SETOR_USUARIO_ATEND=SA.cd_setor_atendimento);")->result_array();
        
    //     return $this->db->query("SELECT 
    //                                 CONCAT(SA.cd_setor_atendimento,'-', SA.ds_setor_atendimento) as setor, AP.NR_ATENDIMENTO as atendimento,AP.CD_PESSOA_FISICA as paciente 
    //                             FROM 
    //                                 db_dev.atendimento_paciente AP 
    //                                 INNER JOIN prescr_medica PME ON (PME.NR_ATENDIMENTO=AP.NR_ATENDIMENTO)
    //                                 INNER JOIN setor_atendimento SA ON (SA.cd_setor_atendimento=PME.CD_SETOR_ATENDIMENTO);")->result_array();
    // }

    public function percentuaisGeraisOcupacao(){
        return $this->db->query("SELECT * FROM OCUPACAO_TOTAL WHERE date_format(dt_atualizacao, '%Y-%m-%d %H:%i') = (SELECT MAX(date_format(dt_atualizacao, '%Y-%m-%d %H:%i')) FROM OCUPACAO_TOTAL) ORDER BY NR_UNIDADES_SETOR DESC, CD_CLASSIF_SETOR DESC")->getResultArray();
    }

    public function percentuaisSetorOcupacao($id_area){
        if($id_area==3 OR $id_area==99 OR $id_area==4){
            $condicao = "AND CD_CLASSIF_SETOR in ($id_area) AND CD_SETOR_ATENDIMENTO not in (129,83,145)";
        }else{
            $condicao = "AND CD_SETOR_ATENDIMENTO in ($id_area)";
        }
        //return $this->db->query("SELECT * FROM `OCUPACAO_SETOR` WHERE DT_ATUALIZACAO >= DATE_SUB(NOW(), INTERVAL 1 HOUR) AND CD_CLASSIF_SETOR = $id_area")->result_array();
        return $this->db->query("SELECT * FROM `OCUPACAO_SETOR` WHERE date_format(dt_atualizacao, '%Y-%m-%d %H:%i') = (SELECT MAX(date_format(dt_atualizacao, '%Y-%m-%d %H:%i')) FROM `OCUPACAO_SETOR`) $condicao")->getResultArray();
    }

    public function retornaSetorLoopPainel(){
        return $this->db->query("SELECT * FROM CONFIG_PAINEL_OCUPACAO WHERE ATIVO=1 ORDER BY ID ASC")->getResultArray();
    }

    public function retornaUltimoSetorGeralAtivo(){
        return $this->db->query("SELECT * FROM CONFIG_PAINEL_OCUPACAO WHERE ATIVO=1 ORDER BY ID DESC LIMIT 1")->getRowArray();
    }

    // public function atualizaSetorLoopPainel($setor_anterior,$proximo_setor){
    //     $anterior = $this->db->query("UPDATE CONFIG_PAINEL_OCUPACAO SET ULT_MOSTRADO = 0 WHERE NR_SETOR=$setor_anterior");
    //     if($anterior==true){
    //         return $this->db->query("UPDATE CONFIG_PAINEL_OCUPACAO SET ULT_MOSTRADO = 1 WHERE NR_SETOR=$proximo_setor");
    //     }else{
    //         return false;
    //     }
    // }
}
?>
