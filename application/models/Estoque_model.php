<?php
class Estoque_model extends CI_Model
{
    public function getProdutosEstoque()
    {
        $sql = "SELECT a.id_produto
                    , a.nome
                    , ifnull((select sum(b.qtde) from itens_compras as b inner join compras as c on c.id_compra = b.id_compra where b.id_produto = a.id_produto and c.status = 'C'), 0) as qtde_compras
                    , ifnull((select sum(b.qtde) from itens_compras as b inner join compras as c on c.id_compra = b.id_compra where b.id_produto = a.id_produto and c.status = 'V'), 0) as qtde_vendas
                    , ifnull((select sum(c.qtde) from utilizacoes_produtos as c where c.id_produto = a.id_produto and c.status = 'E'), 0) as qtde_acrescidos
                    , ifnull((select sum(c.qtde) from utilizacoes_produtos as c where c.id_produto = a.id_produto and c.status = 'S'), 0) as qtde_utilizacoes
                    , a.id_tipo_produto
                    , (select b.nome from tipo_produto as b WHERE b.id_tipo_produto = a.id_tipo_produto) as tipo_produto
                FROM produtos as a";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    public function getEstoqueProduto($id_produto)
    {
        $sql = "SELECT sum(a.qtde) as qtde_compras
                    , ifnull((select sum(c.qtde) from utilizacoes_produtos as c where c.id_produto = a.id_produto and c.status = 'E'), 0) as qtde_acrescidos
                    , ifnull((select sum(c.qtde) from utilizacoes_produtos as c where c.id_produto = a.id_produto and c.status = 'S'), 0) as qtde_utilizacoes
                FROM itens_compras as a
                INNER JOIN compras as b ON b.id_compra = a.id_compra and b.status = 'C'
                WHERE a.id_produto = {$id_produto}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function getMovimentacoesProduto($id_produto)
    {
        $sql = "SELECT a.qtde
                    , a.valor_uni
                    , a.valor_total
                    , b.status
                    , DATE_FORMAT(b.dt_compra, '%d/%m/%Y') as data
                    , b.id_plantacao
                    , ifnull((select d.descricao from plantacao as c inner join terrenos as d WHERE c.id_plantacao = b.id_plantacao and d.id_terreno = c.id_terreno), '') as desc_terreno
                    , ifnull((select d.nome from plantacao as c inner join item_cultivo as d WHERE c.id_plantacao = b.id_plantacao and d.id_cultivo = c.id_cultivo), '') as item_cultivo
                    , ifnull((select DATE_FORMAT(c.dt_plantacao, '%d/%m/%Y') from plantacao as c WHERE c.id_plantacao = b.id_plantacao), '') as dt_plantacao
                    , DAY(b.dt_compra) as dia
                    , MONTH(b.dt_compra) as mes
                    , YEAR(b.dt_compra) as filtro_ano
                    , 'I' as local
                FROM itens_compras as a
                INNER JOIN compras as b ON b.id_compra = a.id_compra
                WHERE a.id_produto = {$id_produto}
                UNION ALL
                SELECT a.qtde
                    , 0
                    , 0
                    , a.status
                    , DATE_FORMAT(a.dt_utilizacao, '%d/%m/%Y') as data
                    , a.id_plantacao
                    , ifnull((select b.descricao from plantacao as c inner join terrenos as b WHERE c.id_plantacao = a.id_plantacao and b.id_terreno = c.id_terreno), '') as desc_terreno
                    , ifnull((select b.nome from plantacao as c inner join item_cultivo as b WHERE c.id_plantacao = a.id_plantacao and b.id_cultivo = c.id_cultivo), '') as item_cultivo
                    , ifnull((select DATE_FORMAT(b.dt_plantacao, '%d/%m/%Y') from plantacao as b WHERE b.id_plantacao = a.id_plantacao), '') as dt_plantacao
                    , DAY(a.dt_utilizacao) as dia
                    , MONTH(a.dt_utilizacao) as mes
                    , YEAR(a.dt_utilizacao) as filtro_ano
                    , 'U' as local
                FROM utilizacoes_produtos as a
                WHERE a.id_produto = {$id_produto}
                ORDER BY filtro_ano DESC , mes DESC , dia DESC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}

