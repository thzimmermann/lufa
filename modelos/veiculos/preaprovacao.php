<?php
    echo link_tag('assets_novo/css/hint.css');
    echo link_js('assets/js/angular.min.js');
    echo link_js("assets/js/angular-messages.min.js");
?>
<script>
    function alterarVeiculo(i_alocacao) {
        $('#i_alocacao_mudar').val(i_alocacao);
    }

    function chamaKm(event, i_patrimonio, field) {
        if(event.which == 13) {
            setKmMinimo(i_patrimonio, field);
            return false;
        }
    }

    function setKmMinimo(i_patrimonio, field)
    {
        var km = $('#'+field).val();
        var dados = {km_minimo:km,i_patrimonio:i_patrimonio}
        var retorno = call_valor("veiculos/setKmMinimo", dados);
        if (retorno == 1) {
            alert('Salvo com sucesso!');
        }
    }

    function setTab(num)
    {
        $('.tab-content').removeClass('hide');
        $('.tab-content').addClass('hide');
        $('.tab'+num).removeClass('hide');
        $('ul.nav-tabs > li').removeClass('active');
        $('.tab-li'+num).addClass('active');
    }

    function formAprovacao(i_alocacao)
    {
        $("#i_alocacao").val(i_alocacao);
    }

    function mostra_campo(status)
    {
        if (status == 'R') {
            $(".motivo").removeClass("hide");
            $(".patrimonio").addClass("hide");
        } else {
            $(".motivo").addClass("hide");
            $(".patrimonio").removeClass("hide");
        }
    }

    function salva_aprovacao_veiculo()
    {
        $("#form_salvar_aprovacao_veiculo").submit();
    }

    function cancela_agendado(i_alocacao) {
        if (confirm('Deseja realmente cancelar o agendamento!')) {
            var retorno = call_valor("veiculos/cancela_agendado", {valor:i_alocacao});
            if (retorno = 1) {
                alert('Cancelado com sucesso!');
                location.reload();
            }
        }
    }

    function salva_resumo() {
        $("#form_salvar_resumo").submit();
    }

    function substituiVirgula(campo) {
        campo.value = campo.value.replace(/,/gi, ".");
    }

    function addCategoria () {
        var nome_grupo = $("input[name=nome_grupo]");

        if ($.trim(nome_grupo.val()) == '') {
            nome_grupo.css({
                border: '1px solid #FF0000',
                background: '#FFEFEF'
            });
            alert('Preencha o nome da categoria!');
        } else {
            var retorno = call_valor("veiculos/setCategoriaVeiculos", {nome_grupo: nome_grupo.val()});
            if (retorno > 0) {
                $('#modal-addCategoria').modal('hide');
                alert('Categoria adicionada com sucesso!');
                nome_grupo.css({
                    border: '',
                    background: ''
                });
                nome_grupo.val('');
                location.reload();
            };
        };
    }

    function editaCategoria (i_grupo, i_empresa, i_patrimonio) {
        $("#modal-editaCategoria").modal('show');
        $(".op-categoria").each(function(index, el) {
            if (el.value == i_grupo) {
                $(this).attr('selected', 'selected');
            };
        });
        $("input[name=i_empresa]").val(i_empresa);
        $("input[name=i_patrimonio]").val(i_patrimonio);

        $(this).click(function(event) {
            event.preventDefault();
        });
    }

    function salvaCategoria () {
        var i_empresa = $("input[name=i_empresa]").val();
        var i_patrimonio = $("input[name=i_patrimonio]").val();
        var i_grupo = $("#categoria option:selected").val();

        var retorno = call_valor("veiculos/setCategoriaVeiculos", {i_empresa: i_empresa, i_patrimonio: i_patrimonio, i_grupo: i_grupo, editar: 1});

        if (retorno > 0) {
            alert('Categoria alterada com sucesso!');
            location.reload();
        };
    }

    var appVeiculo = angular.module('appVeiculo',['ngMessages']);
    appVeiculo.controller('alocacaoCtrl', function($scope) {

        $scope.getResumoAlocacao = function(i_alocacao) {
            $scope.formResumo = JSON.parse(call_valor("veiculos/getResumoAlocacao",{i_alocacao:i_alocacao}));
        };

        $scope.setResumo = function(formResumo) {
            var retorno = call_valor("veiculos/setResumo",formResumo);

            if (retorno==1) {
                alert('Salvo com sucesso!');
                location.reload();
            }
        };

        $scope.calculaKmPercorrido = function(kmInicial, kmFinal) {
            if(kmFinal==''){
                $scope.formResumo.km_percorrida = 0;
            }else{
                $scope.formResumo.km_percorrida = kmFinal - kmInicial;
            }
        };

        $scope.calculaValorCombustivel = function(vl_combustivel, km_percorrida, km_litro) {
            $scope.formResumo.vl_gasto = (km_percorrida / km_litro)*number_format(vl_combustivel,2);
            $scope.formResumo.vl_gasto = number_format($scope.formResumo.vl_gasto,2);
        };

        $scope.validaCampo = function (campo, status_form) {
            if (status_form && campo.$invalid) {
                return 'alert-danger';
            } else {
                return '';
            }
        }
        $scope.validaCampokm = function (campo, km_percorrida, status_form) {
            if (km_percorrida<0) {
                return 'alert-danger';
            } else {
                return '';
            }
        }
    });
</script>
<div ng-app="appVeiculo" ng-controller="alocacaoCtrl">
    <div class="row"><?php $this->load->view("interface_titulo", $titulo_interface)?></div>
    <div class="row">
        <div class="modal fade" id="modal-aprovacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title-aprovacao" id="myModalLabel">Aprova&ccedil;&atilde;o do Agendamento</h4>
                    </div>
                    <div class="modal-body">
                            <?php
                                echo satc_form_open(
                                    'form_salvar_aprovacao_veiculo',
                                    'veiculos/setAprovacaoCoordenador',
                                    'veiculos/preaprovacao',
                                    '',
                                    array(),
                                    0,
                                    'form-horizontal'
                                );
                            ?>
                            <input type="hidden" id="i_alocacao" name="i_alocacao" value="">
                            <div class="row">
                                <div class="form-group">
                                    <label for="status" class="col-sm-3 control-label">Status: </label>
                                    <div class="col-sm-6">
                                        <input onclick="mostra_campo(this.value)" checked="true" type="radio" name="status" id="status1" value="D"> Aprovar &nbsp;
                                        <input onclick="mostra_campo(this.value)" type="radio" name="status" id="status2" value="R"> Reprovar
                                    </div>
                                </div>
                            </div>
                            <div class='motivo hide'>
                                <br/>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="motivo" class="col-sm-3 control-label">Motivo: </label>
                                        <div class="col-sm-7">
                                            <textarea rows="3" class="form-control" id="motivo" name="motivo" placeholder="Motivo"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="salva_aprovacao_veiculo()">Salvar aprova&ccedil;&atilde;o</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="tabbable tabs-left tabbable-bordered">
            <ul class="nav nav-tabs">
                <li class='active tab-li1'>
                    <a onclick="setTab(1)" href="#">
                        Aguardando <span class='badge'><?php echo count($aguardando); ?></span>
                    </a>
                </li>
                <li class='tab-li4'>
                    <a onclick="setTab(4)" href="#">
                        Aprovados <span class='badge alert-info'><?php echo count($aprovados_direcao); ?></span>
                    </a>
                </li>
                <!-- <li class='tab-li2'>
                    <a onclick="setTab(2)" href="#">
                        Aprovados pelo setor de Ve&iacute;culos <span class='badge alert-success'><?php //echo count($aprovados); ?></span>
                    </a>
                </li> -->
                <li class='tab-li3'>
                    <a onclick="setTab(3)" href="#">
                        Reprovados <span class='badge alert-danger'><?php echo count($reprovados); ?></span>
                    </a>
                </li>
            </ul>
            <div class="tab-content tab1">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-hover table-striped">
                            <thead>
                              <tr>
                                  <th></th>
                                  <th>Solicitante</th>
                                  <th>Condutor</th>
                                  <th width="20%" colspan="2" class="text-center">Hor&aacute;rio uso</th>
                                  <th>Local</th>
                                  <th>Unidade</th>
                                  <th>Motivo</th>
                                  <th class="text-center">Nº Passageiros</th>
                                  <th class='text-center'>Aprova&ccedil;&atilde;o</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($aguardando)==0) {
                                    echo '<tr><td colspan="10" class="text-center">Nenhum registro!</td></tr>';
                                } else {
                                    $cont = 0;
                                    foreach ($aguardando as $a) {
                                        $cont++;
                                        echo '<tr>';
                                        echo "  <td>{$cont}</td>";
                                        echo "  <td>{$a['solicitante']}</td>";
                                        echo "  <td>{$a['condutor']}</td>";
                                        echo "  <td class='text-center'><strong>{$a['dt_alocacao_ini']}</strong></td>";
                                        echo "  <td class='text-center'><strong>{$a['dt_alocacao_fim']}</strong></td>";
                                        if (isset($a['cidade'])) {
                                            echo "  <td>{$this->satc->utf8_encode($a['cidade'])}</td>";
                                        } else {
                                            echo "  <td>&nbsp;</td>";
                                        }
                                        echo "  <td>{$this->satc->utf8_encode($a['nome_unidade'])}</td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['justificativa'])}</td>";
                                        echo "  <td class='text-center'>{$a['n_passageiros']}</td>";
                                        echo "  <td class='text-center'>
                                                    <button
                                                        data-toggle='modal'
                                                        data-target='#modal-aprovacao'
                                                        onclick=\"formAprovacao('{$this->satc->codificar($a['i_alocacao'])}')\"
                                                        class='btn btn-default btn-sm'>
                                                        <span class='glyphicon icon-ok'></span>
                                                        Responder
                                                    </button>
                                                </td>";
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-content tab4 hide">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-hover table-striped">
                            <thead>
                              <tr>
                                  <th></th>
                                  <th>Solicitante</th>
                                  <th>Condutor</th>
                                  <th width="20%" colspan="2" class="text-center">Hor&aacute;rio uso</th>
                                  <th>Local</th>
                                  <th>Unidade</th>
                                  <th>Motivo</th>
                                  <th class="text-center">Nº Passageiros
                                  <th class="text-center" width="10%">Situa&ccedil;&atilde;o</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($aprovados_direcao)==0) {
                                    echo '<tr><td colspan="10" class="text-center">Nenhum registro!</td></tr>';
                                } else {
                                    $cont = 0;
                                    foreach ($aprovados_direcao as $a) {
                                        $cont++;
                                        echo '<tr>';
                                        echo "  <td>{$cont}</td>";
                                        echo "  <td>{$a['solicitante']}</td>";
                                        echo "  <td>{$a['condutor']}</td>";
                                        echo "  <td class='text-center'><strong>{$a['dt_alocacao_ini']}</strong></td>";
                                        echo "  <td class='text-center'><strong>{$a['dt_alocacao_fim']}</strong></td>";
                                        if (isset($a['cidade'])) {
                                            echo "  <td>{$this->satc->utf8_encode($a['cidade'])}</td>";
                                        } else {
                                            echo "  <td>&nbsp;</td>";
                                        }
                                        echo "  <td>{$this->satc->utf8_encode($a['nome_unidade'])}</td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['justificativa'])}</td>";
                                        echo "  <td class='text-center'>{$a['n_passageiros']}</td>";
                                        echo "<td class='text-center'>";
                                        if ($a['status_aprov'] == 'A') {
                                            echo "<p class='font-icone'><i class='glyphicon glyphicon-ok-sign verde'></i></p>Aprovado";
                                        } else if ($a['status_aprov'] == 'D') {
                                            echo "<p class='font-icone'><i class='glyphicon glyphicon-minus-sign amarelo'></i></p>Aguardando ve&iacute;culo";
                                        } else {
                                            echo "<p class='font-icone'><i class='glyphicon glyphicon-remove vermelho'></i></p>Reprovado";
                                        }
                                        echo "</td>";
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <div class="tab-content tab2 hide">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-hover table-striped">
                            <thead>
                              <tr>
                                  <th></th>
                                  <th>Condutor</th>
                                  <th>Ve&iacute;culo</th>
                                  <th width="20%" colspan="2" class="text-center">Hor&aacute;rio uso</th>
                                  <th>Local</th>
                                  <th>Unidade</th>
                                  <th class="text-center">Nº Passageiros</th>
                                  <th class="text-center">Km percorrida</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                /*if (count($aprovados)==0) {
                                    echo '<tr><td colspan="10" class="text-center">Nenhum registro!</td></tr>';
                                } else {
                                    $cont = 0;
                                    foreach ($aprovados as $a) {
                                        $cont++;
                                        $km_percorrida = str_replace('.', ',', $a['km_percorrida']).' KM';
                                        echo '<tr>';
                                        echo "  <td>{$cont}</td>";
                                        echo "  <td>{$a['condutor']}</td>";
                                        echo "  <td>{$a['marca_modelo']} - {$a['placa']}</td>";
                                        echo "  <td class='text-center'><strong>{$a['dt_alocacao_ini']}</strong></td>";
                                        echo "  <td class='text-center'><strong>{$a['dt_alocacao_fim']}</strong></td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['justificativa'])}</td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['nome_unidade'])}</td>";
                                        echo "  <td class='text-center'>{$a['n_passageiros']}</td>";
                                        echo "  <td class='text-center'>{$km_percorrida}</td>";
                                        echo '</tr>';
                                    }
                                }*/
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->
            <div class="tab-content tab3 hide">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-hover table-striped">
                            <thead>
                              <tr>
                                  <th></th>
                                  <th>Solicitante</th>
                                  <th>Condutor</th>
                                  <th>Ve&iacute;culo</th>
                                  <th width="20%" colspan="2" class="text-center">Hor&aacute;rio uso</th>
                                  <th>Local</th>
                                  <th>Unidade</th>
                                  <th class="text-center">Nº Passageiros</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($reprovados)==0) {
                                    echo '<tr><td colspan="10" class="text-center">Nenhum registro!</td></tr>';
                                } else {
                                    $cont = 0;
                                    foreach ($reprovados as $a) {
                                        $cont++;
                                        echo '<tr>';
                                        echo "  <td>{$cont}</td>";
                                        echo "  <td>{$a['solicitante']}</td>";
                                        echo "  <td>{$a['condutor']}</td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['marca_modelo'])} - {$a['placa']}</td>";
                                        echo "  <td class='text-center'><strong>{$a['dt_alocacao_ini']}</strong></td>";
                                        echo "  <td class='text-center'><strong>{$a['dt_alocacao_fim']}</strong></td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['justificativa'])}</td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['nome_unidade'])}</td>";
                                        echo "  <td class='text-center'>{$a['n_passageiros']}</td>";
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
