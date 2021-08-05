<div class='row'>
    <div class="col-sm-3 bold">Sitau&ccedil;&atilde;o:</div>
    <div class="col-sm-6">
        <?php
        if ($alocacao['status_aprov'] == 'A') {
            echo "Aprovado";
        } elseif ($alocacao['status_aprov'] == 'R') {
            echo "Reprovado";
        } else {
            echo "Aguardando Aprova&ccedil;&atilde;o";
        }
        ?>
    </div>
</div>
<?php if ($alocacao['status_aprov']=='R'): ?>
    <br/>
    <div class='row'>
        <div class="col-sm-3 bold">Motivo reprova&ccedil;&atilde;o:</div>
        <div class="col-sm-8"><?php echo $alocacao['motivo_cancel']; ?></div>
    </div>
<?php endif ?>
<br/>
<div class='row'>
    <div class="col-sm-3 bold">Solicitante:</div>
    <div class="col-sm-8"><?php echo $alocacao['solicitante']; ?></div>
</div>
<br/>
<div class='row'>
    <div class="col-sm-3 bold">Retirada/Entrega:</div>
    <div class="col-sm-8"><?php echo $alocacao['dt_alocacao_ini'].' &agrave; '.$alocacao['dt_alocacao_fim']; ?></div>
</div>
<br/>
<div class='row'>
    <div class="col-sm-3 bold">Ve&iacute;culo:</div>
    <div class="col-sm-8"><?php echo $alocacao['marca_modelo'].' - '.$alocacao['placa']; ?></div>
</div>
<br/>
<div class='row'>
    <div class="col-sm-3 bold">Condutor:</div>
    <div class="col-sm-8"><?php echo $alocacao['condutor']; ?></div>
</div>
<br/>
<div class='row'>
    <div class="col-sm-3 bold">Local:</div>
    <div class="col-sm-8"><?php echo $alocacao['justificativa']; ?></div>
</div>
<br/>
<div class='row'>
    <div class="col-sm-3 bold">N&ordm; Passageiros: </div>
    <div class="col-sm-8"><?php echo $alocacao['n_passageiros']; ?></div>
</div>
<br/>
<div class='row'>
    <div class="col-sm-3 bold">KM Percorrido:</div>
    <div class="col-sm-8"><?php echo str_replace('.', ',', $alocacao['km_percorrida']); ?> KM</div>
</div>