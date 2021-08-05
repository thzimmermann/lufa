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

    if (isset($i_patrimonio)) {
        echo "<br>";
        echo "<span><b>Filtrado por ve&iacute;culo: </b></span>";
        echo "<span>{$placa}</span>";
    }

    if (count($relatorio_dados) > 0) {
?>
        <div class="bloco-detalhes">
            <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-left">Ve&iacute;culo</th>
                        <th class="text-left">Descri&ccedil;&atilde;o</th>
                        <th class="text-left">Tipo Manuten&ccedil;&atilde;o</th>
                        <th class="text-center">Custo</th>
                        <th class="text-center">Data Manutenção</th>
                        <th class="text-center">Quilometragem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($relatorio_dados as $key => $value) { ?>
                        <tr>
                            <td class="text-left"><?php echo $value['placa'].' - '.$value['veiculo']; ?></td>
                            <td class="text-left"><?php echo $value['descricao']; ?></td>
                            <td class="text-left"><?php echo $value['tipo_formatado']; ?></td>
                            <td class="text-right"><?php echo "R$ ".$this->satc->formatar_moeda($value['custo'], 'br'); ?></td>
                            <td class="text-center"><?php echo $value['dt_manutencao']; ?></td>
                            <td class="text-center"><?php echo $value['km']; ?> km</td>
                        </tr>
                    <?php } ?>
                        <tr>
                            <th colspan="6">Total dos custos: <?php echo "R$ ".$this->satc->formatar_moeda($total_custo, 'br'); ?></th>
                        </tr>
                </tbody>
            </table>
        </div>
        
        <?php if(isset($relatorio_mensal)){?>
            <div class="bloco-detalhes">
                <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">Data</th>
                            <th class="text-left" style="width: 10%">Corretiva</th>
                            <th class="text-left" style="width: 10%">Preventiva</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($relatorio_mensal as $key => $value) { ?>
                            <tr>
                                <td class="text-center"><?php echo $value['mes_ano']; ?></td>
                                
                                <td class="text-left"><?php echo "R$ ".$this->satc->formatar_moeda($value['corretiva'], 'br'); ?></td>

                                <td class="text-left"><?php echo "R$ ".$this->satc->formatar_moeda($value['preventiva'], 'br'); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php }?>
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
