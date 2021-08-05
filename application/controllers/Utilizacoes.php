<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utilizacoes extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Utilizacoes_model');
    }

    public function cadUtilizacoes()
    {
        $data['page'] = 'utilizacoes/cadUtilizacoes';
        $this->load->view('interface_projeto', $data);
    }

    public function setSalvarUtilizacao()
    {
        $obj = $this->input->post('dados');
        $this->Utilizacoes_model->createUtilizacao($obj);
    }

    public function getUtilizacoesPlantacao()
    {
        $dados = $this->Utilizacoes_model->getUtilizacoesPlantacao();
    }
}