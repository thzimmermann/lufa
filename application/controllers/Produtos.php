<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produtos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produtos_model');
    }

    public function listaProdutos()
    {
        $data['page'] = 'produtos/listaProdutos';
        $this->load->view('interface_projeto', $data);
    }

    public function getlistaProdutosAjax()
    {
        $data = $this->Produtos_model->getlistaProdutos();
        echo json_encode($data);
    }

    public function getProduto()
    {
        $id = $this->input->get('id');
        $data = $this->Produtos_model->getProduto($id);
        echo json_encode($data);
    }

    public function cadProdutos()
    {
        $data['page'] = 'produtos/cadProdutos';
        $this->load->view('interface_projeto', $data);
    }

    public function cadTipoProdutos()
    {
        $data['page'] = 'produtos/cadTipoProdutos';
        $this->load->view('interface_projeto', $data);
    }

    public function listaTiposProdutos()
    {
        $data['page'] = 'produtos/listaTiposProdutos';
        $this->load->view('interface_projeto', $data);
    }

    public function getTiposProdutosAjax()
    {
        $data = $this->Produtos_model->getlistaTiposProdutos();
        echo json_encode($data);
    }

    public function getTipoProduto()
    {
        $id = $this->input->get('id');
        $data = $this->Produtos_model->getTipoProduto($id);
        echo json_encode($data);
    }    

    public function getTipoProdutoFiltro()
    {
        $busca = $this->input->get('busca');
        $tipo_produtos = $this->Produtos_model->getTipoProdutoFiltro($busca);
        $options = [];
        if (count($tipo_produtos)>0) {
            foreach ($tipo_produtos as $tipo_produto) {
                $options[] = [
                    'id' => $tipo_produto['id_tipo_produto'],
                    'text' => "{$tipo_produto['nome']}"
                ];
            }
        }
        echo json_encode($options);
    }


    public function setSalvarTipoProdutos()
    {
        $obj = $this->input->post('dados');
        if(isset($obj['id_tipo_produto'])) {
            $where = ['id_tipo_produto' => $obj['id_tipo_produto']];
            $this->Produtos_model->updateTipoProduto($where, $obj);
        } else {
            $this->Produtos_model->createTipoProduto($obj);
        }
    }

    public function setSalvarProdutos()
    {
        $obj = $this->input->post('dados');
        if(isset($obj['id_produto'])) {
            $where = ['id_produto' => $obj['id_produto']];
            unset($obj['tipo_produto']);
            $this->Produtos_model->updateProduto($where, $obj);
        } else {
            $this->Produtos_model->createProduto($obj);
        }
    }

    public function getProdutosFiltro()
    {
        $busca = $this->input->get('busca');
        $produtos = $this->Produtos_model->getProdutosFiltro($busca);
        $options = [];
        if (count($produtos)>0) {
            foreach ($produtos as $produto) {
                $options[] = [
                    'id' => $produto['id_produto'],
                    'text' => "{$produto['nome']}"
                ];
            }
        }
        echo json_encode($options);
    }

}