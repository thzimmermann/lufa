<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuarios_model');
    }

    public function listaUsuarios()
    {
        $data['page'] = 'usuarios/listaUsuarios';
        $this->load->view('interface_projeto', $data);
    }

    public function cadUsuarios()
    {
        $data['page'] = 'usuarios/cadUsuarios';
        $this->load->view('interface_projeto', $data);
    }

    public function getUsuario()
    {
        $id = $this->input->get('id');
        $data = $this->Usuarios_model->getUsuario($id);
        echo json_encode($data);
    }

    public function getlistaUsuariosAjax()
    {
        $data = $this->Usuarios_model->getlistaUsuarios();
        echo json_encode($data);
    }

    public function setSalvarUsuario()
    {
        $obj = $this->input->post('dados');

        if(isset($obj['id_usuario'])) {
            $where = ['id_usuario' => $obj['id_usuario']];
            $this->Usuarios_model->updateUsuario($where, $obj);
        } else {
            $this->Usuarios_model->createUsuario($obj);
        }
    }
}