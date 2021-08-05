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
</style>

<?php
    $this->load->view($cabecalho);
    if (isset($dt_inicial_i) && isset($dt_final_i)) {
        echo "<span><b>Filtrado por: </b></span><br>";
        echo "<span>Data: {$dt_inicial_i}até {$dt_final_i}</span>";
    }
    if (count($combustiveis)>0) {
?>
        <div class="bloco-detalhes">
            <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Setor</th>
                        <th class="text-center" width="10%">Litros Gasolina</th>
                        <th class="text-center" width="10%">Valor Gasolina</th>
                        <th class="text-center" width="10%">Litros Diesel</th>
                        <th class="text-center" width="10%">Valor Diesel</th>
                        <th class="text-center" width="10%">Litros Etanol</th>
                        <th class="text-center" width="10%">Valor Etanol</th>
                        <th class="text-center" width="10%">Litros Interno</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($combustiveis as $key => $value) {
                ?>
                    <tr>
                        <td><?php echo $this->satc->utf8_encode($combustiveis[$key]['nome_unidade']); ?></td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($combustiveis[$key]['litros_g'], 'br'); ?></td>
                        <td class="text-center"><?php echo 'R$'; echo $this->satc->formatar_moeda($combustiveis[$key]['valor_g'], 'br'); ?></td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($combustiveis[$key]['litros_d'], 'br'); ?> </td>
                        <td class="text-center"><?php echo 'R$'; echo $this->satc->formatar_moeda($combustiveis[$key]['valor_d'], 'br'); ?></td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($combustiveis[$key]['litros_a'], 'br'); ?> </td>
                        <td class="text-center"><?php echo 'R$'; echo $this->satc->formatar_moeda($combustiveis[$key]['valor_a'], 'br'); ?></td>
                        <td class="text-center"><?php echo $this->satc->formatar_moeda($combustiveis[$key]['litros_i'], 'br'); ?> </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th><b>Total de gastos: </b></th>
                        <th class="text-center"><b><?php echo $this->satc->formatar_moeda($totalCombustiveis['litros_g'], 'br'); ?></b></th>
                        <th class="text-center"><?php echo 'R$'; echo $this->satc->formatar_moeda($totalCombustiveis['valor_g'], 'br'); ?></b></th>
                        <th class="text-center"><?php echo $this->satc->formatar_moeda($totalCombustiveis['litros_d'], 'br'); ?> </th>
                        <th class="text-center"><?php echo 'R$'; echo $this->satc->formatar_moeda($totalCombustiveis['valor_d'], 'br'); ?></th>
                        <th class="text-center"><?php echo $this->satc->formatar_moeda($totalCombustiveis['litros_a'], 'br'); ?> </th>
                        <th class="text-center"><?php echo 'R$'; echo $this->satc->formatar_moeda($totalCombustiveis['valor_a'], 'br'); ?></th>
                        <th class="text-center"><?php echo $this->satc->formatar_moeda($totalCombustiveis['litros_i'], 'br'); ?> </th>

                    </tr>
                </tfoot>
            </table>
        </div>
<?php
    } else {
        echo '<p><strong>Nenhum registro encontrado!</strong></p>';
    }
?>
