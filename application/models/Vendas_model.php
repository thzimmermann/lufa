<?php
class Vendas_model extends CI_Model
{
    public function getListaVendas()
    {
        $sql = "SELECT a.id_compra
                    , a.nf
                    , DATE_FORMAT(a.dt_compra, '%d/%m/%Y') as dt_compra
                    , (select b.nome from fornecedor as b WHERE b.id_fornecedor = a.id_fornecedor) as nome_fornecedor
                    , (select sum(b.valor_total) from itens_compras as b WHERE b.id_compra = a.id_compra) as valor_total
                FROM compras as a
                WHERE status = 'V'";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function createVendas($obj)
    {
        $this->db->insert('compras', $obj);
        return $this->db->insert_id('id_compra');
    }

    public function createItemVendas($obj)
    {
        $this->db->insert('itens_compras', $obj);
        return $this->db->insert_id('id_itens_compras');
    }

    public function getlistaItensVendas($id_compra)
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
}
?>