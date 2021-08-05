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
    /*if (isset($dt_inicial_i) && isset($dt_final_i)) {
        echo '<br><b>Data: </b>'.$dt_inicial_i.' at&eacute; '.$dt_final_i;
    }*/
    if (count($listarCombustiveis)>0) {
?>
        <div class="bloco-detalhes">
            <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Ve&iacute;culo</th>
                        <th>Unidade</th>
                        <th class="text-center" width="10%">Combust&iacute;vel</th>
                        <th class="text-center" width="10%">Litros(l)</th>
                        <th class="text-center" width="10%">Valor(R$)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $litros = 0;
                $valor = 0;
                foreach ($listarCombustiveis as $key => $value) {
                if ($value['combustivel'] == 'G') {
                    $value['combustivel'] = 'Gasolina';
                } else if ($value['combustivel'] == 'A') {
                    $value['combustivel'] = 'Alcool';
                } else {
                    $value['combustivel'] = 'Diesel';
                }
                $litros+= $value['litros'];
                $valor+= $value['valor'];
                ?>
                    <tr>
                        <td><?php echo utf8_encode($value['veiculo']); ?></td>
                        <td><?php echo $value['i_unidade_mask'].' - '.utf8_encode($value['nome_unidade']); ?></td>
                        <td class="text-center"><?php echo $value['combustivel']; ?></td>
                        <td class="text-right"><?php echo $this->satc->formatar_moeda($value['litros'], 'br'); ?></td>
                        <td class="text-right"><?php echo 'R$ '.$this->satc->formatar_moeda($value['valor'], 'br'); ?> </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td colspan="1" class="text-right"><?php echo $this->satc->formatar_moeda($litros); ?> </td>
                        <td colspan="1" class="text-right">R$ <?php echo $this->satc->formatar_moeda($valor); ?> </td>
                    </tr>
                </tfoot>
            </table>
        </div>
<?php
    } else {
        echo '<p><strong>Nenhum registro encontrado!</strong></p>';
    }
?>
