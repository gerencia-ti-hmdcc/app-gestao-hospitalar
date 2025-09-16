<?php
namespace App\Models;
use CodeIgniter\Model;

class LoginModel extends Model {
    //function __construct ()
    // { 
    //}
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

    public function logar($email,$senha){
        return $this->db->query("SELECT * FROM USERS WHERE EMAIL='$email' AND SENHA='$senha'")->getRowArray();
    }

    public function atualizaToken($id,$data_atual,$token,$validade){
        $this->db->query("UPDATE
                            USERS
                        SET
                            ULTIMO_LOGIN='".$data_atual."',
                            TOKEN='".$token."',
                            VALIDADE_TOKEN='".$validade."'
                        WHERE
                            ID=$id");
    }

    public function cadastrarPrimeiroAcesso($email,$senha){
        $ok = $this->db->query("UPDATE
                            USERS
                        SET
                            SENHA='$senha'
                        WHERE
                            EMAIL='$email'");
        if($ok==true){
            return true;
        }else{
            return false;
        }
    }

    public function atualizaDisp($dispositivo,$id){
        $this->db->query("UPDATE
                            USERS
                        SET
                            DISP_ULTIMO_LOGIN='".$dispositivo."'
                        WHERE
                            ID=$id");
    }

    public function retornaUsuarioPainel(){
        return $this->db->query("SELECT * FROM USERS WHERE TIPO_PERFIL='P' AND IE_STATUS='A'")->getRowArray();
    }
}
?>
