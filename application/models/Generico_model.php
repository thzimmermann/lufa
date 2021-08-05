<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generico_model extends CI_Model {

    function logado()
    {
        if (!($this->session->userdata('logado')==TRUE)) {
            redirect('');
        }
    }

}

/* End of file Generico_model.php */
/* Location: ./application/models/Generico_model.php */