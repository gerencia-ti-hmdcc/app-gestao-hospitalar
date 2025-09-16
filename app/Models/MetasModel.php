<?php

namespace App\Models;
use CodeIgniter\Model;

class MetasModel extends Model {
    
    public function retornaMetasAdmissoes(){
        return $this->db->query("SELECT 
                                    C.ID,
                                    C.ANO,
                                    C.QUADRIMESTRE,
                                    C.TIPO_ADMISSAO,
                                    C.QUANTIDADE,
                                    C.NR_AGRUPAMENTO,
                                    A.DESC_AGRUPAMENTO
                                FROM 
                                    CONFIG_META_ADMISSAO C 
                                    INNER JOIN CONFIG_AGRUPAMENTO A ON (A.NR_AGRUPAMENTO=C.NR_AGRUPAMENTO)
                                ORDER BY
                                    C.ANO DESC, C.QUADRIMESTRE DESC")->getResultArray();
    }

    public function retornaTodosAgrupamentos(){
        return $this->db->query("SELECT * FROM CONFIG_AGRUPAMENTO WHERE ID not in (2,3,4,5,6,7,8)")->getResultArray();
    }

    public function cadastraMeta($ano,$quadrimestre,$tipo,$linha,$quantidade){
        return $this->db->query("INSERT INTO
                                    CONFIG_META_ADMISSAO(ANO,QUADRIMESTRE,TIPO_ADMISSAO,NR_AGRUPAMENTO,QUANTIDADE)
                                VALUES
                                    ($ano,$quadrimestre,'$tipo',$linha,$quantidade)");
    }

    public function retornaMeta($id){
        return $this->db->query("SELECT * FROM CONFIG_META_ADMISSAO WHERE ID=$id")->getRowArray();
    }

    public function atualizaMeta($id,$ano,$quadrimestre,$tipo,$linha,$quantidade){
        return $this->db->query("UPDATE CONFIG_META_ADMISSAO SET ANO=$ano, QUADRIMESTRE=$quadrimestre, TIPO_ADMISSAO='$tipo', NR_AGRUPAMENTO=$linha, QUANTIDADE=$quantidade WHERE ID=$id");
    }

    public function excluirMeta($id){
        return $this->db->query("DELETE FROM CONFIG_META_ADMISSAO WHERE ID=$id");
    }
}
?>
