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
    echo link_ng('veiculos/controller/condutoresCarros.js');
    echo link_js('assets_novo/js/angular-locale_pt-br.js');
?>
<style type="text/css">
    .datepicker{z-index:9999 !important}
    .font-bold {font-weight: bold}
</style>
<div ng-app="appCondutoresCarros" ng-controller="condutoresCarrosController" id="principal">
    <div ng-if="detalhes=='0'">
        <div class="page-header-topo">
            <h3 id="grid">
                Alocações Condutor/Carro
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <?php
                echo anchor(
                    'veiculos/gerenciarVeiculos',
                    '<span class="glyphicon glyphicon-arrow-left"></span> Voltar',
                    'class="btn btn-warning btn-sm"'
                );
                ?>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtroCondCar" ng-model-options="{debounce:500}">
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
                            <th class="text-center" width="10%">Aloca&ccedil;&otilde;es</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaCondutorCarros.length != 0">
                        <tr ng-repeat="listaCondutorCarro in listaCondutorCarros|filter:filtroCondCar">
                            <td>{{listaCondutorCarro.nome_condutor}}</td>
                            <td class="text-center">
                                ({{listaCondutorCarro.total_alocacoes}})
                                <button type="button" class="btn btn-default btn-sm" ng-click="verAlocacoes(listaCondutorCarro)">
                                    <span class="glyphicon glyphicon-list"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-if="listaCondutorCarros.length == 0">
                        <tr>
                            <td class="text-center" colspan="2">N&atilde;o à registros.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div ng-if="detalhes=='1'">
        <div class="page-header-topo">
            <h3 id="grid">
                Aloca&ccedil;&otilde;es {{condutor}}
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <button type="button" class="btn btn-warning btn-sm" ng-click="voltar()">
                    Voltar
                </button>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtroAlocacoes" ng-model-options="{debounce:500}">
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
                            <th class="text-center">Placa</th>
                            <th>Ve&iacute;culo</th>
                            <th class="text-center">Data Inical</th>
                            <th class="text-center">Data Final</th>
                            <th class="text-center" width="9%">Nº Passageiros</th>
                            <th>Destino</th>
                            <th class="text-center" width="7%">Justificativa</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaAlocacoes.length != 0">
                        <tr ng-repeat="listaAlocacao in listaAlocacoes|filter:filtroAlocacoes">
                            <td class="text-center">{{listaAlocacao.placa}}</td>
                            <td>{{listaAlocacao.veiculo}}</td>
                            <td class="text-center">{{listaAlocacao.dt_alocacao_inicial}}</td>
                            <td class="text-center">{{listaAlocacao.dt_alocacao_final}}</td>
                            <td class="text-center">{{listaAlocacao.n_passageiros}}</td>
                            <td>{{listaAlocacao.destino}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm" ng-click="verMotivo(listaAlocacao.justificativa)">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-if="listaAlocacoes.length == 0">
                        <tr>
                            <td class="text-center" colspan="7">N&atilde;o a registros.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('veiculos/modalMotivoAlocacao'); ?>
</div>