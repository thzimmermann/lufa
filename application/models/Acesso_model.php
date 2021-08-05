<?php
class Acesso_model extends CI_Model
{
    public function getConfirmacaoLogin($dados)
    {
        $sql = "SELECT id_usuario
                    , nome
                    , usuario_login
                    , email
                    , telefone
                FROM usuarios
                WHERE usuario_login = '{$dados['usuario']}'
                AND senha =  '{$dados['senha']}'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
}