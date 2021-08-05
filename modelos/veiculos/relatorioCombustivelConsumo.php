<style>
    .bloco-detalhes {
        width: 100%;
        display: block;
        margin-top: 12px;
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
    if ($dt_inicial!= '' && $dt_final != '') {
        echo "<span><b>Filtrado por data: </b></span>";
        echo "<span>{$dt_inicial} até {$dt_final}</span>";
    }
    if (count($listarCombustiveis)>1) {
?>
        <div class="bloco-detalhes">
            <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Ve&iacute;culo</th>
                        <th class="text-center" width="10%">Km rodado</th>
                        <th class="text-center" width="10%">Litros(l)</th>
                        <th class="text-center" width="20%">M&eacute;dia</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($listarCombustiveis as $key => $value) {
                    if ($value['placa'] != '') {


                ?>
                    <tr>
                        <td><?php echo substr($value['placa'], 0, 3).' - '.substr($value['placa'], 3, strlen($value['placa']));   ?></td>
                        <td><?php echo utf8_encode($value['veiculo']); ?></td>
                        <td class="text-center"><?php echo $value['km_rodado']; ?> </td>
                        <td class="text-center"><?php echo $value['litros']; ?></td>
                        <td class="text-center"><?php echo $value['km_rodado']>0?$this->satc->formatar_moeda($value['km_rodado']/$value['litros']):'0'; ?></td>
                    </tr>
                <?php
                    }
                }
                ?>
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
