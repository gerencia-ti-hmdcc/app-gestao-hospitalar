<?php
class Detalhada_model extends CI_Model {
    
    public function retornaLinhasDeCuidado($usuario){
        if($usuario["TIPO_PERFIL"]=="A" || $usuario["TIPO_PERFIL"]=="E" || $usuario["TIPO_PERFIL"]=="D"){
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

    public function retornaUltimaAtualizacaoLeitos(){
        return $this->db->query("SELECT MAX(ultima_atualizacao) as ultima_atualizacao FROM `DETALHE_OCUPACAO` WHERE 1")->row_array();
    }

    public function retornaDadosLeitoPorAtendimento($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    DETALHE_OCUPACAO 
                                WHERE 
                                    nr_atendimento = $nr_atendimento")->row_array();
    }

    public function retornaHistoricoAvaliacoesVerdeVermelho($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    PACIENTE_EVOLUCAO_VERDE_VERMELHO 
                                WHERE 
                                    nr_atendimento=$nr_atendimento
                                ORDER BY 
                                    dt_liberacao DESC")->result_array();
    }

    public function retornaTotaisAvaliacoesVerdeVermelho($nr_atendimento){
        return $this->db->query("SELECT 
                                    (SELECT    
                                        COUNT(ds_verde_ou_vermelho)
                                    FROM
                                        PACIENTE_EVOLUCAO_VERDE_VERMELHO
                                    WHERE 
                                        nr_atendimento=$nr_atendimento) as total,
                                    (SELECT    
                                        COUNT(ds_verde_ou_vermelho)
                                    FROM
                                        PACIENTE_EVOLUCAO_VERDE_VERMELHO
                                    WHERE 
                                        nr_atendimento=$nr_atendimento
                                        AND ds_verde_ou_vermelho='VERDE') as total_verde,
                                    (
                                        (SELECT    
                                            COUNT(ds_verde_ou_vermelho)
                                        FROM
                                            PACIENTE_EVOLUCAO_VERDE_VERMELHO
                                        WHERE 
                                            nr_atendimento=$nr_atendimento
                                            AND ds_verde_ou_vermelho='VERDE')*100/
                                                (SELECT    
                                                    COUNT(ds_verde_ou_vermelho)
                                                FROM
                                                    PACIENTE_EVOLUCAO_VERDE_VERMELHO
                                                WHERE 
                                                    nr_atendimento=$nr_atendimento)
                                    ) as porcentagem_verde,
                                    (SELECT    
                                        COUNT(ds_verde_ou_vermelho)
                                    FROM
                                        PACIENTE_EVOLUCAO_VERDE_VERMELHO
                                    WHERE 
                                        nr_atendimento=$nr_atendimento
                                        AND ds_verde_ou_vermelho='VERMELHO') as total_vermelho,
                                    (
                                        (SELECT    
                                            COUNT(ds_verde_ou_vermelho)
                                        FROM
                                            PACIENTE_EVOLUCAO_VERDE_VERMELHO
                                        WHERE 
                                            nr_atendimento=$nr_atendimento
                                            AND ds_verde_ou_vermelho='VERMELHO')*100/
                                                (SELECT    
                                                    COUNT(ds_verde_ou_vermelho)
                                                FROM
                                                    PACIENTE_EVOLUCAO_VERDE_VERMELHO
                                                WHERE 
                                                    nr_atendimento=$nr_atendimento)
                                    ) as porcentagem_vermelho
                                FROM 
                                    dual")->row_array();
    }

    public function retornaHistoricoEvolucoesPaciente($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    PACIENTE_EVOLUCAO 
                                WHERE 
                                    nr_atendimento=$nr_atendimento
                                ORDER BY 
                                    dt_liberacao_evolucao DESC")->result_array();
    }

    public function retornaHistoricoInterconsultasPaciente($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    PACIENTE_INTERCONSULTA 
                                WHERE 
                                    nr_atendimento=$nr_atendimento
                                ORDER BY 
                                    nr_parecer DESC, ds_tipo DESC")->result_array();
    }

    public function retornaHistoricoExamesLaboratoriaisPaciente($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    PACIENTE_EXAME_LABORATORIAL 
                                WHERE 
                                    nr_atendimento = $nr_atendimento
                                ORDER BY
                                    dt_baixa DESC, nr_prescricao DESC, nr_sequencia ASC")->result_array();
    }

}
?>
