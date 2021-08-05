<?php
class Utilizacoes_model extends CI_Model
{
    public function createUtilizacao($obj)
    {
        $this->db->insert('utilizacoes_produtos', $obj);
        return $this->db->insert_id('id_utilizacoes_produto');
    }

    public function getUtilizacoesPlantacao()
    {
        $sql = "";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
