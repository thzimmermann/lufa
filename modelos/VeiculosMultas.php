<?php if ( ! defined('BASEPATH')) exit('Não é permitido acesso direto ao Script (Acesso)');

class VeiculosMultas extends CI_Controller {
    /**
    * @author Zimmermann
    */
    public function __construct()
    {
        parent::__construct();
        $this->Usuarios_model->logado();
        $this->load->model("Veiculos_model");
        $this->load->model("Multas_model");
        $this->load->model("Portal_menus_model");
    }

    public function listarMultasVeiculos()
    {
        $data['veiculos'] = $this->Veiculos_model->getListaCarros();
        foreach ($data['veiculos'] as $key => $a) {
            $data['veiculos'][$key]['placa'] =
                substr($a['placa'], 0, 3)
                .'-'.
                substr($a['placa'], 3, strlen($a['placa']));
        }
        $data["titulo_interface"] = $this->Portal_menus_model->get_interface_titulo(__CLASS__, __FUNCTION__, "");
        $data["pagina"] = "veiculos/listarMultasVeiculos";
        $this->load->view($this->session->userdata("interface"), $data);
    }

    public function getVeiculosAjax()
    {
        $busca = $this->input->get('busca');
        $veiculos = $this->Veiculos_model->getListaCarros($busca);
        $json = array();
        foreach ($veiculos as $v) {
            $json[] = array(
                'id' => $v['i_patrimonio'],
                'text' =>
                substr($v['placa'], 0, 3)
                .'-'.substr($v['placa'], 3, strlen($v['placa']))
                .' / '.$this->satc->utf8_encode($v['nome'])
            );
        }
        echo json_encode($json);
    }

    public function getlistarMultasAjax()
    {
        $listarMultas = $this->Multas_model->getListaMultas();
        foreach ($listarMultas as $key => $a) {
            $listarMultas[$key]['veiculo'] =
                substr($a['veiculo'], 0, 3)
                .' - '.
                substr($a['veiculo'], 3, strlen($a['veiculo']));

            switch ($listarMultas[$key]['gravidade']) {
                case 'V':
                    $listarMultas[$key]['gravidade'] = utf8_decode('Gravíssima');
                    break;
                case 'G':
                    $listarMultas[$key]['gravidade'] = 'Grave';
                    break;
                case 'M':
                    $listarMultas[$key]['gravidade'] = utf8_decode('Média');
                    break;
                case 'L':
                    $listarMultas[$key]['gravidade'] = 'Leve';
                    break;
            }
        }
        $json = $this->satc->serializeDados($listarMultas);
        echo json_encode($json);
    }

    public function getCidadesAjax()
    {
        $this->load->model("Cidades_model");
        $busca = $this->input->get('busca');
        $cidades = $this->Cidades_model->getCidadesEstados($busca);
        $json = array();
        foreach ($cidades as $v) {
            $json[] = array(
                'id' => $v['i_cidade_ibge'],
                'text' => $this->satc->utf8_encode($v['nome']).' - '.$v['uf'],
            );
        }
        echo json_encode($json);
    }

    public function setSalvarMultas()
    {
        $obj = $this->satc->post('multas');
        $obj['dt_multa'] = $this->satc->formata_data($obj['dt_multa'], 2).' '.$obj['hr_multa'];
        unset($obj['hr_multa']);
        $obj['dt_sistema'] = $this->satc->data_atual();
        $obj['status'] = 'A';
        $obj['i_usuario'] =  $this->session->userdata('i_usuario');
        $obj['i_empresa'] =  $this->session->userdata('i_empresa');
        $obj['i_multa'] = $this->Generico_model->get_Prox('i_multa', 'dba.cv_veiculos_multas');
        $this->Multas_model->setSalvarMultas($obj);
        echo 1;
    }

    public function getAnexosDownload()
    {
        $this->load->helper('download');
        $arquivo = $this->satc->get('arquivo');
        $dir = BASEDIR_ARQUIVOS_NERCU."/veiculos/multas";
        $data = file_get_contents("{$dir}/{$arquivo}");
        force_download($arquivo, $data, null);
    }
}