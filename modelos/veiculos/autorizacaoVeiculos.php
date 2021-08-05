<?php
    echo link_tag('assets_novo/css/select2.css');
    echo link_tag('assets_novo/css/ebro_select2.css');
    echo link_js('assets_novo/js/select2.min.js');
    echo link_js('assets/js/angular-sanitize.min.js');
    echo link_js('assets_novo/js/plupload.full.min.js');
    echo link_js('assets_novo/js/pluginAnexo.js');
    echo link_js('assets_novo/js/dirPagination.js');
    echo link_js('assets_novo/js/angular-filter.min.js');
    echo link_js('assets_novo/js/masks.js');
    echo link_ng('veiculos/controller/autorizacaoVeiculosController.js');
    echo link_js('assets_novo/js/angular-locale_pt-br.js');
    echo link_js('assets_novo/js/raphael-2.1.4.min.js');
    echo link_js('assets_novo/js/justgage.js');
?>
<style type="text/css">
    .datepicker{z-index:9999 !important}
    .font-bold {font-weight: bold}
    .gauge {
      width: 170px;
      height: 90px;
    }
</style>
<script type="text/javascript">
    function changeValueData(element) {
        angular.element(element).triggerHandler('input');
    }
    document.addEventListener("DOMContentLoaded", function(event) {
        var g1 = new JustGage({
            id: 'g1',
            value: 0,
            min: 0,
            max: 100,
            symbol: '%',
            pointer: true,
            gaugeWidthScale: 0.6,
            customSectors: [{
              color: '#D11010',
              lo: 0,
              hi: 25
            }, {
              color: '#DA4C14',
              lo: 25,
              hi: 50
            }, {
              color: '#E9C70D',
              lo: 50,
              hi: 75
            }, {
              color: '#2CBE1D',
              lo: 75,
              hi: 100
            }],
            counter: true
        });
        document.getElementById('gauge_plus').addEventListener('click', function() {
            if (g1.config.value < 100) {
                document.getElementById("nivel_combustivel").value = g1.config.value+5;
                g1.refresh(g1.config.value+5);
            }
        });
        document.getElementById('gauge_less').addEventListener('click', function() {
            if (g1.config.value >= 5) {
                document.getElementById("nivel_combustivel").value = g1.config.value-5;
                g1.refresh(g1.config.value-5);
            }
        });
    });
</script>
<div ng-app="appAutorizacao" ng-controller="autorizacaoVeiculosController" id="principal">
    <div>
        <div class="page-header-topo">
            <div class="row">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <h3 id="grid" ng-show="detalhes == 0">
                            Registro <br>
                            <span style="font-size: large;">&nbsp;&nbsp;Solicita&ccedil;&otilde;es de Ve&iacute;culos</span>
                        </h3>
                        <h3 id="grid" ng-show="detalhes == 1">
                            Ve&iacute;culos com Problemas
                        </h3>
                        <h3 id="grid" ng-show="detalhes == 2">
                            Problemas/Solu&ccedil;&atilde;o <br>
                            <span style="font-size: large;">&nbsp;&nbsp;Veículo: {{placa}} - {{veiculo}}</span>
                            <h4 ng-show="detalhes == 2">
                                <button type="button" class="btn btn-warning btn-sm" ng-click="voltar(1)">
                                    <span class="glyphicon glyphicon-arrow-left"></span> Voltar
                                </button>
                            </h4>
                        </h3>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pull-right">
                        <h3 id="grid" ng-show="detalhes == 0">
                            <h4>
                                <button class="btn btn-default" ng-click="verObservacoesVeiculos()" ng-show="detalhes == 0">
                                    <span class="glyphicon glyphicon-th-list"></span> Problemas com Ve&iacute;culos
                                </button>
                            </h4>
                        </h3>
                        <h3 id="grid" ng-show="detalhes == 5">
                            &nbsp; <!-- DETALHES == 2 funcao nao acabada ainda-->
                            <h4 ng-show="detalhes == 5" >
                                <button type="button" class="btn btn-danger btn-sm" ng-click="relatorioPDF(i_patrimonio)">
                                    <span class="glyphicon glyphicon-list-alt"></span> Relat&oacute;rio PDF
                                </button>
                            </h4>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div ng-show="detalhes == 0">
            <div class="row">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <div class="form-inline">
                            <label>Per&iacute;odo</label><br>
                            <div class="form-group">
                                <label class="item item-input">Sa&iacute;da: </label>
                                <input type="date" class="form-control" placeholder="Data Inicial"  name="startDate" min-date="2011-01-01" ng-model="startDate">
                            </div>
                            <div class="form-group">
                                <label class="item item-input">Chegada: </label>
                                <input type="date" class="form-control" placeholder="Data Final" name="endDate" min-date="2011-01-01" ng-model="endDate">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pull-right">
                        <form>
                            <br>
                            <div class="form-group ">
                                <label class="item item-input">&nbsp;</label>
                                <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtro">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="tabbable tabs-left tabbable-bordered">
                    <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                        <thead>
                            <tr>
                                <th>Condutor</th>
                                <th class="text-center">Placa</th>
                                <th>Ve&iacute;culo</th>
                                <th class="text-center">Data sa&iacute;da</th>
                                <th class="text-center">Data chegada</th>
                                <th class="text-center">Imprimir AUT.</th>
                                <th class="text-center">Autoriza&ccedil;&atilde;o</th>
                            </tr>
                        </thead>
                        <tbody ng-if="listaSolicitacoesUsuarios.length != 0">
                            <tr ng-repeat="listaSolicitacoes in listaSolicitacoesUsuarios|betweenDate:'data_alocacao':startDate:endDate|filter:filtro">
                                <td>{{listaSolicitacoes.condutor}}</td>
                                <td class="text-center">{{listaSolicitacoes.placa}}</td>
                                <td>{{listaSolicitacoes.veiculo}}</td>
                                <td class="text-center">{{listaSolicitacoes.dt_alocacao_inicial}}</td>
                                <td class="text-center">{{listaSolicitacoes.dt_alocacao_final}}</td>
                                <td class="text-center">
                                    <button class="btn btn-default btn-sm btn-xs" ng-click="imprimirPdfAutorizacao(listaSolicitacoes)">
                                        <span class="glyphicon glyphicon-print"></span>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button disabled ng-if="listaSolicitacoes.autorizacao_feita == 1" class="btn btn-success btn-sm">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </button>
                                    <button ng-if="listaSolicitacoes.autorizacao_feita != 1" class="btn btn-danger btn-sm" ng-click="cadastroAutorizacao(listaSolicitacoes)">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-if="listaSolicitacoesUsuarios.length == 0">
                            <tr>
                                <td class="text-center" colspan="7">N&atilde;o a aloca&ccedil;&otilde;es feitas</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div ng-show="detalhes == 1">
            <div class="row">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <div class="form-group">
                            <button ng-show="detalhes == 1" type="button" class="btn btn-warning btn-sm" ng-click="voltar(0)">
                                <span class="glyphicon glyphicon-arrow-left"></span> Voltar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pull-right">
                        <div class="form-group">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtro">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="tabbable tabs-left tabbable-bordered">
                    <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                        <thead>
                            <tr>
                                <th>Placa - Ve&iacute;culo</th>
                                <th class="text-center">Problemas</th>
                            </tr>
                        </thead>
                        <tbody ng-if="listaVeiculoProblemas.length != 0">
                            <tr ng-repeat="listaVeiculo in listaVeiculoProblemas|filter:filtro">
                                <td>{{listaVeiculo.placa}}&nbsp;&nbsp; - &nbsp;&nbsp;{{listaVeiculo.veiculo}}</td>
                                <td class="text-center">
                                    <button class="btn btn-default btn-sm" ng-click="detalhesCarro(listaVeiculo.i_patrimonio)">
                                        <span class="glyphicon glyphicon-cog"></span> ({{listaVeiculo.n_problemas}})
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-if="listaVeiculoProblemas.length == 0">
                            <tr>
                                <td class="text-center" colspan="2">N&atilde;o a carros com problemas.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div ng-show="detalhes == 2">
            <div class="row">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <div class="form-inline">
                            <label>Per&iacute;odo</label><br>
                            <div class="form-group">
                                <label class="item item-input">Sa&iacute;da: </label>
                                <input type="date" class="form-control" placeholder="Data Inicial"  name="startDate" min-date="2011-01-01" ng-model="startDate">
                            </div>
                            <div class="form-group">
                                <label class="item item-input">Chegada: </label>
                                <input type="date" class="form-control" placeholder="Data Final" name="endDate" min-date="2011-01-01" ng-model="endDate">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pull-right">
                        <form>
                            <br>
                            <div class="form-group ">
                                <label class="item item-input">&nbsp;</label>
                                <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtro">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="tabbable tabs-left tabbable-bordered">
                    <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Patrimonio</th>
                                <th>Condutor</th>
                                <th class="text-center">Data sa&iacute;da</th>
                                <th class="text-center">Data chegada</th>
                                <th class="text-center">Problema/Solu&ccedil;&atilde;o</th>
                                <th class="text-center">Solu&ccedil;&atilde;o</th>
                            </tr>
                        </thead>
                        <tbody ng-if="listaProblemasVeiculo.length != 0">
                            <tr ng-repeat="listaVeiculo in listaProblemasVeiculo|betweenDate:'dt_inicial':startDate:endDate|filter:filtro">
                                <td class="text-center">{{listaVeiculo.i_patrimonio}}</td>
                                <td>{{listaVeiculo.condutor}}</td>
                                <td class="text-center">{{listaVeiculo.dt_alocacao_inicial}}</td>
                                <td class="text-center">{{listaVeiculo.dt_alocacao_final}}</td>
                                <td class="text-center">
                                    <button class="btn btn-default btn-sm btn-xs" ng-click="cadSolucaoProblema(listaVeiculo)">
                                        <span ng-if="listaVeiculo.solucao == ''" class="glyphicon glyphicon-wrench"></span>
                                        <span ng-if="listaVeiculo.solucao != ''" class="glyphicon glyphicon-search"></span>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <span class="glyphicon glyphicon-ok" style="color: green;" ng-if="listaVeiculo.solucao != ''"></span>
                                    <span class="glyphicon glyphicon-remove" style="color: red;" ng-if="listaVeiculo.solucao == ''"></span>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-if="listaProblemasVeiculo.length == 0">
                            <tr>
                                <td class="text-center" colspan="5">N&atilde;o a Problemas Cadastrados</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
    $this->load->view('veiculos/modalAutorizacaoVeiculos');
    $this->load->view('veiculos/modalSolucaoProblemaVeiculos');
    ?>
</div>