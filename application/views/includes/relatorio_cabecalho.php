<?php
echo "<table width='100%' style='text-align:right;border-bottom:1px solid #000' class='rel_cabecalho'>
        <tr>
            <td style='text-align:left;' rowspan='4' width='15%'><img src='assets/img/logo.png' height='90' width='125'></td>
            <td style='text-align:center;'><b>lufa<b></td>
            <td width='5%' nowrap='nowrap'>Data:</td>
            <td width='5%' nowrap='nowrap' style='text-align:right;'>".date('d/m/Y')."</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Hor&aacute;rio:</td>
            <td style='text-align:right;'>".date("H:i:s")."</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style='text-align:center;'><b>".utf8_decode($cabecalho_titulo)."</b></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
      </table>";
?>