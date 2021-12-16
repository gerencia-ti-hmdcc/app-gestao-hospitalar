<?php
class Dashboard_model extends CI_Model {
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
        return $this->db->query("SELECT * FROM HMDCC_APP_TOTAL_OCUPACAO")->result_array();
    }
}
?>
