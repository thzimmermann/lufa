<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Veiculos_model extends CI_Model
{
    public function getVeiculosAgendados($usuario = '', $i_alocacao = '', $dt_inicial = '', $dt_final = '', $emergencial = '')
    {
        $filtro = ' 1=1 ';
        if (!trim($i_alocacao)=='') {
            $filtro .= " AND a.i_alocacao = {$i_alocacao}";
        }

        if (!trim($usuario)=='') {
            $filtro .= " AND a.usuario = '{$usuario}'";
        }

        if ((trim($dt_inicial) != '') && (trim($dt_final) != '')) {
            $filtro .= " AND DATEFORMAT(a.dt_alocacao_ini,'YYYY-MM-DD') >= '{$dt_inicial}'";
            $filtro .= " AND DATEFORMAT(a.dt_alocacao_fim,'YYYY-MM-DD') <= '{$dt_final}' ";
        }


        if (!trim($emergencial)=='') {
            $filtro .= " AND a.emergencial = '{$emergencial}' AND status_aprov = 'A'";
        }

        $sql = "SELECT b.i_empresa
                    , b.i_patrimonio
                    , b.marca_modelo
                    , b.combustivel
                    , b.placa
                    , (select z.nome from dba.ge_usuarios as z where z.nome_login = a.usuario) as solicitante
                    , (select z.nome from dba.ge_usuarios as z where z.i_usuario = a.i_usuario) as condutor
                    , a.i_alocacao
                    , DATEFORMAT(a.dt_alocacao_ini,'DD/MM/YYYY HH:MM') AS dt_alocacao_ini
                    , DATEFORMAT(a.dt_alocacao_fim,'DD/MM/YYYY HH:MM') AS dt_alocacao_fim
                    , a.acompanhante
                    , a.acompanhantes
                    , a.n_passageiros
                    , a.justificativa
                    , a.status
                    , a.status_aprov
                    , a.i_cidade_ibge
                    , a.dt_cancel
                    , a.motivo_cancel
                    , a.i_unidade
                    , a.emergencial
                    , (select top 1 z.nome from dba.ci_unidades as z where z.i_empresa = a.i_empresa and z.i_unidade = a.i_unidade) as nome_unidade
                    , if a.dt_alocacao_fim < now() then 1 else 0 end if AS status_tempo
                    , (SELECT COUNT(1)
                        FROM dba.cv_alocacoes as z
                        where z.i_patrimonio = a.i_patrimonio) AS total_alocacoes
                    , ISNULL((SELECT km_percorrida
                        FROM dba.cv_alocacoes_km as z
                        WHERE z.i_empresa = a.i_empresa
                        AND z.i_alocacao = a.i_alocacao),0) AS km_percorrida
                    ,  (select nome_login from dba.ge_usuarios as z where z.i_empresa = a.i_empresa and z.i_usuario = (SELECT TOP 1 i_usuario FROM dba.ge_usuarios_unidades as z
                                                WHERE z.i_empresa = a.i_empresa
                                                AND z.i_unidade = a.i_unidade
                                                and z.i_usuario <> 3
                                                and z.nivel = (select nivel_min_req - 1
                                                            from ci_unidades as s
                                                            WHERE s.i_empresa = a.i_empresa
                                                            AND s.i_unidade = a.i_unidade
                                                           )
                                                       order by i_usuario desc)) as nome_coordenador
                FROM dba.cv_alocacoes AS a
                LEFT JOIN dba.cv_veiculos AS b ON b.i_empresa= a.i_empresa AND b.i_patrimonio = a.i_patrimonio
                WHERE {$filtro}
                ORDER BY a.dt_alocacao_ini DESC";
        $query = $this->db->query($sql);
        $return = $query->result_array();
        $query->free_result();
        return $return;
    }

    public function search_condutor($i_usuario)
    {
        $sql = "SELECT 1 FROM dba.cv_condutores WHERE i_usuario = {$i_usuario}";
        $query = $this->db->query($sql);
        $retorno = ($query->num_rows()>0)?true:false;
        $query->free_result();
        return $retorno;
    }

    public function setAgendamento($insert)
    {
        $this->db->insert('dba.cv_alocacoes', $insert);
    }

    public function set_condutor($insert)
    {
        $this->db->insert('dba.cv_condutores', $insert);
    }

    public function setAlterarHorario($update)
    {
        $this->db->where('i_alocacao', $update['i_alocacao']);
        $this->db->where('i_empresa', $update['i_empresa']);
        $this->db->update('dba.cv_alocacoes', $update);
    }

    public function verifica_disponibilidade($data_retirada, $data_entrega, $i_patrimonio, $hr_inicio, $hr_fim)
    {
        $nova_retirada = $this->satc->formata_data($data_retirada, 2).' '.$hr_inicio;
        $nova_entrega = $this->satc->formata_data($data_entrega, 2).' '.$hr_fim;

        $sql = "SELECT 1 FROM dba.cv_alocacoes
                WHERE i_patrimonio = {$i_patrimonio}
                AND (('{$nova_retirada}' BETWEEN dt_alocacao_ini AND dt_alocacao_fim)
                OR ('{$nova_entrega}' BETWEEN dt_alocacao_ini AND dt_alocacao_fim))
                AND status_aprov IN ('A','P')";
        $query = $this->db->query($sql);
        $retorno = $query->num_rows()>0?1:0;
        $query->free_result();
        return $retorno;
    }

    public function setAprovacao($status, $motivo, $i_alocacao, $i_patrimonio,$obs)
    {
        $array_up = array();
        if (trim($motivo)!='') {
            $array_up = array('dt_cancel'=>$this->satc->data_atual());
        }

        if (trim($i_patrimonio)!='') {
            $array_up = array('i_patrimonio'=>$i_patrimonio);
        }

        $this->db->where('i_alocacao', $i_alocacao);
        $this->db->update('dba.cv_alocacoes', array('observacao'=>$obs,'status_aprov'=>$status, 'motivo_cancel'=>$motivo)+$array_up);
    }

    public function setAprovacaoVeiculos($dados)
    {
        if (isset($dados['motivo'])) {
            $dados['dt_cancel'] = $this->satc->data_atual();
        }
        $this->db->where('i_alocacao', $dados['i_alocacao']);
        $this->db->update('dba.cv_alocacoes', $dados);
    }

    public function getDadosCondutor($i_usuario)
    {
        $sql = "SELECT numero_carteira
                    ,  DATEFORMAT(dt_validade,'DD/MM/YYYY') AS dt_validade
                    ,  categoria
                    ,  observacao
                FROM dba.cv_condutores
                WHERE i_usuario = {$i_usuario}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function getDadosCondutorStatus($i_usuario)
    {
        $sql = "SELECT numero_carteira
                    ,  DATEFORMAT(dt_validade,'DD/MM/YYYY') AS data_validade
                    ,  categoria
                    , isnull(if (dt_validade<=NOW()) THEN 'VN' ELSE status ENDIF, '') AS status_cnh
                    , IF dt_validade<NOW()+30 THEN 1 ELSE 0 ENDIF vencida
                    ,  observacao
                FROM dba.cv_condutores
                WHERE i_usuario = {$i_usuario}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function getVeiculos()
    {
        $sql = "SELECT a.i_patrimonio
                    , a.marca_modelo||': '||a.placa AS nome
                    , a.km_minimo
                    , a.placa
                    , a.marca_modelo
                    , a.combustivel
                    , ISNULL((SELECT i_grupo FROM dba.cv_grupos AS b WHERE a.i_empresa = b.i_empresa AND a.i_grupo = b.i_grupo),0) AS i_grupo
                FROM dba.cv_veiculos as a key join dba.pa_patrimonios
                WHERE pa_patrimonios.status ='A'
                AND a.disponivel = 'S'";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getVeiculo($i_patrimonio)
    {
        $sql = "SELECT a.i_patrimonio
                    , a.marca_modelo||': '||a.placa AS nome
                    , a.km_minimo
                    , a.placa
                    , a.marca_modelo
                    , a.combustivel
                    , ISNULL((SELECT i_grupo FROM dba.cv_grupos AS b WHERE a.i_empresa = b.i_empresa AND a.i_grupo = b.i_grupo),0) AS i_grupo
                FROM dba.cv_veiculos as a key join dba.pa_patrimonios
                WHERE pa_patrimonios.status ='A'
                AND a.i_patrimonio = {$i_patrimonio}
                AND a.disponivel = 'S'";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function getVeiculosAll()
    {
        $dados = array();
        $sql = "SELECT a.i_empresa
                    , a.i_patrimonio
                    , a.marca_modelo||': '||a.placa AS nome
                    , a.km_minimo
                    , a.placa
                    , a.marca_modelo
                    , a.combustivel
                    , a.disponivel
                    , (SELECT nome FROM dba.cv_grupos AS b WHERE a.i_empresa = b.i_empresa AND a.i_grupo = b.i_grupo) AS categoria
                    , ISNULL((SELECT i_grupo FROM dba.cv_grupos AS b WHERE a.i_empresa = b.i_empresa AND a.i_grupo = b.i_grupo),0) AS i_grupo
                FROM dba.cv_veiculos as a key join dba.pa_patrimonios
                WHERE pa_patrimonios.status ='A'
                AND a.disponivel = 'S'";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        foreach ($retorno as $val) {
            $dados[$val['i_grupo']][] = $val;
        }
        return $dados;
    }

    public function getVeiculosAllGrupo($data_inicial, $data_final)
    {
        $dados = array();
        $sql = "SELECT a.i_empresa
                    , a.i_patrimonio
                    , a.placa||': '||a.marca_modelo AS nome
                    , a.km_minimo
                    , a.placa
                    , a.marca_modelo
                    , a.combustivel
                    , a.disponivel
                    , (SELECT nome FROM dba.cv_grupos AS b WHERE a.i_empresa = b.i_empresa AND a.i_grupo = b.i_grupo) AS categoria
                    , ISNULL((SELECT i_grupo FROM dba.cv_grupos AS b WHERE a.i_empresa = b.i_empresa AND a.i_grupo = b.i_grupo),0) AS i_grupo
                FROM dba.cv_veiculos as a key join dba.pa_patrimonios
                WHERE pa_patrimonios.status ='A'
                AND a.disponivel = 'S'
                AND NOT EXISTS(SELECT 1 FROM dba.CV_ALOCACOES as c
                            WHERE c.i_empresa = {$this->session->userdata('i_empresa')}
                            AND c.status_aprov = 'A'
                            AND (
                                ('{$data_inicial}' between c.dt_alocacao_ini and c.dt_alocacao_fim)
                                OR ('{$data_final}' between c.dt_alocacao_ini and c.dt_alocacao_fim)
                            )
                            AND c.i_patrimonio = a.i_patrimonio)";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function get_veiculos()
    {
        $sql = "SELECT a.i_patrimonio, a.marca_modelo||': '||a.placa AS nome
                FROM dba.cv_veiculos as a key join dba.pa_patrimonios
                WHERE pa_patrimonios.status ='A'
                AND a.disponivel = 'S'
                AND exists(select 1 from dba.cv_alocacoes c
                            where c.i_empresa = a.i_empresa
                            and c.i_patrimonio = a.i_patrimonio)";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getVeiculoPlaca($placa)
    {
        $sql = "SELECT a.i_patrimonio
                    , a.marca_modelo AS nome
                    , a.placa as placa
                FROM dba.cv_veiculos as a
                KEY JOIN dba.pa_patrimonios as b
                WHERE b.status ='A'
                AND a.disponivel = 'S'
                AND a.placa = '{$placa}'";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function get_historico($i_patrimonio, $dt_inicio, $dt_fim)
    {
        $filtro = '';
        if (trim($i_patrimonio)!='') {
            $filtro .= " AND a.i_patrimonio = {$i_patrimonio}";
        }

        if (trim($dt_inicio) != '') {
            $dt_inicio = $this->satc->formata_data($dt_inicio, 2);
            $filtro .= " AND DATEFORMAT(b.dt_alocacao_ini,'YYYY-MM-DD') >= '{$dt_inicio}'";
        }

        if (trim($dt_fim) != '') {
            $dt_fim = $this->satc->formata_data($dt_fim, 2);
            $filtro .= " AND DATEFORMAT(b.dt_alocacao_fim,'YYYY-MM-DD') <= '{$dt_fim}' ";
        }

        $sql = "SELECT a.i_empresa
                        , a.i_patrimonio
                        , (select z.nome from dba.ge_usuarios as z where z.i_usuario = b.i_usuario) as condutor
                        , a.marca_modelo
                        , a.combustivel
                        , a.placa
                        , b.i_alocacao
                        , DATEFORMAT(b.dt_alocacao_ini,'DD/MM/YYYY HH:MM') AS dt_alocacao_ini
                        , DATEFORMAT(b.dt_alocacao_fim,'DD/MM/YYYY HH:MM') AS dt_alocacao_fim
                        , b.acompanhante
                        , b.acompanhantes
                        , b.n_passageiros
                        , b.justificativa
                        , b.i_cidade_ibge
                        , b.status
                        , b.status_aprov
                        , DATEFORMAT(b.dt_cancel,'DD/MM/YYYY HH:MM') AS dt_cancel
                        , b.motivo_cancel
                        , if (b.status_aprov = 'P') then 'Pendente'
                        else if (b.status_aprov = 'R') then 'Reprovado' else 'Aprovado' end if end if AS situacao
                FROM dba.cv_veiculos AS a
                LEFT JOIN dba.cv_alocacoes AS b ON b.i_empresa= a.i_empresa AND b.i_patrimonio = a.i_patrimonio
                WHERE a.disponivel = 'S' AND NOT b.i_alocacao IS NULL {$filtro} ";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getHistoricoCondutores()
    {
        $sql = "SELECT (select z.nome from dba.ge_usuarios as z where z.i_usuario = b.i_usuario) as nome_condutor
                    , b.i_usuario
                    , count(1) as total_alocacoes
                FROM dba.cv_alocacoes AS b
                WHERE b.status_aprov = 'A'
                GROUP BY b.i_usuario
                ORDER BY total_alocacoes DESC,nome_condutor ASC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getResumoAlocacao($i_alocacao)
    {
        $sql = "SELECT DATEFORMAT(dt_inicial,'DD/MM/YYYY HH:MM') AS dt_inicial
                    , DATEFORMAT(dt_final,'DD/MM/YYYY HH:MM') AS dt_final
                    , km_inicial
                    , km_final
                    , km_percorrida
                    , vl_combustivel
                    , vl_gasto
                    , km_litro
                    , observacao
                    , i_alocacao
                FROM dba.cv_alocacoes_km
                WHERE i_alocacao = {$i_alocacao}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function setResumo($dados, $i_alocacao = '')
    {
        if (trim($i_alocacao) != '') {
            $this->db->where('i_alocacao', $i_alocacao);
            $this->db->update('dba.cv_alocacoes_km', $dados);
        } else {
            $this->db->insert('dba.cv_alocacoes_km', $dados);
        }
    }

    public function getDatasAlocacao($i_alocacao)
    {
        $sql = "SELECT DATEFORMAT(dt_alocacao_ini,'DD/MM/YYYY') AS dt_inicial
                    , DATEFORMAT(dt_alocacao_ini, 'HH:MM') AS hr_inicial
                    , DATEFORMAT(dt_alocacao_fim, 'DD/MM/YYYY') AS dt_final
                    , DATEFORMAT(dt_alocacao_fim, 'HH:MM') AS hr_final
                FROM dba.cv_alocacoes
                WHERE i_empresa = {$this->session->userdata('i_empresa')} AND
                i_alocacao = {$i_alocacao}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno;
    }

    public function setKmMinimo($i_patrimonio, $km_minimo)
    {
        $km_minimo = str_replace(',', '.', $km_minimo);
        $this->db->where('i_patrimonio', $i_patrimonio);
        $this->db->update('dba.cv_veiculos', array('km_minimo'=>$km_minimo));
    }

    public function setAlterarVeiculo($i_alocacao, $i_patrimonio)
    {
        $this->db->where('i_alocacao', $i_alocacao);
        $this->db->update('dba.cv_alocacoes', array('i_patrimonio'=>$i_patrimonio));
    }

    public function setCatVeiculos($data)
    {
        if (isset($data['editar'])) {
            $this->db->where('i_empresa', $data['i_empresa']);
            $this->db->where('i_patrimonio', $data['i_patrimonio']);
            unset($data['i_empresa']);
            unset($data['i_patrimonio']);
            unset($data['editar']);
            return $this->db->update('dba.cv_veiculos', $data);
        } else {
            return $this->db->insert('dba.cv_grupos', $data);
        }
    }

    public function getNomeCatVeiculos($i_grupo = '')
    {
        if (trim($i_grupo) != '') {
            $sql = "SELECT nome
                    FROM dba.cv_grupos
                    WHERE i_grupo = {$i_grupo}";
            $query = $this->db->query($sql);
            $retorno = $query->row();
            $query->free_result();
            return $retorno;
        }
    }

    public function getCatVeiculos()
    {
        $sql = "SELECT i_grupo
                       , nome
                FROM dba.cv_grupos";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function setSalvarDocumentos($documentos)
    {
        $documentos['descricao'] = utf8_decode($documentos['descricao']);
        return $this->db->insert('dba.cv_veiculos_documentos', $documentos);
    }

    public function getDocumentos($i_patrimonio)
    {
        $sql = "SELECT i_documento
                    , arquivo
                    , descricao
                    , DATEFORMAT(dt_vencimento,'DD/MM/YYYY') AS dt_vencimento
                FROM dba.cv_veiculos_documentos
                WHERE i_patrimonio = {$i_patrimonio}
                AND i_empresa = {$this->session->userdata('i_empresa')}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getGerenciamentoVeiculos()
    {
        $sql = "SELECT a.i_patrimonio
                    , a.marca_modelo||': '||a.placa AS nome
                    , a.km_minimo
                    , a.placa
                    , a.marca_modelo
                    , a.combustivel
                    , (SELECT nome FROM dba.cv_grupos as b
                        WHERE b.i_grupo = a.i_grupo AND b.i_empresa = a.i_empresa) as i_grupo
                    , (SELECT count(1) FROM dba.cv_veiculos_documentos as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa) as total_documentos
                    , (SELECT count(1) FROM dba.cv_veiculos_manutencoes as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa) as total_manutencoes
                    , (SELECT count(1) FROM dba.cv_veiculos_manutencoes as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa
                        AND b.tipo = 'P'
                        AND isnull(b.dt_prox_manutencao, now()) <> now()) as total_manutencoes_prev
                    , (SELECT count(1) FROM dba.cv_combustivel_veiculos as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa
                        AND b.status = 'S') as total_combustiveis
                    , (SELECT count(1) FROM dba.cv_veiculos_multas as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa
                        AND b.status != 'F') as total_multas
                    , (SELECT count(1) FROM dba.cv_alocacoes as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa
                        AND b.status_aprov = 'A') as total_utilizacoes
                    , (SELECT DATEFORMAT(min(b.dt_vencimento), 'DD/MM/YYYY') FROM dba.cv_veiculos_documentos as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa
                        AND b.dt_vencimento>=now()) as dt_vencimento
                    , ISNULL((SELECT DATEFORMAT(min(b.dt_prox_manutencao), 'DD/MM/YYYY') FROM dba.cv_veiculos_manutencoes as b
                            WHERE b.i_patrimonio = a.i_patrimonio
                            AND b.i_empresa = a.i_empresa
                            AND b.dt_prox_manutencao>=now()), '') as dt_manutencao_prev
                    , (SELECT DATEDIFF(DAY, min(b.dt_vencimento), now())
                        FROM dba.cv_veiculos_documentos as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa
                        AND b.dt_vencimento>=now()) as diferenca
                    , (SELECT DATEDIFF(DAY, min(b.dt_prox_manutencao), now())
                        FROM dba.cv_veiculos_manutencoes as b
                        WHERE b.i_patrimonio = a.i_patrimonio
                        AND b.i_empresa = a.i_empresa
                        AND b.dt_prox_manutencao>=now()) as proximamanutencao
                FROM dba.cv_veiculos as a key JOIN dba.pa_patrimonios
                WHERE pa_patrimonios.status ='A'
                AND a.disponivel = 'S'
                ORDER BY a.i_patrimonio, dt_vencimento DESC, total_multas DESC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getListaCarros($busca = '')
    {
        $filtro = '';
        if ($busca != '') {
            $filtro = "AND a.marca_modelo LIKE '%{$busca}%' OR a.placa LIKE '%{$busca}%'";
        }
        $sql = "SELECT a.i_patrimonio
                    , a.marca_modelo AS nome
                    , a.placa as placa
                FROM dba.cv_veiculos as a
                key JOIN dba.pa_patrimonios
                WHERE dba.pa_patrimonios.status ='A'
                {$filtro}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function setSalvarMultas($multas)
    {
        $this->db->where('i_multa', $multas['i_multa']);
        $this->db->where('i_empresa', $multas['i_empresa']);
        $this->db->where('i_patrimonio', $multas['i_patrimonio']);
        return $this->db->update('dba.cv_veiculos_multas', $multas);
    }

    public function getListaManutencoes($i_patrimonio)
    {
        $sql = "SELECT a.i_manutencao
                    , a.descricao
                    , (CASE a.tipo
                        WHEN 'P' THEN 'Preventiva'
                        WHEN 'C' THEN 'Corretiva'
                        WHEN 'S' THEN 'Sinistro'
                       ELSE '' END) as tipo_view
                    , a.tipo
                    , a.custo
                    , DATEFORMAT(a.dt_prox_manutencao,'DD/MM/YYYY') AS dt_prox_manutencao
                    , (SELECT TOP 1 b.km_final
                          FROM dba.cv_veiculos_autorizacao AS b
                          WHERE b.i_patrimonio  = a.i_patrimonio ORDER BY b.km_final DESC) AS km_atual
                    , DATEFORMAT(NOW(),'DD/MM/YYYY') AS dt_atual
                    , str(isnull(a.km_prox_manutencao,0), length(a.km_prox_manutencao)-2, 0) as km_prox_manutencao
                    , DATEFORMAT(a.dt_manutencao,'DD/MM/YYYY') AS dt_manutencao
                    , str(a.kilometragem, length(a.kilometragem)-2, 0) as kilometragem
                    , (SELECT COUNT(1)
                        FROM dba.cv_veiculos_manutencoes_arquivos as b
                        WHERE b.i_manutencao = a.i_manutencao
                        AND b.arquivo <> '') as total_arquivos
                FROM dba.cv_veiculos_manutencoes as a
                WHERE a.i_patrimonio = {$i_patrimonio}
                AND a.i_empresa = {$this->session->userdata('i_empresa')}
                order by a.dt_manutencao DESC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function setSalvarManutencoes($manutencoes)
    {
        $manutencoes['descricao'] = utf8_decode($manutencoes['descricao']);
        return $this->db->insert('dba.cv_veiculos_manutencoes', $manutencoes);
    }

    public function setSalvarManutencoesArquivos($arquivos)
    {
        return $this->db->insert('dba.cv_veiculos_manutencoes_arquivos', $arquivos);
    }

    public function getlistaArquivosManutencao($i_manutencao)
    {
        $sql = "SELECT a.i_arquivo
                    , a.arquivo
                FROM dba.cv_veiculos_manutencoes_arquivos as a
                WHERE a.i_manutencao = {$i_manutencao}";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getCondutoresAnterior($data)
    {
        $i_patrimonio = $data['i_patrimonio'];
        $dt_multa = $data['dt_multa'];

        $sql = "SELECT (SELECT y.nome
                        FROM dba.ge_usuarios AS y
                        WHERE y.i_usuario = z.i_usuario) AS nome_condutor
                FROM dba.cv_alocacoes AS z
                WHERE z.i_patrimonio = {$i_patrimonio}
                AND z.dt_alocacao_ini LIKE '{$dt_multa}%'
                OR z.dt_alocacao_fim LIKE '{$dt_multa}%'";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        if (!empty($retorno['nome_condutor'])) {
            $retorno['dia'] = $this->satc->formata_data($dt_multa,1);
            $retorno['nome_condutor'] = $this->satc->utf8_encode($retorno['nome_condutor']);
        }
        return $retorno;
    }

    public function imagemCNH($i_usuario)
    {
        $sql = "SELECT cnh_imagem
            FROM dba.cv_condutores
            WHERE i_usuario = {$i_usuario}";
        $query = $this->db->query($sql);
        $retorno = $query->row_array();
        $query->free_result();
        return $retorno['cnh_imagem'];
    }

    public function getHistoricoAlocacoes($i_usuario = '')
    {
        // SE ADICIONAR ALGUM CAMPO NESTE SQL, DAR UNSET NA FUNÇÃO setSalvarAutorizacao no Controller(Veiculos)
        if ($i_usuario == '') {
            $filtro = '';
        } else {
            $filtro = "AND a.i_usuario = {$i_usuario}";
        }
        $sql = "SELECT (SELECT b.marca_modelo
                        FROM dba.cv_veiculos as b
                        WHERE b.i_empresa = a.i_empresa
                        AND b.i_patrimonio = a.i_patrimonio) as veiculo
                        , (SELECT b.placa
                            FROM dba.cv_veiculos as b
                            WHERE b.i_empresa = a.i_empresa
                            AND b.i_patrimonio = a.i_patrimonio) as placa
                        , a.i_patrimonio
                        , a.i_alocacao
                        , (SELECT b.nome FROM dba.ge_usuarios AS b WHERE b.i_usuario = a.i_usuario) AS condutor
                        , (SELECT b.numero_carteira FROM dba.cv_condutores AS b WHERE b.i_usuario = a.i_usuario) AS numero_carteira
                        , (SELECT DATEFORMAT(b.dt_validade,'DD/MM/YYYY') FROM dba.cv_condutores AS b WHERE b.i_usuario = a.i_usuario) AS dt_validade_cnh
                        , DATEFORMAT(a.dt_alocacao_ini,'DD/MM/YYYY') AS dt_alocacao_inicial
                        , DATEFORMAT(a.dt_alocacao_fim,'DD/MM/YYYY') AS dt_alocacao_final
                        , DATEFORMAT(a.dt_alocacao_ini,'HH:mm') AS hr_alocacao_inicial
                        , DATEFORMAT(a.dt_alocacao_fim,'HH:mm') AS hr_alocacao_final
                        , a.n_passageiros
                        , (SELECT b.nome||' - '||b.uf
                            FROM dba.ge_cidades_ibge AS b
                            WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS destino
                        , a.justificativa
                        , a.n_passageiros
                        , DATEFORMAT(a.dt_alocacao_ini,'YYYY-MM-DD HH:MM') AS data_alocacao
                        , a.i_unidade
                        , (SELECT TOP 1 1 FROM dba.cv_veiculos_autorizacao as b WHERE b.i_alocacao = a.i_alocacao) as autorizacao_feita
                        , a.i_usuario
                        , (SELECT nome FROM dba.ci_unidades as b WHERE a.i_empresa=b.i_empresa AND a.i_unidade=b.i_unidade) as nome_unidade
                FROM dba.cv_alocacoes as a
                WHERE a.status_aprov = 'A'
                {$filtro}
                ORDER BY dt_alocacao_ini DESC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getHistoricoCarro($i_patrimonio)
    {
        $sql = "SELECT  (select z.nome from dba.ge_usuarios as z where z.i_usuario = a.i_usuario) as condutor
                        , a.i_patrimonio
                        , DATEFORMAT(a.dt_alocacao_ini,'DD/MM/YYYY HH:MM') AS dt_alocacao_inicial
                        , DATEFORMAT(a.dt_alocacao_fim,'DD/MM/YYYY HH:MM') AS dt_alocacao_final
                        , a.dt_alocacao_ini as data_alocacao
                        , n_passageiros
                        , (SELECT b.nome||' - '||b.uf
                            FROM dba.ge_cidades_ibge AS b
                            WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS destino
                        , a.justificativa
                FROM dba.cv_alocacoes as a
                WHERE a.i_patrimonio = {$i_patrimonio}
                AND a.status_aprov = 'A'
                ORDER BY dt_alocacao_ini DESC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getAgendamentoData($dados)
    {
        $data_retirada = $this->satc->formata_data($dados['data_retirada'], 2);
        $data_entrega = $this->satc->formata_data($dados['data_entrega'], 2);
        $sql = "SELECT (select z.nome from dba.ge_usuarios as z where z.i_usuario = a.i_usuario) as condutor
                        , n_passageiros
                        , DATEFORMAT(a.dt_alocacao_ini,'DD/MM/YYYY HH:MM') AS dt_alocacao_inicial
                        , DATEFORMAT(a.dt_alocacao_fim,'DD/MM/YYYY HH:MM') AS dt_alocacao_final
                        , (SELECT b.nome||' - '||b.uf
                            FROM dba.ge_cidades_ibge AS b
                            WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS destino
                        , a.justificativa
                        , a.status_aprov
                FROM dba.cv_alocacoes as a
                WHERE a.i_cidade_ibge = {$dados['i_cidade_ibge']}
                AND a.dt_alocacao_ini LIKE '{$data_retirada}%'
                AND a.dt_alocacao_fim LIKE '{$data_entrega}%'
                AND a.status_aprov <> 'R'
                ORDER BY dt_alocacao_ini DESC";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        $query->free_result();
        return $result;
    }

    public function getCalendar($status = '')
    {
        $filtro = '';
        if ($status != '') {
            $filtro = "AND a.status_aprov = '{$status}'";
        }
        $sql = "SELECT a.i_alocacao
                    , (select z.nome from dba.ge_usuarios as z where z.i_usuario = a.i_usuario) as nome_solicitante
                    , DATEFORMAT(a.dt_alocacao_ini, 'YYYY-MM-DD')||'T'||DATEFORMAT(a.dt_alocacao_ini, 'HH:MM') AS dt_alocacao_ini
                    , DATEFORMAT(a.dt_alocacao_fim, 'YYYY-MM-DD')||'T'||DATEFORMAT(a.dt_alocacao_fim, 'HH:MM') AS dt_alocacao_fim
                    , DATEFORMAT(a.dt_alocacao_ini, 'HH:MM') AS hr_inicio
                    , DATEFORMAT(a.dt_alocacao_fim, 'HH:MM') AS hr_fim
                    , (SELECT z.marca_modelo||' \n '||z.placa
                        FROM dba.cv_veiculos AS z
                        WHERE z.i_patrimonio = a.i_patrimonio) as tit
                    , (SELECT b.nome||' - '||b.uf
                            FROM dba.ge_cidades_ibge AS b
                            WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS destino
                    , a.status_aprov
                    , tit||' (' \n  ||hr_inicio||' - '||hr_fim||')' as titulo
                FROM dba.cv_alocacoes AS a
                WHERE a.status_aprov != 'R'
                {$filtro}";
        $query = $this->db->query($sql);
        $lista = $query->result_array();
        $retorno = array();
        if (count($lista)>0) {
            foreach ($lista as $a) {
                switch ($a['status_aprov']) {
                    case 'A':
                        $border_color = 'rgb(0, 167, 26)';
                        $bg_color = 'rgb(211, 251, 191)';
                        $tipo = 'A';
                        break;
                    case 'P':
                        $border_color = '#A4A4A4';
                        $bg_color = '#D7D7D7';
                        $tipo = 'P';
                        break;
                }

                $retorno[] = array(
                    'i_alocacao' => $a['i_alocacao'],
                    'title' => $this->satc->utf8_encode("Ve&iacute;culo: {$a['titulo']} <br> Usu&aacute;rio:  {$a['nome_solicitante']}"),
                    'start' => $a['dt_alocacao_ini'],
                    'end' => $a['dt_alocacao_fim'],
                    'backgroundColor' => $bg_color,
                    'borderColor' => $border_color,
                    'tipo' => $tipo
                );
            }
        }
        $query->free_result();
        return json_encode($retorno);
    }

    public function getCalendarNovo($status = '',$dia,$mes,$ano,$dia_consulta_inicio,$dia_consulta_fim,$tipoView)
    {
        $filtro = '';
        if ($status != '') {
            $filtro = " AND a.status_aprov = '{$status}'";
        }

        switch ($tipoView) {
                    case 'basicWeek':
                        $sql1 = " AND dt_alocacao_ini>='{$dia_consulta_inicio}' AND dt_alocacao_ini<='{$dia_consulta_fim}'";
                        break;
                    case 'basicDay':
                        $sql1 = " AND DAY(dt_alocacao_ini)={$dia} AND MONTH(dt_alocacao_ini) = {$mes} AND YEAR(dt_alocacao_ini) = {$ano}";
                        break;
                    case 'month':
                        $sql1 = " AND MONTH(dt_alocacao_ini) = {$mes} AND YEAR(dt_alocacao_ini) = {$ano}";
                        break;
                    default:
                        $sql1 = " 1=2";
                        break;
                }

        $sql = "SELECT a.i_alocacao
                    , (select z.nome from dba.ge_usuarios as z where z.i_usuario = a.i_usuario) as nome_solicitante
                    , DATEFORMAT(a.dt_alocacao_ini, 'YYYY-MM-DD')||'T'||DATEFORMAT(a.dt_alocacao_ini, 'HH:MM') AS dt_alocacao_ini
                    , DATEFORMAT(a.dt_alocacao_fim, 'YYYY-MM-DD')||'T'||DATEFORMAT(a.dt_alocacao_fim, 'HH:MM') AS dt_alocacao_fim
                    , DATEFORMAT(a.dt_alocacao_ini, 'HH:MM') AS hr_inicio
                    , DATEFORMAT(a.dt_alocacao_fim, 'HH:MM') AS hr_fim
                    , (SELECT ' - '||z.marca_modelo||' \n '||z.placa
                        FROM dba.cv_veiculos AS z
                        WHERE z.i_patrimonio = a.i_patrimonio) as tit
                    , (SELECT b.nome||' - '||b.uf
                            FROM dba.ge_cidades_ibge AS b
                            WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS destino
                    , a.status_aprov
                    , tit||' (' \n  ||hr_inicio||' - '||hr_fim||')' as titulo
                FROM dba.cv_alocacoes AS a
                WHERE a.status_aprov != 'R' {$sql1} {$filtro}";
        $query = $this->db->query($sql);
        $lista = $query->result_array();
        $retorno = array();
        if (count($lista)>0) {
            foreach ($lista as $a) {
                switch ($a['status_aprov']) {
                    case 'A':
                        $border_color = 'rgb(0, 167, 26)';
                        $bg_color = 'rgb(211, 251, 191)';
                        $tipo = 'A';
                        break;
                    case 'P':
                        $border_color = '#A4A4A4';
                        $bg_color = '#D7D7D7';
                        $tipo = 'P';
                        break;
                }

                $retorno[] = array(
                    'i_alocacao' => $a['i_alocacao'],
                    'title' => $this->satc->utf8_encode("{$a['titulo']} \n  {$a['nome_solicitante']}"),
                    'start' => $a['dt_alocacao_ini'],
                    'end' => $a['dt_alocacao_fim'],
                    'backgroundColor' => $bg_color,
                    'borderColor' => $border_color,
                    'tipo' => $tipo
                );
            }
        }
        $query->free_result();
        return json_encode($retorno);
    }


    public function getDadosAlocacao($i_alocacao)
    {
        $sql = "SELECT a.i_alocacao
                    , a.i_empresa
                    , a.i_usuario
                    , a.observacao
                    , (select z.nome from dba.ge_usuarios as z where z.i_usuario = a.i_usuario) as nome_solicitante
                    , DATEFORMAT(a.dt_alocacao_ini, 'DD-MM-YYYY HH:MM') AS dt_alocacao_ini
                    , DATEFORMAT(a.dt_alocacao_fim, 'DD-MM-YYYY HH:MM') AS dt_alocacao_fim
                    , DATEFORMAT(a.dt_alocacao_ini, 'HH:MM') AS hr_inicio
                    , DATEFORMAT(a.dt_alocacao_fim, 'HH:MM') AS hr_fim
                    , a.justificativa
                    , isnull(a.n_passageiros, 0) as n_passageiros
                    , (SELECT nome FROM dba.ci_unidades as b WHERE a.i_empresa=b.i_empresa AND a.i_unidade=b.i_unidade) as nome_unidade
                    , (SELECT z.marca_modelo||' - '||z.placa
                        FROM dba.cv_veiculos AS z
                        WHERE z.i_patrimonio = a.i_patrimonio) as veiculo
                    , isnull((SELECT b.nome||' - '||b.uf
                            FROM dba.ge_cidades_ibge AS b
                            WHERE b.i_cidade_ibge=a.i_cidade_ibge),' - ') AS destino
                    , a.status_aprov
                FROM dba.cv_alocacoes as a
                WHERE a.i_alocacao = {$i_alocacao}";
        $query = $this->db->query($sql);
        $res = $query->row_array();
        $query->free_result();
        $return = array();
        if (count($res)>0) {
            foreach ($res as $key => $val) {
                $return[$key] = $this->satc->utf8_encode($val);
            }
        }
        return $return;
    }

    public function setSalvarAutorizacao($obj)
    {
        if (!empty($obj['problemas'])) {
            $obj['problemas'] = utf8_decode($obj['problemas']);
        }
        return $this->db->insert('dba.cv_veiculos_autorizacao', $obj);
    }

    public function getAlocacaoAutorizacao($i_alocacao = '')
    {
        if ($i_alocacao == '') {
            $filtro = '';
        } else {
            $filtro = "AND a.i_alocacao = {$i_alocacao}";
        }
        $sql = "SELECT (SELECT b.marca_modelo
                        FROM dba.cv_veiculos as b
                        WHERE b.i_empresa = a.i_empresa
                        AND b.i_patrimonio = a.i_patrimonio) as veiculo
                        , (SELECT b.placa
                            FROM dba.cv_veiculos as b
                            WHERE b.i_empresa = a.i_empresa
                            AND b.i_patrimonio = a.i_patrimonio) as placa
                        , a.i_patrimonio
                        , a.i_alocacao
                        , (SELECT b.nome FROM dba.ge_usuarios AS b WHERE b.i_usuario = a.i_usuario) AS condutor
                        , (SELECT b.numero_carteira FROM dba.cv_condutores AS b WHERE b.i_usuario = a.i_usuario) AS numero_carteira
                        , (SELECT DATEFORMAT(b.dt_validade,'DD/MM/YYYY') FROM dba.cv_condutores AS b WHERE b.i_usuario = a.i_usuario) AS dt_validade_cnh
                        , DATEFORMAT(a.dt_alocacao_ini,'DD/MM/YYYY') AS dt_alocacao_inicial
                        , DATEFORMAT(a.dt_alocacao_fim,'DD/MM/YYYY') AS dt_alocacao_final
                        , DATEFORMAT(a.dt_alocacao_ini,'HH:mm') AS hr_alocacao_inicial
                        , DATEFORMAT(a.dt_alocacao_fim,'HH:mm') AS hr_alocacao_final
                        , a.n_passageiros
                        , (SELECT b.nome||' - '||b.uf
                            FROM dba.ge_cidades_ibge AS b
                            WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS destino
                        , a.justificativa
                        , a.n_passageiros
                        , a.dt_alocacao_ini as data_alocacao
                        , a.i_unidade
                        , (SELECT TOP 1 1 FROM dba.cv_veiculos_autorizacao as b WHERE b.i_alocacao = a.i_alocacao) as autorizacao_feita
                        , a.i_usuario
                        , (SELECT nome FROM dba.ci_unidades as b WHERE a.i_empresa=b.i_empresa AND a.i_unidade=b.i_unidade) as nome_unidade
                FROM dba.cv_alocacoes as a
                WHERE a.status_aprov = 'A'
                {$filtro}
                ORDER BY a.dt_alocacao_fim DESC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getProblemasVeiculos()
    {
        $sql = "SELECT a.i_patrimonio
                        , (SELECT b.marca_modelo
                        FROM dba.cv_veiculos as b
                        WHERE b.i_empresa = a.i_empresa
                        AND b.i_patrimonio = a.i_patrimonio) as veiculo
                        , (SELECT b.placa
                            FROM dba.cv_veiculos as b
                            WHERE b.i_empresa = a.i_empresa
                            AND b.i_patrimonio = a.i_patrimonio) as placa
                        , (SELECT COUNT(1) FROM dba.cv_veiculos_autorizacao as b WHERE b.i_patrimonio = a.i_patrimonio) as n_problemas
                FROM dba.cv_alocacoes as a
                WHERE a.status_aprov = 'A'
                AND EXISTS(SELECT 1 FROM dba.cv_veiculos_autorizacao as c WHERE c.i_alocacao = a.i_alocacao AND trim(c.problemas) <> '')
                GROUP BY a.i_patrimonio, a.i_empresa
                ORDER BY n_problemas DESC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function getProblemasVeiculo($i_patrimonio)
    {
        $sql = "SELECT a.i_autorizacao
                    , a.i_alocacao
                    , a.i_patrimonio
                    , (SELECT b.marca_modelo
                        FROM dba.cv_veiculos as b
                        WHERE b.i_patrimonio = a.i_patrimonio) as veiculo
                    , (SELECT b.placa
                        FROM dba.cv_veiculos as b
                        WHERE b.i_patrimonio = a.i_patrimonio) as placa
                    , (SELECT b.nome FROM dba.ge_usuarios AS b WHERE b.i_usuario = a.i_usuario) AS condutor
                    , a.problemas
                    , a.dt_inicial
                    , a.i_patrimonio
                    , DATEFORMAT(a.dt_inicial,'DD/MM/YYYY HH:mm') AS dt_alocacao_inicial
                    , DATEFORMAT(a.dt_final,'DD/MM/YYYY HH:mm') AS dt_alocacao_final
                    , a.solucao
                FROM dba.cv_veiculos_autorizacao as a
                WHERE trim(a.problemas) <> ''
                AND a.i_patrimonio = {$i_patrimonio}
                ORDER BY veiculo DESC";
        $query = $this->db->query($sql);
        $retorno = $query->result_array();
        $query->free_result();
        return $retorno;
    }

    public function setSalvarSolucao($obj)
    {
        $obj['problemas'] = utf8_decode($obj['problemas']);
        $obj['solucao'] = utf8_decode($obj['solucao']);
        $this->db->where('i_autorizacao', $obj['i_autorizacao']);
        return $this->db->update('dba.cv_veiculos_autorizacao', $obj);;
    }

    public function setAprovacaoCoordenador($status, $motivo, $i_alocacao, $i_aprovadirecao)
    {
        $array_up = array();
        if (trim($motivo)!='') {
            $array_up = array('dt_cancel'=>$this->satc->data_atual());
        }

        $this->db->where('i_alocacao', $i_alocacao);
        $this->db->update('dba.cv_alocacoes', array('status_aprov'=>$status, 'motivo_cancel'=>$motivo, 'i_aprovadirecao'=>$i_aprovadirecao)+$array_up);
    }

    public function getVeiculosAgendadosUnidade($usuario = '', $i_alocacao = '')
    {
        $filtro = ' 1=1 ';
        if (!trim($i_alocacao)=='') {
            $filtro .= " AND a.i_alocacao = {$i_alocacao}";
        }

        if (!trim($usuario)=='') {
            $filtro .= " AND a.usuario = '{$usuario}'";
        }
        $i_usuario = $this->session->userdata('i_usuario');
        if ($this->session->userdata('i_usuario') == 4) {
            $i_usuario = 23;
        }

        if ($this->session->userdata('i_usuario') == 3398) {
            $i_usuario = 23;
        }

        $sql = "SELECT b.i_empresa
                    , b.i_patrimonio
                    , b.marca_modelo
                    , b.combustivel
                    , b.placa
                    , (select z.nome from dba.ge_usuarios as z where z.nome_login = a.usuario) as solicitante
                    , (select z.nome from dba.ge_usuarios as z where z.i_usuario = a.i_usuario) as condutor
                    , a.i_alocacao
                    , DATEFORMAT(a.dt_alocacao_ini,'DD/MM/YYYY HH:MM') AS dt_alocacao_ini
                    , DATEFORMAT(a.dt_alocacao_fim,'DD/MM/YYYY HH:MM') AS dt_alocacao_fim
                    , a.acompanhante
                    , a.acompanhantes
                    , a.n_passageiros
                    , a.justificativa
                    , a.status
                    , a.status_aprov
                    , a.i_cidade_ibge
                    , a.dt_cancel
                    , a.motivo_cancel
                    , a.i_unidade
                    , a.emergencial
                    , (select top 1 z.nome from dba.ci_unidades as z where z.i_empresa = a.i_empresa and z.i_unidade = a.i_unidade) as nome_unidade
                    , if a.dt_alocacao_fim < now() then 1 else 0 end if AS status_tempo
                    , (SELECT COUNT(1)
                        FROM dba.cv_alocacoes as z
                        where z.i_patrimonio = a.i_patrimonio) AS total_alocacoes
                    , ISNULL((SELECT km_percorrida
                        FROM dba.cv_alocacoes_km as z
                        WHERE z.i_empresa = a.i_empresa
                        AND z.i_alocacao = a.i_alocacao),0) AS km_percorrida
                FROM dba.cv_alocacoes AS a
                LEFT JOIN dba.cv_veiculos AS b ON b.i_empresa= a.i_empresa AND b.i_patrimonio = a.i_patrimonio
                WHERE {$filtro}
                AND EXISTS(SELECT 1 FROM dba.ge_usuarios_unidades as z
                            WHERE z.i_empresa = a.i_empresa
                            AND z.i_unidade = a.i_unidade
                            and z.nivel = (select nivel_min_req - 1
                                        from ci_unidades as s
                                        WHERE s.i_empresa = z.i_empresa
                                        AND s.i_unidade = z.i_unidade)
                            and z.i_usuario = {$i_usuario})
                ORDER BY a.dt_alocacao_ini DESC";
        $query = $this->db->query($sql);
        $return = $query->result_array();
        $query->free_result();
        return $return;
    }

    public function getNumerosAgendamentos($dt_inicial = '', $dt_final = '')
    {
        $filtro = '';
        if (trim($dt_inicial) != '' && trim($dt_final) != '') {
            $filtro .= " AND DATEFORMAT(dt_alocacao_ini,'YYYY-MM-DD') >= '{$dt_inicial}'";
            $filtro .= " AND DATEFORMAT(dt_alocacao_fim,'YYYY-MM-DD') <= '{$dt_final}' ";
        }

        $sql = "SELECT status_aprov
                    , count(1) as total
                    , emergencial
                FROM dba.cv_alocacoes
                WHERE 1 = 1
                {$filtro}
                GROUP BY status_aprov,emergencial";
        $query = $this->db->query($sql);
        $return = $query->result_array();
        $query->free_result();
        return $return;
    }

    public function getRelatorioKmRodado($filtro = '')
    {
        $sql = "SELECT a.i_alocacao
                       , a.i_patrimonio
                       , (SELECT nome
                          FROM dba.ci_unidades AS b
                          WHERE a.i_empresa=b.i_empresa
                          AND a.i_unidade=b.i_unidade) as nome_unidade
                       , (SELECT b.placa
                          FROM dba.cv_veiculos AS b
                          WHERE b.i_empresa = a.i_empresa
                          AND b.i_patrimonio = a.i_patrimonio) AS placa
                       , (SELECT b.marca_modelo
                          FROM dba.cv_veiculos AS b
                          WHERE b.i_empresa = a.i_empresa
                          AND b.i_patrimonio = a.i_patrimonio) AS veiculo
                       , (SELECT TOP 1 CONVERT(DOUBLE, b.km_final - b.km_inicial)
                          FROM dba.cv_veiculos_autorizacao AS b
                          WHERE b.i_alocacao = a.i_alocacao) AS km_rodado
                       , DATEFORMAT(a.dt_alocacao_ini,'DD/MM/YYYY HH:MM') AS dt_alocacao_inicial
                       , DATEFORMAT(a.dt_alocacao_fim,'DD/MM/YYYY HH:MM') AS dt_alocacao_final
                FROM dba.cv_alocacoes AS a
                WHERE a.i_empresa = {$this->session->userdata('i_empresa')}
                AND a.i_patrimonio <> 1
                AND km_rodado IS NOT NULL
                {$filtro}
                ORDER BY a.i_unidade DESC";
        $query = $this->db->query($sql);
        $return = $query->result_array();
        $query->free_result();
        return $return;
    }

    public function getRelatorioManutencao($filtro = '')
    {
        $sql = "SELECT (SELECT b.marca_modelo
                        FROM dba.cv_veiculos AS b
                        WHERE b.i_patrimonio = a.i_patrimonio) AS veiculo
                       , (SELECT b.placa
                        FROM dba.cv_veiculos as b
                        WHERE b.i_patrimonio = a.i_patrimonio) as placa
                       , a.descricao
                       , (CASE a.tipo
                            WHEN 'P' THEN 'Preventiva'
                            WHEN 'C' THEN 'Corretiva'
                          END) AS tipo_formatado
                       , a.custo
                       , DATEFORMAT(a.dt_manutencao,'DD/MM/YYYY') AS dt_manutencao
                       , CONVERT(DOUBLE,a.kilometragem) AS km
                FROM dba.cv_veiculos_manutencoes AS a
                WHERE a.i_empresa = {$this->session->userdata('i_empresa')}
                {$filtro}
                ORDER BY a.dt_manutencao DESC";
        $query = $this->db->query($sql);
        $return = $query->result_array();
        $query->free_result();
        return $return;
    }

    public function getRelatorioManutencaoMensal($filtro = '')
    {
        $sql = "SELECT MONTH(a.dt_manutencao) as order_mes,
                       YEAR(a.dt_manutencao) as order_ano,
                       (convert(varchar(2), order_mes) +'/'+convert(varchar(4), order_ano)) AS mes_ano,
                       ISNULL(a.tipo, 'C') AS tipo,
                       ISNULL(SUM(a.custo), 0) custo
                FROM dba.cv_veiculos_manutencoes AS a 
                WHERE a.i_empresa = {$this->session->userdata('i_empresa')}
                {$filtro}
                GROUP BY order_mes, order_ano, a.tipo
                ORDER BY -order_ano, -order_mes";
                
        $query = $this->db->query($sql);
        $return = $query->result_array();
        $query->free_result();
        return $return;
    }

    public function get_email_responsavel($i_alocacao)
    {
        $sql = "SELECT a.i_usuario
                       ,a.i_cidade_ibge as ibge
                       ,DATEFORMAT(a.dt_alocacao_ini,'DD/MM/YYYY hh:mm') AS dt_inicio
                       ,b.e_mail AS email
                FROM dba.cv_alocacoes AS a
                INNER JOIN dba.ge_usuarios AS b ON b.i_usuario = a.i_usuario
                WHERE a.i_alocacao = {$i_alocacao}";
        $query = $this->db->query($sql);
        $return = $query->row();
        $query->free_result();
        return $return;
    }

    public function getDadosRegistroSaida($i_alocacao)
    {
        $sql = "SELECT i_autorizacao
                    , i_alocacao
                    , i_patrimonio
                    , problemas
                    , nivel_combustivel
                    , DATEFORMAT(dt_inicial, 'DD/MM/YYYY') as data_inicial
                    , DATEFORMAT(dt_final, 'DD/MM/YYYY') as data_final
                    , DATEFORMAT(dt_inicial, 'hh:mm') as hora_inicial
                    , DATEFORMAT(dt_final, 'hh:mm') as hora_final
                    , km_inicial
                    , km_final
                FROM dba.cv_veiculos_autorizacao
                WHERE i_alocacao = {$i_alocacao}";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        $query->free_result();
        return $result;
    }

    public function getDadosCombustivel($dt_inicial, $dt_final)
    {
        $sql = "SELECT a.i_patrimonio
                    , SUM(litros) as litros
                    , (SELECT b.placa
                      FROM dba.cv_veiculos AS b
                      WHERE b.i_empresa = a.i_empresa
                      AND b.i_patrimonio = a.i_patrimonio) AS placa
                    , (SELECT b.marca_modelo
                      FROM dba.cv_veiculos AS b
                      WHERE b.i_empresa = a.i_empresa
                      AND b.i_patrimonio = a.i_patrimonio) AS veiculo
                    , (SELECT TOP 1 CONVERT(DOUBLE, SUM(b.km_final) - SUM(b.km_inicial))
                      FROM dba.cv_veiculos_autorizacao AS b
                      WHERE b.i_patrimonio = a.i_patrimonio
                      AND b.dt_inicial >= '{$dt_inicial}'
                      AND b.dt_final <= '{$dt_final}'
                      GROUP BY b.i_patrimonio) AS km_rodado
                FROM dba.cv_combustivel_veiculos AS a
                WHERE a.dt_sistema BETWEEN '{$dt_inicial}' and '{$dt_final}'
                GROUP BY a.i_patrimonio, a.i_empresa";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    /**
    * @author Henrique Cechinel
    */

    public function getCalendarRelatorio()
    {
        $sql = "SELECT a.i_alocacao
                    , a.i_patrimonio
                    , (select z.nome from dba.ge_usuarios as z where z.i_usuario = a.i_usuario) as nome_solicitante
                    , DATEFORMAT(a.dt_alocacao_ini, 'DD')||'/'||DATEFORMAT(a.dt_alocacao_ini, 'MM') AS data_inicio
                    , DATEFORMAT(a.dt_alocacao_fim, 'DD')||'/'||DATEFORMAT(a.dt_alocacao_fim, 'MM') AS data_fim
                    , DATEFORMAT(a.dt_alocacao_ini, 'YYYY-MM-DD') AS dia_inicio
                    , DATEFORMAT(a.dt_alocacao_fim, 'YYYY-MM-DD') AS dia_fim
                    , DATEFORMAT(a.dt_alocacao_ini, 'HH:MM') AS hr_inicio
                    , DATEFORMAT(a.dt_alocacao_fim, 'HH:MM') AS hr_fim
                    , (SELECT z.marca_modelo
                        FROM dba.cv_veiculos AS z
                        WHERE z.i_patrimonio = a.i_patrimonio) as modelo
                    , (SELECT z.placa
                        FROM dba.cv_veiculos AS z
                        WHERE z.i_patrimonio = a.i_patrimonio) as placa
                    , (SELECT b.nome||' - '||b.uf
                            FROM dba.ge_cidades_ibge AS b
                            WHERE b.i_cidade_ibge=a.i_cidade_ibge) AS destino
                    , a.status_aprov
                FROM dba.cv_alocacoes AS a
                WHERE a.status_aprov != 'R'
                AND dia_inicio = DATEFORMAT(now(), 'YYYY-MM-DD')
                ORDER BY a.i_patrimonio";
        $query = $this->db->query($sql);
        $lista = $query->result_array();
        return $lista;
    }

    public function getCountVeiculos()
    {
        $sql = "SELECT a.placa
                        , a.marca_modelo
                        , a.i_patrimonio
                        , (SELECT count(1)
                            FROM dba.cv_alocacoes as b
                            WHERE DATEFORMAT(b.dt_alocacao_ini, 'YYYY-MM-DD') = DATEFORMAT(now(), 'YYYY-MM-DD')
                            AND b.status_aprov = 'A'
                            AND a.i_patrimonio = b.i_patrimonio
                            GROUP BY b.i_patrimonio
                            ORDER BY b.i_patrimonio) as count
                FROM dba.cv_veiculos AS a
                LEFT join dba.pa_patrimonios as b ON  b.i_patrimonio = a.i_patrimonio
                WHERE a.DISPONIVEL = 'S'
                AND a.i_patrimonio > 1
                AND b.status ='A'
                ORDER BY a.i_patrimonio";
        $query = $this->db->query($sql);
        $lista = $query->result_array();
        return $lista;
    }
}
