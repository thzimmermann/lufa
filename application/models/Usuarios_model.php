<?php
class Usuarios_model extends CI_Model
{
    public function getlistaUsuarios()
    {
        $sql = "SELECT id_usuario
                    , nome
                    , usuario_login
                    , email
                    , telefone
                FROM usuarios";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function createUsuario($obj)
    {
        $this->db->insert('usuarios', $obj);
        return $this->db->insert_id('id_usuario');
    }

    public function updateUsuario($where, $obj)
    {
        $this->db->where('id_usuario', $obj['id_usuario']);
        return $this->db->update('usuarios', $obj);
    }

    public function getUsuario($id)
    {
        $sql = "SELECT id_usuario
                    , nome
                    , usuario_login
                    , senha
                    , email
                    , telefone
                FROM usuarios
                WHERE id_usuario = {$id}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }
}