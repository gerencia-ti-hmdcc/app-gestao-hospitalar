<?php

namespace App\Models;
use CodeIgniter\Model;

class DetalhadaModel extends Model {
    
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
                                    ds_linha_cuidado ASC")->getResultArray();
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
                                    ds_setor_atendimento ASC")->getResultArray();
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
                                    ds_leito_atual asc")->getResultArray();
    }

    public function retornaDadosLeito($nr_atendimento,$leito_atual,$cd_setor_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    DETALHE_OCUPACAO 
                                WHERE 
                                    nr_atendimento = $nr_atendimento
                                    AND ds_leito_atual = '$leito_atual'
                                    AND cd_setor_atendimento = $cd_setor_atendimento")->getRowArray();
    } 

    public function retornaDadosLinhaCuidado($linha_cuidado){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    LINHA_CUIDADO 
                                WHERE 
                                    cd_classif_setor=$linha_cuidado")->getRowArray();
    }

    public function retornaDadosSetorAtendimento($cd_setor_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    SETOR_ATENDIMENTO 
                                WHERE 
                                    cd_setor_atendimento=$cd_setor_atendimento")->getRowArray();
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
                                    dt_saida_unidade asc")->getResultArray();
    }

    public function retornaUltimaAtualizacaoLeitos(){
        return $this->db->query("SELECT MAX(ultima_atualizacao) as ultima_atualizacao FROM `DETALHE_OCUPACAO` WHERE 1")->getRowArray();
    }

    public function retornaDadosLeitoPorAtendimento($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    DETALHE_OCUPACAO 
                                WHERE 
                                    nr_atendimento = $nr_atendimento")->getRowArray();
    }

    public function retornaHistoricoAvaliacoesVerdeVermelho($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    PACIENTE_EVOLUCAO_VERDE_VERMELHO 
                                WHERE 
                                    nr_atendimento=$nr_atendimento
                                ORDER BY 
                                    dt_liberacao DESC")->getResultArray();
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
                                    dual")->getRowArray();
    }

    public function retornaHistoricoEvolucoesPaciente($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    PACIENTE_EVOLUCAO 
                                WHERE 
                                    nr_atendimento=$nr_atendimento
                                ORDER BY 
                                    dt_liberacao_evolucao DESC")->getResultArray();
    }

    public function retornaHistoricoInterconsultasPaciente($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    PACIENTE_INTERCONSULTA 
                                WHERE 
                                    nr_atendimento=$nr_atendimento
                                ORDER BY 
                                    nr_parecer DESC, ds_tipo DESC")->getResultArray();
    }

    public function retornaHistoricoExamesLaboratoriaisPaciente($nr_atendimento){
        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    PACIENTE_EXAME_LABORATORIAL 
                                WHERE 
                                    nr_atendimento = $nr_atendimento
                                ORDER BY
                                    dt_baixa DESC, nr_prescricao DESC, nr_sequencia ASC")->getResultArray();
    }

    public function retornaProntuarioAtendimento($nr_atendimento){
        return $this->db->query("SELECT 
                                    nr_prontuario 
                                FROM 
                                    DETALHE_OCUPACAO 
                               WHERE 
                                    nr_atendimento = $nr_atendimento")->getRowArray();
    }

    protected $site1 = 'site';

    public function retornaExamesImagemPaciente($nr_prontuario)
    {
        $site = \Config\Database::connect($this->site1);

        $query = $site->query("SELECT * FROM HMDCC_EXAMES_IMAGEM WHERE nr_prontuario = ?", [$nr_prontuario]);
        return $query->getResultArray();
    }

    public function retornaUltimasAvaliacoesBradenPaciente(){
        return $this->db->query("SELECT
                                    NR_ATENDIMENTO, 
                                    CD_SETOR_ATENDIMENTO, 
                                    DS_LEITO_ATUAL, 
                                    DT_LIBERACAO_BRADEN, 
                                    PONTOS_BRADEN, 
                                    CLASSIFICACAO_BRADEN,
                                    PROFISSIONAL_BRADEN,
                                    SEQ_BRADEN
                                FROM 
                                    PACIENTE_DADOS_CLINICOS a
                                WHERE
                                    NR_ATENDIMENTO is not null
                                    AND NR_ATENDIMENTO <> 0
                                    AND SEQ_BRADEN is not null
                                    AND SEQ_BRADEN <> 0
                                    and DT_LIBERACAO_BRADEN = ( select max(x.DT_LIBERACAO_BRADEN) from PACIENTE_DADOS_CLINICOS x where x.NR_ATENDIMENTO=a.nr_atendimento)
                                GROUP BY	
                                    NR_ATENDIMENTO")->getResultArray();
    }

    public function retornaUltimasAvaliacoesMorsePaciente(){
        return $this->db->query("SELECT
                                    NR_ATENDIMENTO, 
                                    CD_SETOR_ATENDIMENTO, 
                                    DS_LEITO_ATUAL,
                                    DT_LIBERACAO_MORSE,
                                    PONTOS_MORSE, 
                                    CLASSIFICACAO_MORSE,
                                    PROFISSIONAL_MORSE,
                                    SEQ_MORSE
                                FROM 
                                    PACIENTE_DADOS_CLINICOS a
                                WHERE
                                    NR_ATENDIMENTO is not null
                                    AND NR_ATENDIMENTO <> 0
                                    AND SEQ_MORSE is not null
                                    AND SEQ_MORSE <> 0
                                    and DT_LIBERACAO_MORSE = ( select max(x.DT_LIBERACAO_MORSE) from PACIENTE_DADOS_CLINICOS x where x.NR_ATENDIMENTO = a.nr_atendimento)
                                GROUP BY	
                                    NR_ATENDIMENTO")->getResultArray();
    }
}
?>
