<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fornecedores extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Fornecedores_model');
    }

    public function listaFornecedores()
    {
        $data['page'] = 'fornecedores/listaFornecedores';
        $this->load->view('interface_projeto', $data);
    }

    public function cadFornecedor()
    {
        $data['page'] = 'fornecedores/cadFornecedor';
        $this->load->view('interface_projeto', $data);
    }

    public function getlistaFornecedoresAjax()
    {
        $data = $this->Fornecedores_model->getListaFornecedores();
        echo json_encode($data);
    }

    public function getFornecedor()
    {
        $id = $this->input->get('id');
        $data = $this->Fornecedores_model->getFornecedor($id);
        echo json_encode($data);
    }

    public function setSalvarFornecedor()
    {
        $obj = $this->input->post('dados');

        if(isset($obj['id_fornecedor'])) {
            $where = ['id_fornecedor' => $obj['id_fornecedor']];
            unset($obj['cidade']);
            unset($obj['uf']);
            $this->Fornecedores_model->updateFornecedor($where, $obj);
        } else {
            $this->Fornecedores_model->createFornecedor($obj);
            /*$obj['id_fornecedor'] = $id;*/
        }
    }

    public function getFornecedorFiltro()
    {
        $busca = $this->input->get('busca');
        $fornecedores = $this->Fornecedores_model->getFornecedorFiltro($busca);
        $options = [];
        if (count($fornecedores)>0) {
            foreach ($fornecedores as $fornecedor) {
                $options[] = [
                    'id' => $fornecedor['id_fornecedor'],
                    'text' => "{$fornecedor['nome']}"
                ];
            }
        }
        echo json_encode($options);
    }
}
