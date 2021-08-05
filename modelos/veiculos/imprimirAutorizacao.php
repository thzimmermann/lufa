<style>
    .bloco-detalhes {
        width: 100%;
        display: block;
        margin-top: 10px;
    }

    .rel_cabecalho {
        margin-bottom: 2%;
        font-size: 14px;
    }

    h4 {
        text-align: center;
        width: 100%;
    }

    thead tr th {
        background-color: #f3f3f3;
    }

    tfoot tr th {
        background-color: #f3f3f3;
        font-size: 14px;
    }

    #dados td {
        font-size: 14px;
        padding: 7px;
    }

    #dados th {
        font-size: 14px;
    }

    .borda{
        border: 1px solid #ddd;
        padding: 10px;
        background-color: #F4F4F4;
        border-radius: 5px;
    }
    #dados tbody tr:nth-child(odd) {
       background-color: #fffafa;
    }
    .rodape  {
        color: #000;
        background-color: #000;
        height: 1px;
        width: 300px;
        border-top: 1px dashed #000;
    }

</style>
<?php
    if (count($dados)>0) {
?>
        <div class="bloco-detalhes">
        <?php
echo "<table width='100%' style='text-align:right;border-bottom:1px solid #000' class='rel_cabecalho'>
        <tr>
            <td style='width:125px; height:90px;' >".img("assets_novo/img/logo_satc_novo.png")."</td>
            <td style='text-align:center; font-size: 32px;'><b>REGISTRO DE SAÍDA DE VEÍCULOS</b></td>
        </tr>
      </table>";
?>
          <table id="dados" width="100%" class="table table-nomargin dataTable table-bordered table-hover">
            <tfoot>
              <tr>
                  <td colspan="4"><b>CONDUTOR:</b> <?php echo $this->satc->utf8_encode($dados['condutor']); ?></td>
                  <td colspan="2"><b>DESTINO:</b> <?php echo $dados['destino']; ?></td>
              </tr>
              <tr>
                  <td colspan="2"><b>Nº DA CNH:</b> <?php echo $dados['numero_carteira']; ?></td>
                  <td colspan="2"><b>VENCIMENTO DA CNH:</b> <?php echo $dados['dt_validade_cnh']; ?></td>
                  <td colspan="2"><b>PASSAGEIROS:</b> <?php echo $dados['n_passageiros']; ?></td>
              </tr>
              <tr>
                  <td colspan="3"><b>VE&Iacute;CULO:</b> <?php echo $dados['placa'].' - '.$this->satc->utf8_encode($dados['veiculo']); ?></td>
                  <td colspan="3"><b>CENTRO DE CUSTO:</b> <?php echo $this->satc->utf8_encode($dados['nome_unidade']); ?></td>
              </tr>
              <tr>
                  <td colspan="3"><b>SA&Iacute;DA</b></td>
                  <td colspan="3"><b>CHEGADA</b></td>
              </tr>
              <?php
              if (count($autorizacao) > 0) {
              ?>
              <tr>
                <td colspan="1">DATA: <br> <?php echo $autorizacao['data_inicial']; ?></td>
                <td colspan="1">HORA: <br><?php echo $autorizacao['hora_inicial']; ?></td>
                <td colspan="1">KM: <br><?php echo $autorizacao['km_inicial']; ?></td>
                <td colspan="1">DATA: <br> <?php echo $autorizacao['data_final']; ?></td>
                <td colspan="1">HORA: <br><?php echo $autorizacao['hora_final']; ?></td>
                <td colspan="1">KM: <br><?php echo $autorizacao['km_final']; ?></td>
              </tr>
              <?php
              } else {
              ?>
              <tr>
                <td colspan="1">DATA: <br> ___ / ___ / _____</td>
                <td colspan="1">HORA: <br>___:___</td>
                <td colspan="1">KM: <br>________</td>
                <td colspan="1">DATA: <br> ___ / ___ / _____</td>
                <td colspan="1">HORA: <br>___:___</td>
                <td colspan="1">KM: <br>________</td>
              </tr>
              <?php
              }
              ?>
              <tr>
                <td colspan="3" class="text-center" rowspan="1"><b>MARCAR N&Iacute;VEL DO COMBUST&Iacute;VEL</b></td>
                <td colspan="3" class="text-center" rowspan="1"><b>PROBLEMAS DO VE&Iacute;CULO</b></td>
              </tr>
              <?php
              if (count($autorizacao) > 0) {
              ?>
              <tr>
                <td colspan="3" class="text-center" rowspan="3">
                  <?php echo img("assets_novo/img/combustivel.png"); ?>
                  <br>
                  <strong>
                    <?php echo $autorizacao['nivel_combustivel'].'%'; ?>
                  </strong>
                </td>
                <td colspan="3" class="text-center" rowspan="3">
                  <?php echo $autorizacao['problemas']; ?>
                </td>
              </tr>
              <?php
              } else {
              ?>
              <tr>
                <td colspan="3" class="text-center" rowspan="3"><?php echo img("assets_novo/img/combustivel.png"); ?></td>
                <td colspan="3" class="text-center" rowspan="3">___________________________________ <br>___________________________________ <br>___________________________________ <br>___________________________________</td>
              </tr>
              <?php
              }
              ?>
            </tfoot>
          </table>
        </div>
        <br>
        <div>
          <div class="text-left" >
            <span>*Devolver esse documento preenchido juntamente com a chave do ve&iacute;culo</span>
          </div>
        </div>
        <br>
        <br>
        <div>
          <div class="text-center" style="text-align:center;">
            <hr class="rodape">
            <span>Assinatura do Usu&aacute;rio</span>
          </div>
        </div>
<?php
    } else {
        echo '<p class=\'text-center\'><strong>Nenhum registro encontrado!</strong></p>';
    }
?>
