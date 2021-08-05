<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estoque extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Estoque_model');
    }

    public function controleEstoque()
    {
        $data['page'] = 'estoque/controleEstoque';
        $this->load->view('interface_projeto', $data);
    }

    public function getProdutosEstoqueAjax()
    {
        $dados = $this->Estoque_model->getProdutosEstoque();
        foreach ($dados as $key => $value) {
            $dados[$key]['estoque_atual'] = (($value['qtde_compras']+$value['qtde_acrescidos']) - ($value['qtde_vendas'] + $value['qtde_utilizacoes']));
        }
        echo json_encode($dados);
    }

    public function getEstoqueProduto($id_produto)
    {
        $dados = $this->Estoque_model->getEstoqueProduto($id_produto);
        $qtde_max = (($dados['qtde_compras']+$dados['qtde_acrescidos']) - $dados['qtde_utilizacoes']);
        echo json_encode(["qtde"=>$qtde_max]);
    }

    public function listaMovimentacoes()
    {
        $data['page'] = 'estoque/listaMovimentacoes';
        $this->load->view('interface_projeto', $data);
    }

    public function getMovimentacoesProduto($id_produto)
    {
        $dados = $this->Estoque_model->getMovimentacoesProduto($id_produto);
        echo json_encode($dados);
    }

    public function getAnoMovimentacoes($id_produto)
    {
        $dados = $this->Estoque_model->getMovimentacoesProduto($id_produto);
        $anos = [];
        $anos[] = ['ano' => '', 'label' => 'Todos'];
        foreach ($dados as $key => $value) {
            $anos[$value['filtro_ano']] = ['ano' => $value['filtro_ano'], 'label' => $value['filtro_ano']];
        }
        echo json_encode($anos);
    }

    public function relatorioMovimentacoesProduto()
    {
        $id_produto = $this->input->get('id_produto');
        $tipo = $this->input->get('tipo');
        $data['lista'] = [];
        $data['lista'] = $this->Estoque_model->getMovimentacoesProduto($id_produto);
        $data['cabecalho'] = 'includes/relatorio_cabecalho';
        $data['cabecalho_titulo'] = 'Relatório de Movimentações';
        $data['pagina'] = 'estoque/relatorioMovimentacoesProduto';
        $data['relatorio'] = $tipo;
        if ($tipo == 'E') {
            $this->load->view($data['pagina'], $data);
        } else {
            $this->load->helper('mpdf');
            $html = $this->load->view($data['pagina'], $data, true);
            pdf_create($html);
        }
    }



}