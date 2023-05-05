<?php
class Admissoes_model extends CI_Model {

    public function retornaAdmissoesMes($mes,$ano){
        if($mes==0 || $ano==0){
            $mes = date('m');
            $ano = date("Y");
        }
        return $this->db->query("SELECT * FROM ADMISSAO_DIARIA WHERE MES_REFERENCIA='$mes' AND ANO_REFERENCIA='$ano'")->result_array();
    }
    
    public function retornaQuantAdmissoesMes($mes,$ano){
        if($mes==0 || $ano==0){
            $mes = date('m');
            $ano = date("Y");
        }
        return $this->db->query("SELECT 
                                    IE_TIPO_ADMISSAO,DIA_REFERENCIA, MES_REFERENCIA, ANO_REFERENCIA, COUNT(*) QUANTIDADE 
                                FROM 
                                    ADMISSAO_DIARIA 
                                WHERE
                                    MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                GROUP BY 
                                    IE_TIPO_ADMISSAO,DIA_REFERENCIA, MES_REFERENCIA, ANO_REFERENCIA
                                ORDER BY
                                    DIA_REFERENCIA,MES_REFERENCIA,ANO_REFERENCIA,IE_TIPO_ADMISSAO")->result_array();
    }

    public function retornaQuantAdmissoesMesPorLinha($mes,$ano,$agrupamento){
        if($mes==0 || $ano==0){
            $mes = date('m');
            $ano = date("Y");
        }

        if($agrupamento==1){
            //AGRUPAMENTOS CTI
            $cond_agrupamento = "AND AGRUPAMENTO_ATUAL in ($agrupamento,2,3,4,5,6,7,8)";
        }else{
            $cond_agrupamento = "AND AGRUPAMENTO_ATUAL=$agrupamento";
        }

        return $this->db->query("SELECT 
                                    IE_TIPO_ADMISSAO,DIA_REFERENCIA, MES_REFERENCIA, ANO_REFERENCIA, COUNT(*) QUANTIDADE 
                                FROM 
                                    ADMISSAO_DIARIA 
                                WHERE
                                    MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                    $cond_agrupamento
                                GROUP BY 
                                    IE_TIPO_ADMISSAO,DIA_REFERENCIA, MES_REFERENCIA, ANO_REFERENCIA
                                ORDER BY
                                    DIA_REFERENCIA,MES_REFERENCIA,ANO_REFERENCIA,IE_TIPO_ADMISSAO")->result_array();
    }

    public function retornaDetalhesAdmissoesMes($dia,$mes,$ano){
        if($mes==0 || $ano==0 || $dia==0){
            $dia = date('d');
            $mes = date('m');
            $ano = date("Y");
        }
        return $this->db->query("SELECT 
                                    IE_TIPO_ADMISSAO,
                                    CD_SETOR_ATENDIMENTO,
                                    DS_SETOR_ATENDIMENTO,
                                    COUNT(*) QUANTIDADE
                                FROM 
                                    ADMISSAO_DIARIA 
                                WHERE
                                    DIA_REFERENCIA='$dia'
                                    AND MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                GROUP BY 
                                    IE_TIPO_ADMISSAO,
                                    CD_SETOR_ATENDIMENTO,
                                    DS_SETOR_ATENDIMENTO
                                ORDER BY
                                    IE_TIPO_ADMISSAO,
                                    DS_SETOR_ATENDIMENTO,
                                    QUANTIDADE")->result_array();
    }

    public function retornaDetalhesAdmissoesMesPorLinha($dia,$mes,$ano,$agrupamento){
        if($mes==0 || $ano==0 || $dia==0){
            $dia = date('d');
            $mes = date('m');
            $ano = date("Y");
        }

        if($agrupamento==1){
            //AGRUPAMENTOS CTI
            $cond_agrupamento = "AND AGRUPAMENTO_ATUAL in ($agrupamento,2,3,4,5,6,7,8)";
        }else{
            $cond_agrupamento = "AND AGRUPAMENTO_ATUAL=$agrupamento";
        }

        return $this->db->query("SELECT 
                                    IE_TIPO_ADMISSAO,
                                    CD_SETOR_ATENDIMENTO,
                                    DS_SETOR_ATENDIMENTO,
                                    COUNT(*) QUANTIDADE
                                FROM 
                                    ADMISSAO_DIARIA 
                                WHERE
                                    DIA_REFERENCIA='$dia'
                                    AND MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                    $cond_agrupamento
                                GROUP BY 
                                    IE_TIPO_ADMISSAO,
                                    CD_SETOR_ATENDIMENTO,
                                    DS_SETOR_ATENDIMENTO
                                ORDER BY
                                    IE_TIPO_ADMISSAO,
                                    DS_SETOR_ATENDIMENTO,
                                    QUANTIDADE")->result_array();
    }

    public function retornaTotaisMesSemCTI($mes,$ano){
        if($mes==0 || $ano==0){
            $mes = date('m');
            $ano = date("Y");
        }
        return $this->db->query("SELECT 
                                    AD.IE_TIPO_ADMISSAO,
                                    AD.AGRUPAMENTO_ATUAL,
                                    CA.DESC_AGRUPAMENTO,
                                    COUNT(*) QUANTIDADE
                                FROM 
                                    ADMISSAO_DIARIA AD
                                    JOIN CONFIG_AGRUPAMENTO CA ON (AD.AGRUPAMENTO_ATUAL=CA.NR_AGRUPAMENTO)
                                WHERE
                                    MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                    AND AD.AGRUPAMENTO_ATUAL > 8
                                GROUP BY 
                                    AD.IE_TIPO_ADMISSAO,
                                    AD.AGRUPAMENTO_ATUAL,
                                    CA.DESC_AGRUPAMENTO
                                ORDER BY
                                    AD.AGRUPAMENTO_ATUAL,
                                    AD.IE_TIPO_ADMISSAO,
                                    QUANTIDADE")->result_array();
    }

    public function retornaTotaisMesCTI($mes,$ano){
        if($mes==0 || $ano==0){
            $mes = date('m');
            $ano = date("Y");
        }
        return $this->db->query("SELECT 
                                    AD.IE_TIPO_ADMISSAO,
                                    CA.DESC_AGRUPAMENTO,
                                    COUNT(*) QUANTIDADE
                                FROM 
                                    ADMISSAO_DIARIA AD
                                    JOIN CONFIG_AGRUPAMENTO CA ON (AD.AGRUPAMENTO_ATUAL=CA.NR_AGRUPAMENTO)
                                WHERE
                                    MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                    AND AD.AGRUPAMENTO_ATUAL <= 8
                                GROUP BY 
                                    AD.IE_TIPO_ADMISSAO,
                                    CA.DESC_AGRUPAMENTO
                                ORDER BY
                                    AD.IE_TIPO_ADMISSAO,
                                    AD.AGRUPAMENTO_ATUAL,
                                    QUANTIDADE")->result_array();
    }

    public function retornaTotaisMesPorLinha($mes,$ano,$agrupamento){
        if($mes==0 || $ano==0){
            $mes = date('m');
            $ano = date("Y");
        }

        if($agrupamento==1){
            //AGRUPAMENTOS CTI
            $cond_agrupamento = "AND AD.AGRUPAMENTO_ATUAL in ($agrupamento,2,3,4,5,6,7,8)";
        }else{
            $cond_agrupamento = "AND AD.AGRUPAMENTO_ATUAL=$agrupamento";
        }

        return $this->db->query("SELECT 
                                    AD.IE_TIPO_ADMISSAO,
                                    CA.DESC_AGRUPAMENTO,
                                    COUNT(*) QUANTIDADE
                                FROM 
                                    ADMISSAO_DIARIA AD
                                    JOIN CONFIG_AGRUPAMENTO CA ON (AD.AGRUPAMENTO_ATUAL=CA.NR_AGRUPAMENTO)
                                WHERE
                                    MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                    $cond_agrupamento
                                GROUP BY 
                                    AD.IE_TIPO_ADMISSAO,
                                    CA.DESC_AGRUPAMENTO
                                ORDER BY
                                    AD.IE_TIPO_ADMISSAO,
                                    AD.AGRUPAMENTO_ATUAL,
                                    QUANTIDADE")->result_array();
    }

    public function retornaMetasAnuaisPorLinha($ano,$quadrimestre,$agrupamento,$tipo_admissao=''){
        if($ano==0){
            $ano = date("Y");
        }

        if(strlen($tipo_admissao)>0){
            $and_tipo_admissao = "AND TIPO_ADMISSAO='$tipo_admissao'";
        }else{
            $and_tipo_admissao = "";
        }

        return $this->db->query("SELECT 
                                    * 
                                FROM 
                                    CONFIG_META_ADMISSAO 
                                WHERE 
                                    NR_AGRUPAMENTO=$agrupamento
                                    AND QUADRIMESTRE=$quadrimestre
                                    AND ANO = '$ano' $and_tipo_admissao")->result_array();
    }

    public function retornaOfertasDiarias($mes,$ano){
        if($ano==0 || $mes==0){
            $ano = date("Y");
            $mes = date("m");
        }

        return $this->db->query("SELECT 
                                    ds_setor_solicitado, 
                                    ds_tipo_vaga, 
                                    YEAR(dt_solicitacao) ano, 
                                    MONTH(dt_solicitacao) mes, 
                                    DAY(dt_solicitacao) dia,
                                    COUNT(*) quantidade
                                FROM 
                                    OFERTAS_DIARIAS
                                WHERE 
                                    YEAR(dt_solicitacao)='$ano' AND 
                                    MONTH(dt_solicitacao)='$mes' 
                                    AND ds_status='L' 
                                GROUP BY
                                    ds_setor_solicitado, ds_tipo_vaga, YEAR(dt_solicitacao), MONTH(dt_solicitacao), DAY(dt_solicitacao)
                                ORDER BY
                                    dia, mes, ano, ds_setor_solicitado ASC, quantidade DESC")->result_array();
    }

    public function retornaDetalhesOfertasDiarias($ano,$mes,$dia){
        if($ano==0 || $mes==0 || $dia==0){
            $ano = date("Y");
            $mes = date("m");
            $dia = date('d');
        }

        return $this->db->query("SELECT 
                                    ds_setor_solicitado, 
                                    ds_tipo_vaga, 
                                    YEAR(dt_solicitacao) ano, 
                                    MONTH(dt_solicitacao) mes, 
                                    COUNT(*) quantidade
                                FROM 
                                    OFERTAS_DIARIAS 
                                WHERE 
                                    YEAR(dt_solicitacao)='$ano' AND 
                                    MONTH(dt_solicitacao)='$mes' AND
                                    DAY(dt_solicitacao)='$dia'
                                    AND ds_status='L'
                                GROUP BY
                                    ds_setor_solicitado, ds_tipo_vaga, YEAR(dt_solicitacao), MONTH(dt_solicitacao)
                                ORDER BY
                                    ds_setor_solicitado ASC, quantidade DESC")->result_array();
    }

    public function retornaTotaisOfertasMes($ano,$mes){
        if($ano==0 || $mes==0){
            $ano = date("Y");
            $mes = date("m");
        }

        return $this->db->query("SELECT 
                                    ds_setor_solicitado, 
                                    YEAR(dt_solicitacao) ano, 
                                    MONTH(dt_solicitacao) mes, 
                                    COUNT(*) quantidade
                                FROM 
                                    OFERTAS_DIARIAS 
                                WHERE 
                                    YEAR(dt_solicitacao)='$ano' AND 
                                    MONTH(dt_solicitacao)='$mes'
                                    AND ds_status='L'
                                GROUP BY
                                    ds_setor_solicitado, YEAR(dt_solicitacao), MONTH(dt_solicitacao)
                                ORDER BY
                                    ds_setor_solicitado ASC, quantidade DESC")->result_array();
    }

    public function retornaTotalOfertasMes($ano,$mes){
        if($ano==0 || $mes==0){
            $ano = date("Y");
            $mes = date("m");
        }

        return $this->db->query("SELECT 
                                    YEAR(dt_solicitacao) ano, 
                                    MONTH(dt_solicitacao) mes, 
                                    COUNT(*) quantidade
                                FROM 
                                    OFERTAS_DIARIAS 
                                WHERE 
                                    YEAR(dt_solicitacao)='$ano' AND 
                                    MONTH(dt_solicitacao)='$mes'
                                    AND ds_status='L'
                                GROUP BY
                                    YEAR(dt_solicitacao), MONTH(dt_solicitacao)
                                ORDER BY
                                    ds_setor_solicitado ASC, quantidade DESC")->result_array();
    }

    public function retornaTotaisOfertasPorDia($mes,$ano){
        if($ano==0 || $mes==0){
            $ano = date("Y");
            $mes = date("m");
        }

        return $this->db->query("SELECT 
                                    YEAR(dt_solicitacao) ano, 
                                    MONTH(dt_solicitacao) mes, 
                                    DAY(dt_solicitacao) dia, 
                                    COUNT(*) quantidade
                                FROM 
                                    OFERTAS_DIARIAS 
                                WHERE 
                                    YEAR(dt_solicitacao)='$ano' AND 
                                    MONTH(dt_solicitacao)='$mes'
                                    AND ds_status='L'
                                GROUP BY
                                    YEAR(dt_solicitacao), MONTH(dt_solicitacao),  DAY(dt_solicitacao)
                                ORDER BY
                                    dia, mes, ano, ds_setor_solicitado ASC, quantidade DESC")->result_array();
    }

    public function retornaAdmissoesDiaPeriodicas($dia,$mes,$ano){
        if($mes==0 || $ano==0 || $dia==0){
            $dia = date('d');
            $mes = date('m');
            $ano = date("Y");
        }

        return $this->db->query("SELECT 
                                    IE_TIPO_ADMISSAO,DIA_REFERENCIA, MES_REFERENCIA, ANO_REFERENCIA, COUNT(*) QUANTIDADE 
                                FROM 
                                    ADMISSAO_DIARIA_PERIODICA 
                                WHERE
                                    DIA_REFERENCIA='$dia'
                                    AND MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                GROUP BY 
                                    IE_TIPO_ADMISSAO, DIA_REFERENCIA, MES_REFERENCIA, ANO_REFERENCIA
                                ORDER BY
                                    DIA_REFERENCIA,MES_REFERENCIA,ANO_REFERENCIA,IE_TIPO_ADMISSAO")->result_array();
    }

    public function retornaOfertasDiaPeriodicas($dia,$mes,$ano){
        if($mes==0 || $ano==0 || $dia==0){
            $dia = date('d');
            $mes = date('m');
            $ano = date("Y");
        }

        return $this->db->query("SELECT 
                                    YEAR(dt_solicitacao) ano, 
                                    MONTH(dt_solicitacao) mes, 
                                    DAY(dt_solicitacao) dia, 
                                    COUNT(*) quantidade
                                FROM 
                                    OFERTAS_DIARIAS
                                WHERE 
                                    YEAR(dt_solicitacao)='$ano' AND 
                                    MONTH(dt_solicitacao)='$mes' AND
                                    DAY(dt_solicitacao)='$dia'
                                    AND ds_status='L'
                                GROUP BY
                                    YEAR(dt_solicitacao), MONTH(dt_solicitacao),  DAY(dt_solicitacao)
                                ORDER BY
                                    dia, mes, ano, ds_setor_solicitado ASC, quantidade DESC")->result_array();
    }

    public function retornaDetalhesAdmissoesMesPeriodicas($dia,$mes,$ano){
        if($mes==0 || $ano==0 || $dia==0){
            $dia = date('d');
            $mes = date('m');
            $ano = date("Y");
        }
        return $this->db->query("SELECT 
                                    IE_TIPO_ADMISSAO,
                                    CD_SETOR_ATENDIMENTO,
                                    DS_SETOR_ATENDIMENTO,
                                    COUNT(*) QUANTIDADE
                                FROM 
                                    ADMISSAO_DIARIA_PERIODICA 
                                WHERE
                                    DIA_REFERENCIA='$dia'
                                    AND MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                GROUP BY 
                                    IE_TIPO_ADMISSAO,
                                    CD_SETOR_ATENDIMENTO,
                                    DS_SETOR_ATENDIMENTO
                                ORDER BY
                                    IE_TIPO_ADMISSAO,
                                    DS_SETOR_ATENDIMENTO,
                                    QUANTIDADE")->result_array();
    }

    public function retornaDetalhesOfertasDiariasPeriodicas($ano,$mes,$dia){
        if($ano==0 || $mes==0 || $dia==0){
            $ano = date("Y");
            $mes = date("m");
            $dia = date('d');
        }

        return $this->db->query("SELECT 
                                    ds_setor_solicitado, 
                                    ds_tipo_vaga, 
                                    YEAR(dt_solicitacao) ano, 
                                    MONTH(dt_solicitacao) mes, 
                                    COUNT(*) quantidade
                                FROM 
                                    OFERTAS_DIARIAS
                                WHERE 
                                    YEAR(dt_solicitacao)='$ano' AND 
                                    MONTH(dt_solicitacao)='$mes' AND
                                    DAY(dt_solicitacao)='$dia'
                                    AND ds_status='L'
                                GROUP BY
                                    ds_setor_solicitado, ds_tipo_vaga, YEAR(dt_solicitacao), MONTH(dt_solicitacao)
                                ORDER BY
                                    ds_setor_solicitado ASC, quantidade DESC")->result_array();
    }

    public function retornaTotaisMesSemCTIPeriodicas($dia=0,$mes=0,$ano=0){
        if($mes==0 || $ano==0 || $dia==0){
            $mes = date('m');
            $ano = date("Y");
            $dia = date("d");
        }
        return $this->db->query("SELECT 
                                    AD.IE_TIPO_ADMISSAO,
                                    AD.AGRUPAMENTO_ATUAL,
                                    CA.DESC_AGRUPAMENTO,
                                    (SELECT 
                                        COUNT(*)  
                                    FROM 
                                        ADMISSAO_DIARIA_PERIODICA ADP  
                                    WHERE 
                                        ADP.IE_TIPO_ADMISSAO = AD.IE_TIPO_ADMISSAO 
                                        and ADP.AGRUPAMENTO_ATUAL =  AD.AGRUPAMENTO_ATUAL  	
                                        and DIA_REFERENCIA='$dia'
                                        AND MES_REFERENCIA='$mes' 
                                        AND ANO_REFERENCIA='$ano'
                                        AND ADP.AGRUPAMENTO_ATUAL > 8) ADMISSAO_DIA
                                    , 
                                    COUNT(*) DIARIA,
                                    COUNT(*) + 
                                        (SELECT 
                                            COUNT(*)  
                                        FROM 
                                            ADMISSAO_DIARIA_PERIODICA ADP  
                                        WHERE 
                                            ADP.IE_TIPO_ADMISSAO = AD.IE_TIPO_ADMISSAO 
                                            and ADP.AGRUPAMENTO_ATUAL =  AD.AGRUPAMENTO_ATUAL  	
                                            and DIA_REFERENCIA='$dia'
                                            AND MES_REFERENCIA='$mes' 
                                            AND ANO_REFERENCIA='$ano'
                                            AND ADP.AGRUPAMENTO_ATUAL > 8) QUANTIDADE
                                FROM 
                                    ADMISSAO_DIARIA AD
                                    JOIN CONFIG_AGRUPAMENTO CA ON (AD.AGRUPAMENTO_ATUAL=CA.NR_AGRUPAMENTO)
                                WHERE
                                    MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                    AND AD.AGRUPAMENTO_ATUAL > 8
                                GROUP BY 
                                    AD.IE_TIPO_ADMISSAO,
                                    AD.AGRUPAMENTO_ATUAL,
                                    CA.DESC_AGRUPAMENTO")->result_array();
    }

    public function retornaTotaisMesCTIPeriodicas($dia=0,$mes=0,$ano=0){
        if($mes==0 || $ano==0 || $dia==0){
            $mes = date('m');
            $ano = date("Y");
            $dia = date("d");
        }
        return $this->db->query("SELECT 
                                    AD.IE_TIPO_ADMISSAO,
                                    CA.DESC_AGRUPAMENTO,
                                    (SELECT 
                                        COUNT(*)  
                                    FROM 
                                        ADMISSAO_DIARIA_PERIODICA ADP  
                                    WHERE 
                                        ADP.IE_TIPO_ADMISSAO = AD.IE_TIPO_ADMISSAO 
                                        and ADP.AGRUPAMENTO_ATUAL =  AD.AGRUPAMENTO_ATUAL  	
                                        and DIA_REFERENCIA='$dia'
                                        AND MES_REFERENCIA='$mes' 
                                        AND ANO_REFERENCIA='$ano'
                                        AND ADP.AGRUPAMENTO_ATUAL <= 8) ADMISSAO_DIA, 
                                    COUNT(*) DIARIA,
                                    COUNT(*) + 
                                        (SELECT 
                                            COUNT(*)  
                                        FROM 
                                            ADMISSAO_DIARIA_PERIODICA ADP  
                                        WHERE 
                                            ADP.IE_TIPO_ADMISSAO = AD.IE_TIPO_ADMISSAO 
                                            and ADP.AGRUPAMENTO_ATUAL =  AD.AGRUPAMENTO_ATUAL  	
                                            and DIA_REFERENCIA='$dia'
                                            AND MES_REFERENCIA='$mes' 
                                            AND ANO_REFERENCIA='$ano'
                                            AND ADP.AGRUPAMENTO_ATUAL <= 8) QUANTIDADE
                                FROM 
                                    ADMISSAO_DIARIA AD
                                    JOIN CONFIG_AGRUPAMENTO CA ON (AD.AGRUPAMENTO_ATUAL=CA.NR_AGRUPAMENTO)
                                WHERE
                                    MES_REFERENCIA='$mes' 
                                    AND ANO_REFERENCIA='$ano'
                                    AND AD.AGRUPAMENTO_ATUAL <= 8
                                GROUP BY 
                                    AD.IE_TIPO_ADMISSAO,
                                    CA.DESC_AGRUPAMENTO")->result_array();
    }

    public function retornaUltimaHoraAdmissoesPeriodicas(){
        $ano = date("Y");
        $mes = date("m");
        $dia = date('d');

        return $this->db->query("SELECT
                                    MAX(HORARIO_REFERENCIA) ULTIMA_ATUALIZACAO,
                                    DIA_REFERENCIA,
                                    MES_REFERENCIA,
                                    ANO_REFERENCIA
                                FROM
                                    ADMISSAO_DIARIA_PERIODICA
                                WHERE
                                    DIA_REFERENCIA='$dia'
                                    AND MES_REFERENCIA='$mes'
                                    AND ANO_REFERENCIA='$ano'
                                ORDER BY
                                    ANO_REFERENCIA, MES_REFERENCIA, DIA_REFERENCIA DESC")->row_array();
    }

    // public function retornaUsuarios(){
    //     return $this->db->query("SELECT * FROM USERS ORDER BY NOME ASC")->result_array();
    // }

    // public function retornaUsuario($id){
    //     return $this->db->query("SELECT * FROM USERS WHERE ID=$id")->row_array();
    // }

    // public function retornaTodosStatus(){
    //     return $this->db->query("SELECT DISTINCT SIGLA_STATUS, NOME_STATUS FROM CONFIG_USUARIO_STATUS WHERE ATIVO=1")->result_array();
    // }

    // public function retornaTodosTiposPerfis(){
    //     return $this->db->query("SELECT DISTINCT SIGLA_TIPO_PERFIL, NOME_TIPO_PERFIL FROM CONFIG_USUARIO_TIPO_PERFIL WHERE ATIVO=1")->result_array();
    // }

    // public function resetaSenha($id){
    //     return $this->db->query("UPDATE USERS SET SENHA='e10adc3949ba59abbe56e057f20f883e' WHERE ID=$id");
    // }

    // public function atualizaUsuario($id,$nome,$email,$status,$tipo_perfil){
    //     return $this->db->query("UPDATE USERS SET NOME='$nome', EMAIL='$email', IE_STATUS='$status', TIPO_PERFIL='$tipo_perfil' WHERE ID=$id");
    // }

    // public function tipoPerfilUsuario($id){
    //     return $this->db->query("SELECT TIPO_PERFIL FROM USERS WHERE ID=$id")->row_array();
    // }

    // public function cadastraUsuario($nome,$email,$status,$perfil){
    //     return $this->db->query("INSERT INTO
    //                                 USERS(NOME,EMAIL,IE_STATUS,TIPO_PERFIL,SENHA)
    //                             VALUES
    //                                 ('$nome','$email','$status','$perfil','e10adc3949ba59abbe56e057f20f883e')");
    // }

    // public function existeEmailUsuario($email,$id=0){
    //     if($id==0){
    //         $condicao = "";
    //     }else{
    //         $condicao = "AND ID <>$id";
    //     }
    //     return $this->db->query("SELECT EMAIL FROM USERS WHERE EMAIL='$email' $condicao")->row_array();
    // }

}
?>
