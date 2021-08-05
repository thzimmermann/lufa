 <?php if ( ! defined('BASEPATH')) exit('Não é permitido acesso direto ao Script (Multas_model)');

class Multas_model extends CI_Model {

    /**
     * @author  Zimmermann
     */

    public function getListaMultas()
    {
        $sql = "SELECT a.i_multa
            , (SELECT b.placa||' / '||b.marca_modelo
                FROM dba.cv_veiculos as b
                WHERE b.i_empresa = a.i_empresa
                AND b.i_patrimonio = a.i_patrimonio) as veiculo
            , DATEFORMAT(a.dt_multa,'DD/MM/YYYY') AS dt_multa
            , DATEFORMAT(a.dt_multa,'HH:MM') AS hr_multa
            , (SELECT b.nome||' - '||b.uf
                FROM dba.ge_cidades_ibge AS b
                WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS cidade
            , a.cod_infracao
            , a.pontos
            , a.gravidade
            , (CASE a.status
                        WHEN 'A' THEN 'Aberto'
                        WHEN 'V' THEN 'Verificando'
                        WHEN 'F' THEN 'Fechado'
                       ELSE '' END) as status
            , isnull(a.arquivo, '') as arquivo
            , IF isnull(a.i_usuario_multa,'')<>'' then
                        (SELECT nome  FROM dba.ge_usuarios AS y
                            WHERE y.i_usuario = a.i_usuario_multa)
                        ELSE ''ENDIF as nome_usuario
            FROM dba.cv_veiculos_multas as a
            WHERE a.i_empresa={$this->session->userdata('i_empresa')}
            ORDER BY a.dt_multa ASC, a.i_multa ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getListaMultasPendentes($status = '',$i_patrimonio= '')
    {

        $filtro = '';
        if ($status != ''){
            $filtro = "AND a.status !='{$status}'";
        }
        if ($i_patrimonio != ''){
            $filtro .= "AND a.i_patrimonio={$i_patrimonio}";
        }

        $sql = "SELECT a.i_multa
                    , a.i_patrimonio
                    , (SELECT b.placa||' / '||b.marca_modelo
                        FROM dba.cv_veiculos as b
                        WHERE b.i_empresa = a.i_empresa
                        AND b.i_patrimonio = a.i_patrimonio) as veiculo
                    , DATEFORMAT(a.dt_multa,'DD/MM/YYYY') AS dt_multa
                    , DATEFORMAT(a.dt_multa,'HH:MM') AS hr_multa
                    , (SELECT b.nome||' - '||b.uf
                        FROM dba.ge_cidades_ibge AS b
                        WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS cidade
                    , a.cod_infracao
                    , a.pontos
                    , a.gravidade
                    , a.i_usuario_multa
                    , isnull(a.status,'A') as status
                    , IF isnull(a.i_usuario_multa,'')<>'' then
                        (SELECT nome  FROM dba.ge_usuarios AS y
                            WHERE y.i_usuario = a.i_usuario_multa)
                        ELSE ''ENDIF as nome_usuario
                    , (SELECT (SELECT y.i_usuario
                                FROM dba.ge_usuarios AS y
                                WHERE y.i_usuario = z.i_usuario)
                        FROM dba.cv_alocacoes AS z
                        LEFT JOIN dba.cv_veiculos AS b ON b.i_empresa= z.i_empresa AND b.i_patrimonio = z.i_patrimonio
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND a.dt_multa BETWEEN z.dt_alocacao_ini
                                        AND z.dt_alocacao_fim) AS i_usuario_sugerido
                    , (SELECT (SELECT y.nome
                                FROM dba.ge_usuarios AS y
                                WHERE y.i_usuario = z.i_usuario)
                            FROM dba.cv_alocacoes AS z
                            LEFT JOIN dba.cv_veiculos AS b ON b.i_empresa= z.i_empresa AND b.i_patrimonio = z.i_patrimonio
                            WHERE b.i_patrimonio = a.i_patrimonio
                            AND a.dt_multa BETWEEN z.dt_alocacao_ini AND z.dt_alocacao_fim) AS nome_usuario_sugerido
                FROM dba.cv_veiculos_multas AS a
                WHERE a.i_empresa={$this->session->userdata('i_empresa')}
                {$filtro}
                ORDER BY a.i_multa ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function setSalvarMultas($dados)
    {
        return $this->db->insert('dba.cv_veiculos_multas', $dados);
    }
}

/* End of file Multas_model.php */
/* Location: ./app/models/Multas_model.php */