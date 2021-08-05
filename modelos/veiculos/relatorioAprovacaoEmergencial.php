<style>
    .bloco-detalhes {
        width: 100%;
        display: block;
        margin-top: 10px;
    }

    .rel_cabecalho {
        margin-bottom: 2%;
        font-size: 12px;
    }

    h4 {
        text-align: center;
        width: 100%;
    }

    #dados td {
        font-size: 10px;
        padding: 7px;
    }

    ul {
        display: block;
        list-style-type: disc;
        margin-top: 1em;
        margin-bottom: 1 em;
        margin-left: 0;
        margin-right: 0;
        padding-left: 40px;
    }

    .borda{
        border: 1px solid #ddd;
        padding: 10px;
        background-color: #F4F4F4;
        border-radius: 5px;
    }
</style>

<?php
    $this->load->view($cabecalho);
    if (isset($dt_inicial_i) && isset($dt_final_i)) {
        echo '<br><b>Data: </b>'.$dt_inicial_i.' at&eacute; '.$dt_final_i;
    }
    if (count($listaAlocacoes)>0) {
?>
        <div class="bloco-detalhes">
            <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                <thead>
                  <tr>
                      <th></th>
                      <th>Solicitante</th>
                      <th>Condutor</th>
                      <th width="20%" colspan="2" class="text-center">Hor&aacute;rio uso</th>
                      <th>Local</th>
                      <th>Unidade</th>
                      <th>Motivo</th>
                      <th class="text-center">Nº Passageiros</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($listaAlocacoes)==0) {
                        echo '<tr><td colspan="10" class="text-center">Nenhum registro!</td></tr>';
                    } else {
                        $cont = 0;
                        foreach ($listaAlocacoes as $a) {
                            $cont++;
                            echo '<tr>';
                            echo "  <td>{$cont}</td>";
                            echo "  <td>{$a['solicitante']}</td>";
                            echo "  <td>{$a['condutor']}</td>";
                            echo "  <td class='text-center'><strong>{$a['dt_alocacao_ini']}</strong></td>";
                            echo "  <td class='text-center'><strong>{$a['dt_alocacao_fim']}</strong></td>";
                            if (isset($a['cidade'])) {
                                echo "  <td>{$this->satc->utf8_encode($a['cidade'])}</td>";
                            } else {
                                echo "  <td>&nbsp;</td>";
                            }
                            echo "  <td>{$this->satc->utf8_encode($a['nome_unidade'])}</td>";
                            echo "  <td>{$this->satc->utf8_encode($a['justificativa'])}</td>";
                            echo "  <td class='text-center'>{$a['n_passageiros']}</td>";
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
<?php
    } else {
        echo '<p><strong>Nenhum registro encontrado!</strong></p>';
    }
?>
