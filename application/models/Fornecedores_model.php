<?php
class Fornecedores_model extends CI_Model
{
    public function getListaFornecedores()
    {
        $sql = "SELECT a.id_fornecedor
                    , a.nome
                    , a.email
                    , a.telefone
                    , a.endereco
                    , a.tipo_empresa
                    , a.cnpj_cpf
                    , a.id_cidade
                    , (select b.nome from cidades as b WHERE b.id_cidade = a.id_cidade) as cidade
                    , (select b.uf from cidades as b WHERE b.id_cidade = a.id_cidade) as uf
                FROM fornecedor as a";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function createFornecedor($obj)
    {
        $this->db->insert('fornecedor', $obj);
        return $this->db->insert_id('id_fornecedor');
    }

    public function getFornecedor($id)
    {
        $sql = "SELECT a.id_fornecedor
                    , a.nome
                    , a.email
                    , a.telefone
                    , a.endereco
                    , a.tipo_empresa
                    , a.cnpj_cpf
                    , a.id_cidade
                    , (select b.nome from cidades as b WHERE b.id_cidade = a.id_cidade) as cidade
                    , (select b.uf from cidades as b WHERE b.id_cidade = a.id_cidade) as uf
                FROM fornecedor as a
                WHERE a.id_fornecedor = {$id}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function updateFornecedor($where, $obj)
    {
        $this->db->where('id_fornecedor', $obj['id_fornecedor']);
        return $this->db->update('fornecedor', $obj);
    }

    public function getFornecedorFiltro($busca)
    {
        $filtro = 'WHERE 1=1';
        if (trim($busca) != ''){
            $filtro .= " AND nome like '%{$busca}%'";
        }

        $sql = "SELECT id_fornecedor
                    , nome
                FROM fornecedor
                {$filtro}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }
}