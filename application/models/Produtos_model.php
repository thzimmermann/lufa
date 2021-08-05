<?php
class Produtos_model extends CI_Model
{
    public function getlistaProdutos()
    {
        $sql = "SELECT a.id_produto
                    , a.nome
                    , a.estoque_minimo
                    , a.estoque_maximo
                    , a.id_tipo_produto
                    , (select b.nome from tipo_produto as b WHERE b.id_tipo_produto = a.id_tipo_produto) as tipo_produto
                FROM produtos as a";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getlistaTiposProdutos()
    {
        $sql = "SELECT a.id_tipo_produto
                    , a.nome
                FROM tipo_produto as a";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function createProduto($obj)
    {
        $this->db->insert('produtos', $obj);
        return $this->db->insert_id('id_produto');
    }

    public function createTipoProduto($obj)
    {
        $this->db->insert('tipo_produto', $obj);
        return $this->db->insert_id('id_tipo_produto');
    }

    public function updateProduto($where, $obj)
    {
        $this->db->where('id_produto', $obj['id_produto']);
        return $this->db->update('produtos', $obj);
    }

    public function updateTipoProduto($where, $obj)
    {
        $this->db->where('id_tipo_produto', $obj['id_tipo_produto']);
        return $this->db->update('tipo_produto', $obj);
    }

    public function getTipoProdutoFiltro($busca)
    {
        $filtro = 'WHERE 1=1';
        if (trim($busca) != ''){
            $filtro .= " AND nome like '%{$busca}%'";
        }

        $sql = "SELECT id_tipo_produto
                    , nome
                FROM tipo_produto
                {$filtro}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getProduto($id)
    {
        $sql = "SELECT a.id_produto
                    , a.nome
                    , a.estoque_minimo
                    , a.estoque_maximo
                    , a.id_tipo_produto
                    , (select b.nome from tipo_produto as b WHERE b.id_tipo_produto = a.id_tipo_produto) as tipo_produto
                FROM produtos as a
                WHERE a.id_produto = {$id}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function getTipoProduto($id)
    {
        $sql = "SELECT a.id_tipo_produto
                    , a.nome
                FROM tipo_produto as a
                WHERE a.id_tipo_produto = {$id}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function getProdutosFiltro($busca)
    {
        $filtro = 'WHERE 1=1';
        if (trim($busca) != ''){
            $filtro .= " AND nome like '%{$busca}%'";
        }

        $sql = "SELECT id_produto
                    , nome
                FROM produtos
                {$filtro}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }
}