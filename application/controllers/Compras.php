<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compras extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Compras_model');
    }

    public function listaCompras()
    {
        $data['page'] = 'compras/listaCompras';
        $this->load->view('interface_projeto', $data);
    }

    public function getlistaComprasAjax()
    {
        $data = $this->Compras_model->getListaCompras();
        echo json_encode($data);
    }

    public function getlistaItensComprasAjax()
    {
        $id_compra = $this->input->get('id_compra');
        $data = $this->Compras_model->getlistaItensCompras($id_compra);
        echo json_encode($data);
    }

    public function cadCompras()
    {
        $data['page'] = 'compras/cadCompras';
        $this->load->view('interface_projeto', $data);
    }

    public function setSalvarCompra()
    {
    	$obj = $this->input->post('dados');
        $insertCompra = [];
        $insertCompra['id_fornecedor'] = $obj['id_fornecedor'];
        $insertCompra['nf'] = $obj['nf'];
        $insertCompra['dt_compra'] = $obj['dt_compra'];
        $id_compra = $this->Compras_model->createCompras($insertCompra);
        foreach ($obj['itens'][0] as $key => $value) {
            $insertItemCompra = [];
            $insertItemCompra['id_compra'] = $id_compra;
            $insertItemCompra['id_produto'] = $value['id_produto'];
            $insertItemCompra['qtde'] = $value['qtde'];
            $insertItemCompra['valor_uni'] = $value['valor_uni'];
            $insertItemCompra['valor_total'] = $value['valor_total'];
            $this->Compras_model->createItemCompras($insertItemCompra);
        }
    }

}
