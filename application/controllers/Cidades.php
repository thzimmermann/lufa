<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cidades extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cidades_model');
    }

    public function getCidadesFiltro()
    {
        $busca = $this->input->get('busca');
        $cidades = $this->Cidades_model->getCidadesFiltro($busca);
        $options = [];
        if (count($cidades)>0) {
            foreach ($cidades as $cidade) {
                $options[] = [
                    'id' => $cidade['id_cidade'],
                    'text' => "{$cidade['nome']} - {$cidade['uf']}"
                ];
            }
        }
        echo json_encode($options);
    }

}
