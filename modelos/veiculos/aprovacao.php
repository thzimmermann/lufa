<?php
    echo link_tag('assets_novo/css/hint.css');
    echo link_js("assets/js/angular-messages.min.js");
    echo link_js('assets/js/angular-sanitize.min.js');
    echo link_js('assets_novo/js/angular-filter.min.js');
    echo link_js('assets_novo/js/masks.js');
    echo link_js('assets_novo/js/angular-locale_pt-br.js');
    echo link_tag('assets_novo/css/select2.css');
    echo link_tag('assets_novo/css/ebro_select2.css');
    echo link_js('assets_novo/js/select2.min.js');
?>
<style type="text/css">
    .select2-container {
        height: 40px;
    }

    textarea{
        resize:none;
    }
</style>
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

    $(document).ready(function() {
        $("#veiculosSelectAprov").select2();
        $("#veiculosSelectAprovMudar").select2();
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    });

    var appVeiculo = angular.module('appVeiculo',['ngMessages']);
    appVeiculo.controller('alocacaoCtrl', function($scope, $http, $filter) {
        $scope.veiculoOBS = false;
        $scope.dados = {};
        $scope.getResumoAlocacao = function(i_alocacao) {
            $scope.formResumo = JSON.parse(call_valor("veiculos/getResumoAlocacao",{i_alocacao:i_alocacao}));
        };

        $scope.setResumo = function(formResumo) {
            var retorno = call_valor("veiculos/setResumo",formResumo);
            if (retorno==1) {
                alert('Salvo com sucesso!');
                window.open('aprovacao','_self');
            }
        };

        $scope.calculaKmPercorrido = function(kmInicial, kmFinal) {
            if(kmFinal==''){
                $scope.formResumo.km_percorrida = 0;
            }else{
                $scope.formResumo.km_percorrida = kmFinal - kmInicial;
            }
        };

        $scope.buscarVeiculos = function(i_alocacao, data_inicial, data_final) {
            $("#i_alocacao").val(i_alocacao);
            $scope.dados = {};
            $scope.dados.i_alocacao = i_alocacao;
            $http({
                url: 'getListaVeiculosGrupo?data_inicial='+data_inicial+'&data_final='+data_final,
                method: 'GET'
            }).then(function (retorno) {
                $scope.listaVeiculos = retorno.data;
                $("#veiculosSelectAprov").select2();
            },
            function (retorno) {
                console.log('Error: '+retorno.status);
            });
        }

        $scope.buscarVeiculosAlterar = function(i_alocacao, data_inicial, data_final) {
            $("#i_alocacao_mudar").val(i_alocacao);
            $scope.dados_alterar = {};
            $scope.dados_alterar.i_alocacao = i_alocacao;
            $http({
                url: 'getListaVeiculosGrupo?data_inicial='+data_inicial+'&data_final='+data_final,
                method: 'GET'
            }).then(function (retorno) {
                $scope.listaVeiculosAlterar = retorno.data;
                $("#veiculosSelectAprovMudar").select2();
            },
            function (retorno) {
                console.log('Error: '+retorno.status);
            });
        }

        $scope.buscarVeiculosAlterarHorario = function(i_alocacao, data_inicial, data_final) {
            $scope.obj = {};
            var arrayinicial = data_inicial.split(' ');
            var arrayfinal = data_final.split(' ');
            $scope.obj.i_alocacao = i_alocacao;
            $scope.obj.dt_inicial = arrayinicial[0];
            $scope.obj.hr_inicial = arrayinicial[1];
            $scope.obj.dt_final = arrayfinal[0];
            $scope.obj.hr_final = arrayfinal[1];
        }

        $scope.salvarAprovacaoVeiculo = function(dados, i_patrimonio) {
            dados.i_patrimonio = i_patrimonio;
            $.post('setAprovacaoVeiculos', {dados:dados}, function() {
                alert('Salvo com sucesso.');
                window.open('aprovacao','_self');
            });
            //$("#form_salvar_aprovacao_veiculo").submit();
        }

        $scope.salvarAlteracaoHorario = function(obj) {
            $.post('setAlterarHorario', {dados:obj}, function() {
                alert('Salvo com sucesso.');
                window.open('aprovacao','_self');
            });
        }

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
    <div class='row'>
        <?php
            echo anchor(
                "veiculos/historico",
                '<span class="glyphicon glyphicon-calendar"></span>
                 Hist&oacute;rico',
                'class="btn btn-default btn-sm""'
            );
        ?>
    </div><br/>
    <div class="row">
        <div class="modal fade" id="modal-alterar-horario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title-aprovacao" id="myModalLabel">Alterar ve&iacute;culo</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="dt_inicial">Data inicial:</label>
                                    <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                        <input type="text"
                                            ng-required="true"
                                            ng-model="obj.dt_inicial"
                                            class="form-control input-sm mask_date"
                                            placeholder="Data"
                                            disabled>
                                        <span class="input-group-addon icon_addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="hr_inicial">Hora inicial</label>
                                    <input type="text"
                                        ng-required="true"
                                        ng-model="obj.hr_inicial"
                                        data-inputmask="'mask': '99:99'"
                                        class="form-control"
                                        placeholder="Hora">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="dt_final">Data Final:</label>
                                    <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                        <input type="text"
                                            ng-required="true"
                                            ng-model="obj.dt_final"
                                            class="form-control input-sm mask_date"
                                            placeholder="Data"
                                            disabled>
                                        <span class="input-group-addon icon_addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="hr_final">Hora Final</label>
                                    <input type="text"
                                        ng-required="true"
                                        ng-model="obj.hr_final"
                                        data-inputmask="'mask': '99:99'"
                                        class="form-control"
                                        placeholder="Hora">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ng-click="salvarAlteracaoHorario(obj)">Alterar hor&aacute;rio</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-alterar-veiculo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title-aprovacao" id="myModalLabel">Alterar ve&iacute;culo</h4>
                    </div>
                    <div class="modal-body">
                        <?php
                            echo satc_form_open(
                                'form_alterar_veiculo',
                                'veiculos/setAlterarVeiculo',
                                'veiculos/aprovacao',
                                '',
                                array('role'=>'form'),
                                0,
                                'form-horizontal'
                            );
                        ?>
                            <input type="hidden" id="i_alocacao_mudar" name="i_alocacao_mudar" value="">
                            <div class="row">
                                <div class="form-group">
                                    <label for="i_patrimonio" class="col-sm-3 control-label">Ve&iacute;culo:</label>
                                    <div class="col-sm-7">
                                        <select class='form-control' id="veiculosSelectAprovMudar" name="veiculosSelectAprovMudar" ng-model="veiculo_alterar" ng-options="listaVeiculo.nome group by listaVeiculo.categoria for listaVeiculo in listaVeiculosAlterar"><br>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ng-click="salvarAprovacaoVeiculo(dados_alterar, veiculo_alterar.i_patrimonio)">Alterar ve&iacute;culo</button>
                    </div>
                </div>
            </div>
        </div>

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
                                'form_alterar_veiculo',
                                '',
                                'veiculos/aprovacao',
                                '',
                                array('role'=>'form'),
                                0,
                                'form-horizontal'
                            );
                        ?>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <input type="hidden" disabled id="i_alocacao" ng-model="dados.i_alocacao" name="i_alocacao" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="status" class="col-sm-3 control-label">Status: </label>
                                    <div class="col-sm-6">
                                        <input onclick="mostra_campo(this.value)" checked="true" type="radio" name="status" ng-model="dados.status_aprov" id="status1" value="A"> Aprovar &nbsp;
                                        <input onclick="mostra_campo(this.value)" type="radio" name="status" id="status2" ng-model="dados.status_aprov" value="R"> Reprovar
                                    </div>
                                </div>
                            </div>
                            <div class='motivo hide'>
                                <br/>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="motivo" class="col-sm-3 control-label">Motivo:</label>
                                        <div class="col-sm-7">
                                            <textarea rows="3" class="form-control" id="motivo" name="motivo" ng-model="dados.motivo_cancel" placeholder="Motivo"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='patrimonio'>
                                <br/>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="veiculo" class="col-sm-3 control-label">Ve&iacute;culos:</label>
                                        <div class="col-sm-7">
                                            <select class='form-control' id="veiculosSelectAprov" ng-change="veiculoOBS=true" name="veiculosSelectAprov" ng-model="veiculo" ng-options="listaVeiculo.nome group by listaVeiculo.categoria for listaVeiculo in listaVeiculos">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row" ng-show="dados.status_aprov=='A' && veiculoOBS==true">
                                <div class="form-group">
                                    <label for="observacao" class="col-sm-3 control-label">Observa&ccedil;&atilde;o:</label>
                                    <div class="col-sm-7">
                                        <textarea rows="3" class="form-control" ng-model="dados.observacao"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ng-disabled="dados.status_aprov!='A' && dados.status_aprov!='R'" ng-click="salvarAprovacaoVeiculo(dados, veiculo.i_patrimonio)">Salvar aprova&ccedil;&atilde;o</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade"
            id="modal-resumo"
            tabindex="-1"
            role="dialog"
            ng-required="true"
            aria-labelledby="myModalLabel"
            aria-hidden="true"
            ng-click="calculaValorCombustivel(formResumo.vl_combustivel, formResumo.km_percorrida, formResumo.km_litro)","calculaKmPercorrido(formResumo.km_inicial,formResumo.km_final)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title-aprovacao" id="myModalLabel">Registrando</h4>
                    </div>
                    <form name="formResumoAlocacao" role="form" class="form-horizontal">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="dt_inicial"
                                    class="col-sm-4 control-label">Data/Hora de retirada:
                                </label>
                                <div data-date-autoclose="true"
                                    data-date-format="dd/mm/yyyy"
                                    class="col-sm-3 input-group date ebro_datepicker">
                                    <input type="text"
                                        ng-required="true"
                                        ng-model="formResumo.dt_inicial"
                                        class="form-control input-sm mask_date"
                                        placeholder="Data">
                                    <span class="input-group-addon icon_addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <div class="col-sm-3 input-group">
                                    <input type="text"
                                        ng-required="true"
                                        ng-model="formResumo.hr_inicio_resumo"
                                        data-inputmask="'mask': '99:99'"
                                        class="form-control"
                                        placeholder="Hora">
                               </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="form-group input-group">
                                <label for="dt_final" class="col-sm-4 control-label">Data/Hora de entrega: </label>
                                <div
                                    data-date-autoclose="true"
                                    data-date-format="dd/mm/yyyy"
                                    class="col-sm-3 input-group date ebro_datepicker">
                                    <input type="text"
                                        ng-model="formResumo.dt_final"
                                        ng-required="true"
                                        class="form-control input-sm mask_date"
                                        placeholder="Data">
                                    <span class="input-group-addon icon_addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <div class="col-sm-3 input-group">
                                    <input type="text"
                                        data-inputmask="'mask': '99:99'"
                                        ng-required="true"
                                        ng-model="formResumo.hr_fim_resumo"
                                        class="form-control"
                                        placeholder="Hora">
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="form-group">
                                <label for="km_inicial" class="col-sm-4 control-label">KM Inicial/Final: </label>
                                <div class="col-sm-3 input-group">
                                    <input ng-blur="calculaKmPercorrido(formResumo.km_inicial,formResumo.km_final)"
                                        ng-class="validaCampo(formResumoAlocacao.km_inicial, formResumoAlocacao.$dirty)"
                                        type="text"
                                        ng-required="true"
                                        name="km_inicial"
                                        class="form-control"
                                        id="km_inicial"
                                        ng-model="formResumo.km_inicial"
                                        placeholder="KM Inicial"
                                        onkeyup="substituiVirgula(this)">
                                        <span data-hint="Campo vazio!"
                                                ng-if="formResumoAlocacao.$dirty == true && formResumoAlocacao.km_inicial.$invalid"
                                                class="hint hint--top input-group-addon icon_addon alert-danger">
                                            <i class='icon-exclamation-sign'></i>
                                        </span>
                                </div>
                                <div class="col-sm-3 input-group">
                                    <input ng-blur="calculaKmPercorrido(formResumo.km_inicial,formResumo.km_final)"
                                        ng-class="validaCampo(formResumoAlocacao.km_final, formResumoAlocacao.$dirty)"
                                        type="text"
                                        ng-required="true"
                                        name="km_final"
                                        class="form-control"
                                        id="km_final"
                                        ng-model="formResumo.km_final"
                                        placeholder="KM Final"
                                        onkeyup="substituiVirgula(this)">
                                        <span data-hint="Campo vazio!"
                                                ng-if="formResumoAlocacao.$dirty == true && formResumoAlocacao.km_final.$invalid"
                                                class="hint hint--top input-group-addon icon_addon alert-danger">
                                            <i class='icon-exclamation-sign'></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="form-group">
                                <label for="km_percorrida" class="col-sm-4 control-label">KM Percorrido: </label>
                                <div class="col-sm-3 input-group">
                                    <input readonly="true"
                                        ng-class="validaCampokm(formResumoAlocacao.km_percorrida, formResumo.km_percorrida, formResumoAlocacao.$dirty)"
                                        type="text"
                                        class="form-control"
                                        id="km_percorrida"
                                        ng-required="true"
                                        ng-model="formResumo.km_percorrida"
                                        placeholder="KM Percorrido"
                                        onkeyup="substituiVirgula(this)">
                                         <span data-hint="Quilometragen inv&aacute;lida !"
                                                ng-if="formResumoAlocacao.$dirty == true && formResumo.km_percorrida<0"
                                                class="hint hint--top input-group-addon icon_addon alert-danger">
                                            <i class='icon-exclamation-sign'></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="form-group">
                                <label for="vl_combustivel" class="col-sm-4 control-label">Valor Combust&iacute;vel/KM/L: </label>
                                <div class="col-sm-3 input-group">
                                    <input ng-class="validaCampo(formResumoAlocacao.vl_combustivel, formResumoAlocacao.$dirty)"
                                        type="text"
                                        ng-required="true"
                                        name="vl_combustivel"
                                        class="form-control "
                                        id="vl_combustivel"
                                        ng-model="formResumo.vl_combustivel"
                                        placeholder="Valor Combust&iacute;vel"
                                        onkeyup="substituiVirgula(this)">
                                        <span data-hint="Campo vazio!"
                                                ng-if="formResumoAlocacao.$dirty == true && formResumoAlocacao.vl_combustivel.$invalid"
                                                class="hint hint--top input-group-addon icon_addon alert-danger">
                                            <i class='icon-exclamation-sign'></i>
                                        </span>

                                </div>

                                <div class="col-sm-3 input-group">
                                    <input ng-class="validaCampo(formResumoAlocacao.km_litro, formResumoAlocacao.$dirty)"
                                        type="text"
                                        ng-required="true"
                                        name="km_litro"
                                        class="form-control text-right "
                                        id="km_litro"
                                        ng-model="formResumo.km_litro"
                                        placeholder="M&eacute;dia KM/L"
                                        onkeyup="substituiVirgula(this)">
                                        <span data-hint="Campo vazio!"
                                                ng-if="formResumoAlocacao.$dirty == true && formResumoAlocacao.km_litro.$invalid"
                                                class="hint hint--top input-group-addon icon_addon alert-danger">
                                            <i class='icon-exclamation-sign'></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="form-group">
                                <label for="vl_gasto" class="col-sm-4 control-label">Valor Gasto: </label>
                                <div class="col-sm-3">
                                    <input readonly="true"
                                        type="text"
                                        class="form-control text-right"
                                        id="vl_gasto"
                                        ng-model="formResumo.vl_gasto"
                                        ng-required="true"
                                        placeholder="Valor Gasto">
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="form-group">
                                <label for="observacoes" class="col-sm-4 control-label">Observa&ccedil;&otilde;es: </label>
                                <div class="col-sm-6">
                                    <textarea rows="4"
                                        class="form-control"
                                        ng-model="formResumo.observacao"
                                        placeholder="Observa&ccedil;&otilde;es"></textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" ng-model="formResumo.i_alocacao">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ng-click="formResumoAlocacao.$valid? setResumo(formResumo):formResumoAlocacao.$dirty=true">Salvar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="tabbable tabs-left tabbable-bordered">
            <ul class="nav nav-tabs">
                <li class='active tab-li1'>
                    <a onclick="setTab(1)" href="#">
                        Pr&eacute;-Aprovado <span class='badge'><?php echo count($aguardando); ?></span>
                    </a>
                </li>
                <li class='tab-li2'>
                    <a onclick="setTab(2)" href="#">
                        Aprovados <span class='badge alert-success'><?php echo count($aprovados); ?></span>
                    </a>
                </li>
                <li class='tab-li3'>
                    <a onclick="setTab(3)" href="#">
                        Reprovados <span class='badge alert-danger'><?php echo count($reprovados); ?></span>
                    </a>
                </li>
                <li class='tab-li4'>
                    <a onclick="setTab(4)" href="#">
                        Ve&iacute;culos <span class='badge alert-info'><?php echo count($veiculos_all)-1; ?></span>
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
                                  <th width="20%" class="text-center">Hor&aacute;rio uso</th>
                                  <th>Local</th>
                                  <th>Unidade</th>
                                  <th>Motivo</th>
                                  <th class="text-center">Nº Passageiros</th>
                                  <th>Aprova&ccedil;&atilde;o</th>
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
                                        echo "  <td class='text-center'>
                                                    <strong>{$a['dt_alocacao_ini']}</strong>
                                                    <strong>{$a['dt_alocacao_fim']}</strong>
                                                    <br>
                                                    <button data-toggle='modal' data-target='#modal-alterar-horario' ng-click=\"buscarVeiculosAlterarHorario('{$a['i_alocacao']}','{$a['dt_alocacao_ini']}','{$a['dt_alocacao_fim']}')\" class='btn btn-default btn-xs'>
                                                        <span class='glyphicon glyphicon-pencil'></span>
                                                        Alterar hor&aacute;rio
                                                    </button>
                                                </td>";
                                        if (isset($a['cidade'])) {
                                            echo "  <td>{$this->satc->utf8_encode($a['cidade'])}</td>";
                                        } else {
                                            echo "  <td>&nbsp;</td>";
                                        }
                                        echo "  <td>{$this->satc->utf8_encode($a['nome_unidade'])}</td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['justificativa'])}</td>";
                                        echo "  <td class='text-center'>{$a['n_passageiros']}</td>";
                                        echo "  <td>
                                                    <button
                                                        data-toggle='modal'
                                                        data-target='#modal-aprovacao'
                                                        onclick=\"formAprovacao('{$this->satc->codificar($a['i_alocacao'])}')\"
                                                        ng-click=\"buscarVeiculos('{$a['i_alocacao']}','{$a['dt_alocacao_ini']}','{$a['dt_alocacao_fim']}')\"
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

            <div class="tab-content tab2 hide">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                        <?php
                            $attributes = array('id'=>'form_periodo', 'method'=>'GET', 'class'=>'form-inline');
                            echo form_open("veiculos/aprovacao", $attributes);
                            ?>
                            <div class="col-sm">
                                <div class="pull-left">
                                    <div class="form-inline" style="margin-left: 20px">
                                        <label>Per&iacute;odo</label><br>
                                        <div class="form-group">
                                            <label class="item item-input">Sa&iacute;da: </label>
                                            <input type="date" class="form-control" placeholder="Data Inicial"  name="dataInicial" min-date="2011-01-01" value="<?php echo ($dtInicial); ?>">
                                        </div>
                                        <div class="form-group" style="margin-left: 20px">
                                            <label class="item item-input">Chegada: </label>
                                            <input type="date" class="form-control" placeholder="Data Final" name="dataFinal" min-date="2011-01-01" value="<?php echo ($dtFinal); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-basic" style="margin-top: 50px; margin-left: 10px" type="submit">Filtrar Periodo</button>
                        <?php
                            echo form_close();
                        ?>
                        <table class="table table-hover table-striped">
                            <thead>
                              <tr>
                                  <th></th>
                                  <th>Condutor</th>
                                  <th>Ve&iacute;culo</th>
                                  <th width="20%" class="text-center">Hor&aacute;rio uso</th>
                                  <th>Local</th>
                                  <th>Observa&ccedil;&atilde;o</th>
                                  <th class="text-center">Nº Passageiros</th>
                                  <th class="text-center">Km percorrida</th>
                                  <th width="5%" class="text-center">Registrar</th>
                                  <th width="5%" class="text-center">Cancelar</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($aprovados)==0) {
                                    echo '<tr><td colspan="10" class="text-center">Nenhum registro!</td></tr>';
                                } else {
                                    $cont = 0;
                                    foreach ($aprovados as $a) {
                                        $cont++;
                                        $km_percorrida = str_replace('.', ',', $a['km_percorrida']).' KM';
                                        echo '<tr>';
                                        echo "  <td>{$cont}</td>";
                                        echo "  <td>{$a['condutor']}</td>";
                                        echo "  <td>{$a['marca_modelo']} - {$a['placa']}
                                                    - <button data-toggle='modal' data-target='#modal-alterar-veiculo' ng-click=\"buscarVeiculosAlterar('{$a['i_alocacao']}','{$a['dt_alocacao_ini']}','{$a['dt_alocacao_fim']}')\" class='btn btn-default btn-xs'>
                                                    <span class='glyphicon glyphicon-pencil'></span>
                                                    Alterar ve&iacute;culo
                                                    </button>
                                                </td>";
                                        echo "  <td class='text-center'>
                                                    <strong>{$a['dt_alocacao_ini']}</strong>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <strong>{$a['dt_alocacao_fim']}</strong>
                                                    <br><br>
                                                    <button data-toggle='modal' data-target='#modal-alterar-horario' ng-click=\"buscarVeiculosAlterarHorario('{$a['i_alocacao']}','{$a['dt_alocacao_ini']}','{$a['dt_alocacao_fim']}')\" class='btn btn-default btn-xs'>
                                                    <span class='glyphicon glyphicon-pencil'></span>
                                                    Alterar hor&aacute;rio
                                                    </button>
                                                </td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['cidade'])}</td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['justificativa'])}</td>";
                                        echo "  <td class='text-center'>{$a['n_passageiros']}</td>";
                                        echo "  <td class='text-center'>{$km_percorrida}</td>";
                                        echo "  <td class='text-center'>
                                                    <button
                                                        data-toggle='modal'
                                                        data-target='#modal-resumo'
                                                        class='btn btn-default btn-sm'
                                                        ng-click='getResumoAlocacao({$a['i_alocacao']})'>
                                                        <span class='glyphicon glyphicon-list-alt'></span>
                                                    </button>
                                                </td>";
                                        echo "  <td class='text-center'>
                                                    <button class='btn btn-default btn-sm'
                                                        onclick='cancela_agendado({$a['i_alocacao']})'>
                                                        <span class='glyphicon icon-remove'></span>
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

            <div class="tab-content tab3 hide">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-hover table-striped">
                            <thead>
                              <tr>
                                  <th></th>
                                  <th width="13%">Solicitante</th>
                                  <th width="13%">Condutor</th>
                                  <th>Ve&iacute;culo</th>
                                  <th width="20%" colspan="2" class="text-center">Hor&aacute;rio uso</th>
                                  <th width="13%">Local</th>
                                  <th width="25%">Observa&ccedil;&otilde;es</th>
                                  <th class="text-center">Nº Passageiros</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($reprovados)==0) {
                                    echo '<tr><td colspan="8" class="text-center">Nenhum registro!</td></tr>';
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
                                        echo "  <td>{$this->satc->utf8_encode($a['cidade'])}</td>";
                                        echo "  <td>{$this->satc->utf8_encode($a['justificativa'])}</td>";
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

            <div class="tab-content tab4 hide">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="#modal-addCategoria" data-toggle="modal" class="btn btn-default btn-sm">
                                   <span class="glyphicon glyphicon-plus-sign" data-toggle="tooltip" title="Adicionar categoria de ve&iacute;culos"></span>
                                    Categoria
                                </a>
                            </div>
                        </div>
                        <table class="table table-hover table-striped">
                            <thead>
                              <tr>
                                  <th>

                                  </th>
                                  <th>Placa</th>
                                  <th>Ve&iacute;culo</th>
                                  <th class="text-center">Combust&iacute;vel</th>
                                  <th class="text-center">Km m&iacute;nimo</th>
                              </tr>
                            </thead>
                            <?php
                                foreach ($veiculos_all as $i_grupo => $val) {
                                    $grupo = $this->Veiculos_model->getNomeCatVeiculos($i_grupo);
                                    $nome_grupo = (isset($grupo->nome) ? $this->satc->utf8_encode($grupo->nome) : 'Sem Categoria');
                            ?>
                            <thead>
                              <tr>
                                <th colspan="6">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                    &nbsp;&nbsp;
                                    <?php echo $nome_grupo ?>
                                </th>
                              </tr>
                            </thead>
                            <?php
                                if (count($veiculos_all[$i_grupo])==0) {
                                    echo '<tr>
                                            <td colspan="6" class="text-center">Nenhum registro!</td>
                                          </tr>';
                                } else {
                                    $cont = 0;
                                    foreach ($veiculos_all[$i_grupo] as $a) {
                            ?>
                                        <tbody>
                                            <?php
                                                if ($a['i_patrimonio']==1) {
                                                    break;
                                                }
                                                $cont++;
                                                echo '<tr>';
                                                echo "  <td>{$cont}</td>";
                                                echo "  <td>{$a['placa']}</td>";
                                                echo "  <td>{$this->satc->utf8_encode($a['marca_modelo'])}</td>";
                                                echo "  <td class='text-center'><strong>{$a['combustivel']}</strong></td>";
                                                echo "  <td width='12%' class='text-center'>
                                                        <div class='col-sm-12 input-group'>
                                                            <input onkeypress=\"chamaKm(event, {$a['i_patrimonio']}, 'km_minimo{$a['i_patrimonio']}')\" onkeyup='substituiVirgula(this)' class='form-control text-right' type='text' name='km_minimo{$a['i_patrimonio']}' id='km_minimo{$a['i_patrimonio']}' value='{$a['km_minimo']}'>
                                                            <span style='cursor:pointer' onclick=\"setKmMinimo({$a['i_patrimonio']}, 'km_minimo".$a['i_patrimonio']."')\" class='input-group-addon icon_addon'>
                                                                <span class='glyphicon glyphicon-floppy-open'></span>
                                                            </span>
                                                        </div>
                                                        </td>
                                                        <td width='3%'>
                                                            <a href='#' onclick='editaCategoria({$a['i_grupo']}, {$a['i_empresa']}, {$a['i_patrimonio']})' class='btn btn-default btn-sm'>
                                                               <span class='glyphicon glyphicon-pencil' data-toggle='tooltip' data-original-title='Alterar Categoria'></span>
                                                            </a>
                                                        </td>
                                                        ";
                                                echo '</tr>';
                                            ?>
                                        </tbody>
                            <?php
                                        }
                                    }
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal tab4 add categorias -->
<div class="modal fade" id="modal-addCategoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Adicionar Categoria</h4>
            </div>
            <?php
                echo satc_form_open('form_add_categoria', '', '', '', array('role'=>'form'), 0, 'form-horizontal');
            ?>
            <div class="modal-body">
                <div class="row">
                    <label for="status" class="col-sm-4 control-label">Nome da Categoria: </label>
                    <div class="col-sm-7">
                        <input type="text" name="nome_grupo" class="form-control input-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="addCategoria()">Salvar Categoria</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal tab4 editar categorias -->
<div class="modal fade" id="modal-editaCategoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar Categoria</h4>
            </div>
            <?php
                echo satc_form_open('form_add_categoria', '', '', '', array('role'=>'form'), 0, 'form-horizontal');
            ?>
            <div class="modal-body">
                <div class="row">
                    <label for="carteira" class="col-sm-4 control-label">Categoria do ve&iacute;culo: </label>
                    <div class="col-sm-7">
                        <select class="form-control input-sm" id="categoria">
                        <option value="" label="- Sem Categoria -" class="op-categoria" name="op-categoria">
                            - Sem Categoria -
                        </option>
                        <?php
                            foreach ($cat_veiculos as $val) {
                            $nome = $this->satc->utf8_encode($val['nome']);
                        ?>
                            <option value="<?php echo $val['i_grupo']; ?>" label="<?php echo $nome; ?>" class="op-categoria" name="op-categoria">
                                <?php echo $nome; ?>
                            </option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                    <input type="hidden" name="i_empresa">
                    <input type="hidden" name="i_patrimonio">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="salvaCategoria()">Salvar Categoria</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>