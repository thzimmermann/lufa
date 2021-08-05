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
    echo '<br><b>Unidade: </b>'.$i_unidade.' - '.$nome_unidade;
    if (isset($dt_inicial_i) && isset($dt_final_i)) {
        echo '<br><b>Data: </b>'.$dt_inicial_i.' at&eacute; '.$dt_final_i;
    }
    if (count($listarCombustiveis)>0) {
?>
        <div class="bloco-detalhes">
            <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Ve&iacute;culo</th>
                        <th class="text-center" width="10%">Combust&iacute;vel</th>
                        <th class="text-center" width="10%">Litros(l)</th>
                        <th class="text-center" width="10%">Valor(R$)</th>
                        <th class="text-center" width="20%">Data/Hora Abastecimento</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($listarCombustiveis as $key => $value) {
                ?>
                    <tr>
                        <td><?php echo $listarCombustiveis[$key]['veiculo']; ?></td>
                        <td class="text-center"><?php echo $listarCombustiveis[$key]['combustivel_view']; ?></td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($listarCombustiveis[$key]['litros'], 'br'); ?></td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($listarCombustiveis[$key]['valor'], 'br'); ?> </td>
                        <td class="text-center"><?php echo $listarCombustiveis[$key]['dt_abastecimento_format']; ?></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <table id="dados_total" style="width: 50%;" class="table table-nomargin dataTable table-bordered table-hover">
            <thead>
                    <tr>
                        <th class="text-center" colspan="2"><?php echo $i_unidade.' - '.$nome_unidade; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Litros Gasolina(l): </td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($total_combustiveis[0]['litros_g'], 'br'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Gasolina(R$): </td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($total_combustiveis[0]['valor_g'], 'br'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Litros Diesel(l): </td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($total_combustiveis[0]['litros_d'], 'br'); ?> </td>
                    </tr>
                    <tr>
                        <td class="text-left">Diesel(R$): </td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($total_combustiveis[0]['valor_d'], 'br'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Litros Etanol(l): </td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($total_combustiveis[0]['litros_a'], 'br'); ?> </td>
                    </tr>
                    <tr>
                        <td class="text-left">Etanol(R$): </td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($total_combustiveis[0]['valor_a'], 'br'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Litros Interno(l): </td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($total_combustiveis[0]['litros_i'], 'br'); ?> </td>
                    </tr>
            </thead>
        </table>
        <!--<div class="borda">
            <p><b> //echo $i_unidade.' - '.$nome_unidade; ?>: </b></p>
            <ul>
                <li>Litros Gasolina(l):  //echo $this->satc->formatar_moeda($total_combustiveis[0]['litros_g'], 'br'); ?></li>
                <li>Gasolina(R$):  //echo $this->satc->formatar_moeda($total_combustiveis[0]['valor_g'], 'br'); ?></li>
                <li>Litros Diesel(l):  //echo $this->satc->formatar_moeda($total_combustiveis[0]['litros_d'], 'br'); ?> </li>
                <li>Diesel(R$):  //echo $this->satc->formatar_moeda($total_combustiveis[0]['valor_d'], 'br'); ?></li>
            </ul>
        </div>-->
<?php
    } else {
        echo '<p><strong>Nenhum registro encontrado!</strong></p>';
    }
?>
