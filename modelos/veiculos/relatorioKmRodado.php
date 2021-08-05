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
        font-size: 12px;
        padding: 7px;
    }
</style>

<?php
    $this->load->view($cabecalho);
    if (isset($dt_inicial) && isset($dt_final)) {
        echo "<span><b>Filtrado por data: </b></span>";
        echo "<span>{$dt_inicial} até {$dt_final}</span>";
    }
    if (count($relatorio_dados) > 0) {
?>
        <div class="bloco-detalhes">
            <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-left">Unidade</th>
                        <th class="text-center">Placa</th>
                        <th class="text-left">Veículo</th>
                        <th class="text-center">Data inicial</th>
                        <th class="text-center">Data final</th>
                        <th class="text-center">Quilometragem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($relatorio_dados as $key => $value) { ?>
                        <tr>
                            <td><?php echo $value['nome_unidade']; ?></td>
                            <td class="text-center"><?php echo $value['placa']; ?></td>
                            <td class="text-left"><?php echo $value['veiculo']; ?></td>
                            <td class="text-center"><?php echo $value['dt_alocacao_inicial']; ?></td>
                            <td class="text-center"><?php echo $value['dt_alocacao_final']; ?></td>
                            <td class="text-center"><?php echo $value['km_rodado']; ?> km</td>
                        </tr>
                    <?php } ?>
                        <tr>
                            <th colspan="6">Total de quilômetros rodados: <?php echo $total_km ?> km</th>
                        </tr>
                </tbody>
            </table>
        </div>
<?php
    } else {
    ?>
        <div class="row" style="margin-top: 20px;">
            <div class="col-sm-12">
                <p class="alert alert-info text-center">
                    <strong>Nenhum registro encontrado!</strong>
                </p>
            </div>
        </div>
    <?php
    }
?>
