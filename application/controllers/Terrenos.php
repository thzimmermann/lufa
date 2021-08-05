<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terrenos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Terrenos_model');
    }

    public function listaTerrenos()
    {
        $data['page'] = 'terrenos/listaTerrenos';
        $this->load->view('interface_projeto', $data);
    }

    public function getListaTerrenosAjax()
    {
        $data = $this->Terrenos_model->getListaTerrenos();
        echo json_encode($data);
    }

    public function setSalvarUtilizacao()
    {
        $obj = $this->input->post('dados');
        $this->Terrenos_model->createUtilizacao($obj);
    }

    public function getTerrenoFiltro()
    {
        $busca = $this->input->get('busca');
        $terrenos = $this->Terrenos_model->getTerrenoFiltro($busca);
        $options = [];
        if (count($terrenos)>0) {
            foreach ($terrenos as $terreno) {
                $options[] = [
                    'id' => $terreno['id_terreno'],
                    'text' => "{$terreno['descricao']}"
                ];
            }
        }
        echo json_encode($options);
    }
}