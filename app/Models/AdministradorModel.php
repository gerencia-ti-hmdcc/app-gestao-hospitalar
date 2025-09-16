<?php

namespace App\Models;
use CodeIgniter\Model;

class AdministradorModel extends Model {
    
    public function retornaUsuarios(){
        return $this->db->query("SELECT U.*,C.NOME_TIPO_PERFIL FROM USERS U JOIN CONFIG_USUARIO_TIPO_PERFIL C ON (C.SIGLA_TIPO_PERFIL=U.TIPO_PERFIL) ORDER BY NOME ASC")->getResultArray();
    }

    public function retornaUsuario($id){
        return $this->db->query("SELECT U.*,C.NOME_TIPO_PERFIL FROM USERS U JOIN CONFIG_USUARIO_TIPO_PERFIL C ON (C.SIGLA_TIPO_PERFIL=U.TIPO_PERFIL) WHERE U.ID=$id")->getRowArray();
    }

    public function retornaTodosStatus(){
        return $this->db->query("SELECT DISTINCT SIGLA_STATUS, NOME_STATUS FROM CONFIG_USUARIO_STATUS WHERE ATIVO=1")->getResultArray();
    }

    public function retornaTodosTiposPerfis(){
        return $this->db->query("SELECT DISTINCT SIGLA_TIPO_PERFIL, NOME_TIPO_PERFIL FROM CONFIG_USUARIO_TIPO_PERFIL WHERE ATIVO=1")->getResultArray();
    }

    public function resetaSenha($id){
        return $this->db->query("UPDATE USERS SET SENHA='e10adc3949ba59abbe56e057f20f883e' WHERE ID=$id");
    }

    public function atualizaUsuario($id,$nome,$email,$status,$tipo_perfil,$usuario_ad=''){
        return $this->db->query("UPDATE USERS SET NOME='$nome', EMAIL='$email', IE_STATUS='$status', TIPO_PERFIL='$tipo_perfil', USUARIO_AD='$usuario_ad' WHERE ID=$id");
    }

    public function tipoPerfilUsuario($id){
        return $this->db->query("SELECT TIPO_PERFIL FROM USERS WHERE ID=$id")->getRowArray();
    }

    public function cadastraUsuario($nome,$email,$status,$perfil,$usuario_ad=''){
        return $this->db->query("INSERT INTO
                                    USERS(NOME,EMAIL,IE_STATUS,TIPO_PERFIL,SENHA,USUARIO_AD)
                                VALUES
                                    ('$nome','$email','$status','$perfil','e10adc3949ba59abbe56e057f20f883e','$usuario_ad')");
    }

    public function existeEmailUsuario($email,$id=0){
        if($id==0){
            $condicao = "";
        }else{
            $condicao = "AND ID <>$id";
        }
        return $this->db->query("SELECT EMAIL FROM USERS WHERE EMAIL='$email' $condicao")->getRowArray();
    }

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
