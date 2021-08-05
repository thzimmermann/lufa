<?php
    header('Content-Type: text/html; charset=ISO-8859-1');
    $this->load->view("header_css");
?>
<div class="col-sm-12">
    <table class="table table-condensed table-bordered table-hover table_satc">
        <thead>
            <tr>
                <th width="25%" class="text-center">C&oacute;digo</th>
                <th>Nome</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($lista as $a) {
            $class = '';
            $valor = $a->i_func%2;
            if ($valor) {
                $class = 'negrito_linha';
            }
            echo "<tr class='cursor_pointer {$class}' onclick='selecionar_item({$a->i_func}, \"{$a->nome}\")'>";
            echo "<td class='text-center'>{$a->i_func}</td>";
            echo "<td>{$a->nome}</td>";
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>