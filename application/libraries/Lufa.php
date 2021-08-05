<?php
class Lufa
{
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function response($data, $total = 0)
    {
        echo json_encode([
            'count' => $total==0 ? count($data) : $total,
            'data' => $data
        ]);
    }

    public function input()
    {
        $postdata = file_get_contents('php://input');
        $request = json_decode($postdata);
        return $request;
    }

    public function getInputAngular()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        return (array) $request;
    }

    public function dataAtual()
    {
        $padrao = $this->CI->config->item('log_date_format');
        return date($padrao);
    }

    public function enviar_email($para, $assunto, $texto, $cc = '', $cco = array())
    {
        $this->CI->load->library('My_phpmailer');
        $destino_nome = explode('@', $para);
        $destino_nome = $destino_nome[0];
        $this->CI->my_phpmailer->enviarEmail(
            $destino_nome,
            $para,
            $assunto,
            $texto,
            $this->CI->session->userdata('suporte'),
            '',
            $cc,
            $cco
        );
    }

    function geraTokenAleatorio($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
    {
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
        $retorno = '';
        $caracteres = '';
        $caracteres .= $lmin;
        if ($maiusculas) $caracteres .= $lmai;
        if ($numeros) $caracteres .= $num;
        if ($simbolos) $caracteres .= $simb;
        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand-1];
        }
        return $retorno;
    }

    /**
        * @author Zimmermann
        * tipo 1 - aaaa-mm-dd -> dd/mm/aaaa
        * tipo 2 - dd/mm/aaaa -> aaaa-mm-dd
        * tipo 3 - dd/mm/aaaa hh:mm:ss -> aaaa-mm-dd hh:mm:ss
        * tipo 4 - aaaa-mm-dd hh:mm:ss -> dd/mm/aaaa hh:mm:ss
        * tipo 5 - dd/mm/aaaa -> aaaamm
        * tipo 6 - dd/mm/aaaa -> mm/dd/aaaa
        * tipo 7 - dd/mm/aaaa hh:mm:ss -> dd/mm/aaaa hh:mm:ss
        * tipo 8 - aaaa-mm-dd hh:mm:ss -> hh:mm
        * tipo 9 - aaaa-mm-dd hh:mm -> aaaamm
        * tipo 10 - aaaa-mm-dd hh:mm -> aaaa-mm-dd
        * mostra_hora - 1 sim 0 nao
    */
    public function formata_data($data, $tipo, $mostra_hora = 0)
    {
        if (!empty($data)) {
            if ($tipo==1) {
                $p_dt = explode('-', $data);
                return $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
            } elseif ($tipo==2) {
                $p_dt = explode('/', $data);
                $new_data = $p_dt[2].'-'.$p_dt[1].'-'.$p_dt[0];
                if ($mostra_hora) {
                    $hr = '00:00';
                    $new_data .= ' '.$hr;
                }
                return $new_data;
            } elseif ($tipo==3) {
                $dt = explode(' ', $data);
                $data = $dt[0];
                $p_dt = explode('/', $data);
                $new_data = $p_dt[2].'-'.$p_dt[1].'-'.$p_dt[0];
                if ($mostra_hora) {
                    $hr = $dt[1];
                    $new_data .= ' '.$hr;
                }
                return $new_data;
            } elseif ($tipo==4) {
                $dt = explode(' ', $data);
                $data = $dt[0];
                $p_dt = explode('-', $data);
                $new_data = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0];
                if ($mostra_hora) {
                    $hr = $dt[1];
                    $new_data .= ' '.$hr;
                }
                return $new_data;
            } elseif ($tipo==5) {
                $p_dt = explode('/', $data);
                return $p_dt[2].$p_dt[1];
            } elseif ($tipo==6) {
                $p_dt = explode('/', $data);
                return $p_dt[1].'/'.$p_dt[0].'/'.$p_dt[2];
            } elseif ($tipo==7) {
                $dt = explode(' ', $data);
                $data = $dt[0];
                $p_dt = explode('-', $data);
                $hr = explode('.', $dt[1]);
                $new_data = $p_dt[2].'/'.$p_dt[1].'/'.$p_dt[0].' '.$hr[0];
                return $new_data;
            } elseif ($tipo==8) {
                $dt = explode(' ', $data);
                $data = $dt[0];
                $p_dt = explode('-', $data);
                $hr = explode(':', $dt[1]);
                $new_data = $hr[0].':'.$hr[1];
                return $new_data;
            } elseif ($tipo==9) {
                $dt = explode(' ', $data);
                $data = $dt[0];
                $p_dt = explode('-', $data);
                $new_data = $p_dt[0].''.$p_dt[1];
                return $new_data;
            } elseif ($tipo==10) {
                $dt = explode(' ', $data);
                $new_data = $dt[0];
                return $new_data;
            }
        }
    }
}
