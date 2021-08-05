<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Vendas_model');
    }

    public function listaVendas()
    {
        $data['page'] = 'vendas/listaVendas';
        $this->load->view('interface_projeto', $data);
    }

    public function getlistaVendasAjax()
    {
        $data = $this->Vendas_model->getListaVendas();
        echo json_encode($data);
    }

    public function getlistaItensVendasAjax()
    {
        $id_compra = $this->input->get('id_venda');
        $data = $this->Vendas_model->getlistaItensVendas($id_compra);
        echo json_encode($data);
    }

    public function cadVendas()
    {
        $data['page'] = 'vendas/cadVendas';
        $this->load->view('interface_projeto', $data);
    }

    public function setSalvarVenda()
    {
        $obj = $this->input->post('dados');
        $insertCompra = [];
        $insertCompra['id_fornecedor'] = $obj['id_fornecedor'];
        $insertCompra['nf'] = $obj['nf'];
        $insertCompra['dt_compra'] = $obj['dt_compra'];
        $insertCompra['status'] = 'V';
        $id_compra = $this->Vendas_model->createVendas($insertCompra);
        foreach ($obj['itens'][0] as $key => $value) {
            $insertItemCompra = [];
            $insertItemCompra['id_compra'] = $id_compra;
            $insertItemCompra['id_produto'] = $value['id_produto'];
            $insertItemCompra['qtde'] = $value['qtde'];
            $insertItemCompra['valor_uni'] = $value['valor_uni'];
            $insertItemCompra['valor_total'] = $value['valor_total'];
            $this->Vendas_model->createItemVendas($insertItemCompra);
        }
    }

}
