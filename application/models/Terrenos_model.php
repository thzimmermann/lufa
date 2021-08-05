<?php
class Terrenos_model extends CI_Model
{
    public function getListaTerrenos()
    {
        $sql = "SELECT a.id_terreno
                    , a.descricao
                    , a.hectares
                FROM terrenos as a";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function createTerrenos($obj)
    {
        $this->db->insert('terrenos', $obj);
        return $this->db->insert_id('id_terreno');
    }

    public function getTerrenoFiltro($busca)
    {
        $filtro = 'WHERE 1=1';
        if (trim($busca) != ''){
            $filtro .= "AND descricao like '%{$busca}%'";
        }

        $sql = "SELECT id_terreno
                    , descricao
                    , hectares
                FROM terrenos as a
                {$filtro}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }
}
?>