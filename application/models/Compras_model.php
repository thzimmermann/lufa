<?php
class Compras_model extends CI_Model
{
    public function getListaCompras()
    {
        $sql = "SELECT a.id_compra
                    , a.nf
                    , DATE_FORMAT(a.dt_compra, '%d/%m/%Y') as dt_compra
                    , (select b.nome from fornecedor as b WHERE b.id_fornecedor = a.id_fornecedor) as nome_fornecedor
                    , (select sum(b.valor_total) from itens_compras as b WHERE b.id_compra = a.id_compra) as valor_total
                FROM compras as a
                WHERE status = 'C'";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function createCompras($obj)
    {
        $this->db->insert('compras', $obj);
        return $this->db->insert_id('id_compra');
    }

    public function createItemCompras($obj)
    {
        $this->db->insert('itens_compras', $obj);
        return $this->db->insert_id('id_itens_compras');
    }

    public function getlistaItensCompras($id_compra)
    {
        $sql = "SELECT a.id_itens_compras
                    , a.id_compra
                    , a.id_produto
                    , (select b.nome from produtos as b WHERE b.id_produto = a.id_produto) as nome_produto
                    , a.qtde
                    , a.valor_uni
                    , a.valor_total
                FROM itens_compras as a
                WHERE a.id_compra = {$id_compra}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getGroupComprasFornecedor($id_fornecedor)
    {
        $sql = "SELECT GROUP_CONCAT(id_compra) as group_compras
                    , id_fornecedor
                FROM compras
                WHERE id_fornecedor = {$id_fornecedor}
                AND status = 'C'
                GROUP BY id_fornecedor";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        $query->free_result();
        return $result;
    }

    public function getGroupVendasFornecedor($id_fornecedor)
    {
        $sql = "SELECT GROUP_CONCAT(id_compra) as group_compras
                    , id_fornecedor
                FROM compras
                WHERE id_fornecedor = {$id_fornecedor}
                AND status = 'V'
                GROUP BY id_fornecedor";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        $query->free_result();
        return $result;
    }

    public function getTotalValorFornecedor($ids_compras = '')
    {
        $sql = "SELECT sum(valor_total) as vl_total
                FROM itens_compras
                WHERE id_compra IN ({$ids_compras})";
        $query = $this->db->query($sql);
        $result = $query->row()->vl_total;
        $query->free_result();
        return $result;
    }
}
?>