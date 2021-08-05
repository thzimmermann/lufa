<?php
class Plantacao_model extends CI_Model
{
    public function getListaPlantacoes()
    {
        $sql = "SELECT a.id_plantacao
                    , DATE_FORMAT(a.dt_plantacao, '%d/%m/%Y') as data_plantacao
                    , DATE_FORMAT(a.dt_estimativa_colheita, '%d/%m/%Y') as data_estimativa_colheita
                    , a.id_cultivo
                    , (select b.nome from item_cultivo as b WHERE b.id_cultivo = a.id_cultivo) as item_cultivo
                    , a.id_terreno
                    , (select b.descricao from terrenos as b WHERE b.id_terreno = a.id_terreno) as desc_terreno
                    , a.status
                FROM plantacao as a
                ORDER BY a.dt_plantacao asc";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function createPlantacao($obj)
    {
        $this->db->insert('plantacao', $obj);
        return $this->db->insert_id('id_plantacao');
    }

    public function getPlantacao($id)
    {
        $sql = "SELECT a.id_plantacao
                    , a.dt_plantacao
                    , a.dt_estimativa_colheita
                    , DATE_FORMAT(a.dt_plantacao, '%d/%m/%Y') as data_plantacao
                    , DATE_FORMAT(a.dt_estimativa_colheita, '%d/%m/%Y') as data_estimativa_colheita
                    , a.id_cultivo
                    , (select b.nome from item_cultivo as b WHERE b.id_cultivo = a.id_cultivo) as item_cultivo
                    , a.id_terreno
                    , (select b.descricao from terrenos as b WHERE b.id_terreno = a.id_terreno) as desc_terreno
                    , a.status
                FROM plantacao as a
                WHERE a.id_plantacao = {$id}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function updatePlantacao($where, $obj)
    {
        $this->db->where('id_plantacao', $obj['id_plantacao']);
        return $this->db->update('plantacao', $obj);
    }

    public function getPlantacaoFiltro($busca)
    {
        $filtro = "WHERE a.status = 'S'";
        if (trim($busca) != ''){
            $filtro .= "AND c.descricao like '%{$busca}%'";
        }

        $sql = "SELECT a.id_plantacao
                    , DATE_FORMAT(a.dt_plantacao, '%d/%m/%Y') as data_plantacao
                    , DATE_FORMAT(a.dt_estimativa_colheita, '%d/%m/%Y') as data_estimativa_colheita
                    , a.id_cultivo
                    , b.nome as item_cultivo
                    , c.descricao as desc_terreno
                FROM plantacao as a
                INNER JOIN item_cultivo as b ON b.id_cultivo = a.id_cultivo
                INNER JOIN terrenos as c ON c.id_terreno = a.id_terreno
                {$filtro}
                ORDER BY a.dt_plantacao ASC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getItemColheitaFiltro($busca)
    {
        $filtro = 'WHERE 1=1';
        if (trim($busca) != ''){
            $filtro .= " AND nome like '%{$busca}%'";
        }

        $sql = "SELECT id_cultivo
                    , nome
                FROM item_cultivo
                {$filtro}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getPlantacaoGeral()
    {
        $sql = "SELECT a.id_plantacao
                    , DATE_FORMAT(a.dt_plantacao, '%d/%m/%Y') as data_plantacao
                    , DATE_FORMAT(a.dt_estimativa_colheita, '%d/%m/%Y') as data_estimativa_colheita
                    , a.id_cultivo
                    , b.nome as item_cultivo
                    , c.descricao as desc_terreno
                    , a.status
                FROM plantacao as a
                INNER JOIN item_cultivo as b ON b.id_cultivo = a.id_cultivo
                INNER JOIN terrenos as c ON c.id_terreno = a.id_terreno
                ORDER BY a.status DESC, a.dt_plantacao ASC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }
}