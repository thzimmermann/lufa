<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plantacao extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Plantacao_model');
    }

    public function listaPlantacao()
    {
        $data['page'] = 'plantacao/listaPlantacao';
        $this->load->view('interface_projeto', $data);
    }

    public function cadPlantacao()
    {
        $data['page'] = 'plantacao/cadPlantacao';
        $this->load->view('interface_projeto', $data);
    }

    public function gerenciarPlantacao()
    {
        $data['page'] = 'plantacao/gerenciadorPlantacao';
        $this->load->view('interface_projeto', $data);
    }

    public function getlistaPlantacoesAjax()
    {
        $data = $this->Plantacao_model->getListaPlantacoes();
        echo json_encode($data);
    }

    public function getPlantacao()
    {
        $id = $this->input->get('id');
        $data = $this->Plantacao_model->getPlantacao($id);
        echo json_encode($data);
    }

    public function getPlantacaoFiltro()
    {
        $busca = $this->input->get('busca');
        $plantacoes = $this->Plantacao_model->getPlantacaoFiltro($busca);
        $options = [];
        if (count($plantacoes)>0) {
            foreach ($plantacoes as $plantacao) {
                $options[] = [
                    'id' => $plantacao['id_plantacao'],
                    'text' => "{$plantacao['item_cultivo']} - {$plantacao['desc_terreno']}"
                ];
            }
        }
        echo json_encode($options);
    }

    public function getItemColheitaFiltro()
    {
        $busca = $this->input->get('busca');
        $itens_cultivo = $this->Plantacao_model->getItemColheitaFiltro($busca);
        $options = [];
        if (count($itens_cultivo)>0) {
            foreach ($itens_cultivo as $cultivo) {
                $options[] = [
                    'id' => $cultivo['id_cultivo'],
                    'text' => "{$cultivo['nome']}"
                ];
            }
        }
        echo json_encode($options);
    }

    public function setSalvarPlantacao()
    {
        $obj = $this->input->post('dados');
        $obj['dt_plantacao'] = $this->lufa->formata_data($obj['dt_plantacao'], 2);
        $obj['dt_estimativa_colheita'] = $this->lufa->formata_data($obj['dt_estimativa_colheita'], 2);
        if(isset($obj['id_plantacao'])) {
            $where = ['id_plantacao' => $obj['id_plantacao']];
            unset($obj['item_cultivo']);
            unset($obj['desc_terreno']);
            unset($obj['data_plantacao']);
            unset($obj['data_estimativa_colheita']);
            $this->Plantacao_model->updatePlantacao($where, $obj);
        } else {
            $this->Plantacao_model->createPlantacao($obj);
            /*$obj['id_plantacao'] = $id;*/
        }
    }

    public function getPlantacaoGeral()
    {
        $plantacoes = $this->Plantacao_model->getPlantacaoGeral();
        $options = [];
        if (count($plantacoes)>0) {
            foreach ($plantacoes as $plantacao) {
                $options[] = [
                    'id' => $plantacao['id_plantacao'],
                    'text' => "{$plantacao['item_cultivo']} - {$plantacao['desc_terreno']} ({$plantacao['data_plantacao']})"
                ];
            }
        }
        echo json_encode($options);
    }

    public function getlistaFornecedoresRazaoAjax()
    {
        $this->load->model('Fornecedores_model');
        $this->load->model('Compras_model');
        $fornecedores = $this->Fornecedores_model->getListaFornecedores();
        foreach ($fornecedores as $key => $value) {
            $compras = $this->Compras_model->getGroupComprasFornecedor($value['id_fornecedor']);
            if (isset($compras)) {
                $fornecedores[$key]['vl_total_compras'] = $this->Compras_model->getTotalValorFornecedor($compras['group_compras']);
            } else {
                $fornecedores[$key]['vl_total_compras'] = 0;
            }

            $vendas = $this->Compras_model->getGroupVendasFornecedor($value['id_fornecedor']);
            if (isset($vendas)) {
                $fornecedores[$key]['vl_total_vendas'] = $this->Compras_model->getTotalValorFornecedor($vendas['group_compras']);
            } else {
                $fornecedores[$key]['vl_total_vendas'] = 0;
            }
        }
        echo json_encode($fornecedores);
    }
}