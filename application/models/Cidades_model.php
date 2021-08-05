<?php
class Cidades_model extends CI_Model
{
    public function getCidadesFiltro($busca)
    {
        $filtro = 'WHERE 1=1';
        if (trim($busca) != ''){
            $filtro .= " AND nome like '%{$busca}%'";
        }

        $sql = "SELECT id_cidade
                    , nome
                    , uf
                FROM cidades
                {$filtro}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }
}