<!DOCTYPE html>
<html>
  <style>

    body {
      font-family: arial;
    }

    table.tabelaPadrao { 
      border-collapse:collapse; 
      width:98%;
      border:1px solid #E3DAD1; 
      font-size:12px; 
    }

    table.tabelaPadrao thead tr th { 
      background-color:#EAEAEA;
    }

    table.tabelaPadrao tr td, table.tabelaPadrao tr th { 
      padding:4px; 
      border-right:1px solid #E3DAD1;
      height: 35px; 
    }

    table.tabelaPadrao tbody tr th { 
      background-color:#F3EFEC; 
      vertical-align:top; 
      font-weight:normal; 
      width:19%; 
    }

    table.tabelaPadrao tbody tr td { 
      border-top:1px solid #E3DAD1; 
    }

    .center {
        text-align: center;
    }
    
  </style>

  <body>
    <h1 class="center">CALEND&Aacute;RIO DE AGENDAMENTOS DE VE&Iacute;CULOS </h1>
    <h2 class="center"><?php echo "Dia: ".date("d/m/Y") ?></h2>
    <br>

    <div>
        <table class="tabelaPadrao">
            <thead>
                <tr>
                    <th width="14%" class="center">VE&Iacute;CULO</th>
                    <!-- <th width="6%" class="center">DATA</th> -->
                    <th width="25%" style="padding-left: 10px;">USU&Aacute;RIO</th>
                    <th width="10%" class="center">SA&Iacute;DA / VOLTA</th>
                    <th width="27%" class="center">DESTINO</th>
                    <th width="8%" class="center">ENTREGUE</th>
                    <th width="16%" class="center">CONTATO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($count_veiculos as $key => $value) {

                    ($value['count'] > 2 ? $rowspan = ($value['count'] + 1) : $rowspan = 3) ?>

                    <tr>
                        <td rowspan="<?php echo $rowspan ?>" class="center"><?php echo substr($value['placa'], 0, 3).'-'.substr($value['placa'], 3, 5).'<br>'.$value['marca_modelo']; ?></td> <?php                                          
                        if ($value['count'] > 0) {
                            $aux = 0;
                            foreach ($list_calendar as $k => $v) {
                                if ($v['i_patrimonio'] == $value['i_patrimonio']) { 
                                    if ($aux > 0) { ?>
                                        <tr> <?php  
                                    } 
                                    $aux++; ?>
                                        <!-- <td class="center"><?php echo ($v['data_inicio']); ?></td> -->
                                        <td style="padding-left: 10px;"><?php echo ($v['nome_solicitante']); ?></td>
                                        <td class="center"><?php echo ($v['hr_inicio']).' / '.($v['hr_fim']); ?></td>
                                        <td class="center"><?php echo ($v['destino']); ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr> <?php
                                    if($aux == $value['count']) {
                                        if (3 - $value['count'] <= 1) { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr> <?php 
                                        } else if (3 - $value['count'] == 2) { 
                                            for ($i=0; $i < 2 ; $i++) { ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> <?php
                                            }
                                        } else if (3 - $value['count'] == 3) {
                                            for ($i=0; $i < 3 ; $i++) { ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> <?php
                                            }
                                        }
                                    }
                                }
                            }
                        } else { ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> <?php
                            for ($i=0; $i < $rowspan - 1 ; $i++) { ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr> <?php
                            }
                        }
                } ?>
                </tbody>
        </table>
    </div>

    
  </body>
</html>