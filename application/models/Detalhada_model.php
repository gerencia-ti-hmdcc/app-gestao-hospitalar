<?php
class Detalhada_model extends CI_Model {
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

    public function retornaLinhasDeCuidado($usuario){
        if($usuario["TIPO_PERFIL"]=="A" || $usuario["TIPO_PERFIL"]=="E"){
            $condicao = "1=1";
        }else{
            $condicao = "IE_STATUS=1";
        }
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    LINHA_CUIDADO 
                                WHERE 
                                    $condicao
                                ORDER BY 
                                    ds_linha_cuidado ASC")->result_array();
    }

    public function retornaSetoresPorLinha($linha_cuidado){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    SETOR_ATENDIMENTO 
                                WHERE 
                                    CD_CLASSIF_SETOR=$linha_cuidado
                                    AND IE_STATUS=1
                                    AND CD_SETOR_ATENDIMENTO <>129
                                ORDER BY
                                    ds_setor_atendimento ASC")->result_array();
    }

    public function retornaLeitosClassifSetor($linha_cuidado, $cd_setor_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    DETALHE_OCUPACAO 
                                WHERE 
                                    CD_AGRUPAMENTO=$linha_cuidado
                                    AND CD_SETOR_ATENDIMENTO=$cd_setor_atendimento
                                ORDER BY 
                                    ds_leito_atual asc")->result_array();
    }

    public function retornaDadosLeito($nr_atendimento,$leito_atual,$cd_setor_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    DETALHE_OCUPACAO 
                                WHERE 
                                    nr_atendimento = $nr_atendimento
                                    AND ds_leito_atual = '$leito_atual'
                                    AND cd_setor_atendimento = $cd_setor_atendimento")->row_array();
    }

    public function retornaDadosLinhaCuidado($linha_cuidado){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    LINHA_CUIDADO 
                                WHERE 
                                    cd_classif_setor=$linha_cuidado")->row_array();
    }

    public function retornaDadosSetorAtendimento($cd_setor_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    SETOR_ATENDIMENTO 
                                WHERE 
                                    cd_setor_atendimento=$cd_setor_atendimento")->row_array();
    }

    public function retornaMovimentacoesAtendimento($nr_atendimento){
        return $this->db->query("SELECT
                                    *
                                FROM
                                    DETALHE_OCUPACAO_MOVIMENTACAO
                                WHERE
                                    nr_atendimento=$nr_atendimento
                                ORDER BY
                                    dt_entrada_unidade asc,
                                    dt_saida_unidade asc")->result_array();
    }

    // public function percentuaisGeraisOcupacao(){
    //     return $this->db->query("SELECT * FROM OCUPACAO_TOTAL WHERE date_format(dt_atualizacao, '%Y-%m-%d %H:%i') = (SELECT MAX(date_format(dt_atualizacao, '%Y-%m-%d %H:%i')) FROM OCUPACAO_TOTAL) ORDER BY NR_UNIDADES_SETOR DESC, CD_CLASSIF_SETOR DESC")->result_array();
    // }

    // public function percentuaisSetorOcupacao($id_area){
    //     if($id_area==3 OR $id_area==99 OR $id_area==4){
    //         $condicao = "AND CD_CLASSIF_SETOR in ($id_area) AND CD_SETOR_ATENDIMENTO not in (129,83,145)";
    //     }else{
    //         $condicao = "AND CD_SETOR_ATENDIMENTO in ($id_area)";
    //     }
    //     //return $this->db->query("SELECT * FROM `OCUPACAO_SETOR` WHERE DT_ATUALIZACAO >= DATE_SUB(NOW(), INTERVAL 1 HOUR) AND CD_CLASSIF_SETOR = $id_area")->result_array();
    //     return $this->db->query("SELECT * FROM `OCUPACAO_SETOR` WHERE date_format(dt_atualizacao, '%Y-%m-%d %H:%i') = (SELECT MAX(date_format(dt_atualizacao, '%Y-%m-%d %H:%i')) FROM `OCUPACAO_SETOR`) $condicao")->result_array();
    // }

    // public function retornaSetorLoopPainel(){
    //     return $this->db->query("SELECT * FROM CONFIG_PAINEL_OCUPACAO WHERE ATIVO=1 ORDER BY ID ASC")->result_array();
    // }

    // public function retornaUltimoSetorGeralAtivo(){
    //     return $this->db->query("SELECT * FROM CONFIG_PAINEL_OCUPACAO WHERE ATIVO=1 ORDER BY ID DESC LIMIT 1")->row_array();
    // }

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
