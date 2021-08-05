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

    thead tr th {
        background-color: #CDCDCD;
    }

    tfoot tr th {
        background-color: #f3f3f3;
        font-size: 12px;
    }

    #dados td {
        font-size: 11px;
        padding: 7px;
    }

    #dados th {
        font-size: 12px;
    }

    .borda{
        border: 1px solid #ddd;
        padding: 10px;
        background-color: #F4F4F4;
        border-radius: 5px;
    }
    #dados tbody tr:nth-child(odd) {
       background-color: #d5d5d5;
    }
</style>
<?php
    if ($relatorio == 'E') {
        header('Content-type: application/vnd.ms-excel');
        header('Content-type: application/vnd.ms-excel');
        header('Content-type: application/force-download');
        header("Content-Disposition: attachment; filename=relatorioMovimentacoes.xls");
        header('Pragma: no-cache');
    } else {
        $this->load->view($cabecalho);
    }
    if (count($lista) > 0) {
?>
        <div class="bloco-detalhes">
            <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th style="text-align: center;">Data</th>
                        <th style="text-align: left;">Movimenta&ccedil;&atilde;o</th>
                        <th style="text-align: center;">Qtde</th>
                        <th style="text-align: center;">Valor unit&aacute;rio</th>
                        <th style="text-align: center;">Valor total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista as $key => $value) { ?>
                        <tr>
                            <td style="text-align: center;">
                                <?php echo $key+1; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $value['data']; ?>
                            </td>
                            <td style="text-align: left;">
                                <?php echo utf8_decode($value['local']=='I'?($value['status'] == 'C'?'Compras':'Vendas'):($value['status'] == 'S'?'Saída (Utilizações)':'Entrada (Utilizações)')); ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $value['qtde']; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo ($value['valor_uni'] > 0?"R$ {$value['valor_uni']}":0); ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo ($value['valor_total'] > 0?"R$ {$value['valor_total']}":0); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
<?php } else { ?>
        <div class="row" style="margin-top: 20px;">
            <div class="col-sm-12">
                <p class="alert alert-info text-center">
                    <strong>Nenhum registro encontrado!</strong>
                </p>
            </div>
        </div>
<?php } ?>
