<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acesso extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Acesso_model');
    }

    public function index()
    {
        $data['page'] = 'acesso/login';
        $this->load->view('interface', $data);
    }

    public function setAutenticacao()
    {
    	$dados = $this->lufa->getInputAngular();
        $dados['senha'] = md5(base64_decode($dados['senha']));
        $logado = $this->Acesso_model->getConfirmacaoLogin($dados);
        if (count($logado) > 0) {
            echo 1;
        } else {
            echo 0;
        }

    }

}
