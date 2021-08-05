<table style="width:98%;">
    <tr>
        <td width="40%">
        <?php
            echo form_dropdown('tipo', array('1'=>'Nome','2'=>'C&oacute;digo'), '', ' id="tipo" class="input-block-level"');
        ?>
        </td>
        <td width='45%'>
        <?php
            $input_pesquisar = array('name'=>'nome_pesquisa', 'id'=>'nome_pesquisa', 'value'=>'', 'maxlength'=>'100', 'class'=>'input-block-level');
            echo form_input($input_pesquisar);
        ?>
        </td>
        <td align='right'>
        <?php
            $button_pesquisar = array('name'=>'pesquisar_local', 'id'=>'pesquisar_local', 'type'=>'button', 'content'=>'Pesquisar', 'onclick'=>'consulta_local()', 'class'=>'btn');
            echo "<div class='input-append'>";
            echo form_button($button_pesquisar);
            echo "</div>";
        ?>
        </td>
    </tr>
</table>