<?php
    echo link_tag('assets_novo/css/select2.css');
    echo link_tag('assets_novo/css/ebro_select2.css');
    echo link_js('assets_novo/js/select2.min.js');
    echo link_js('assets/js/angular-sanitize.min.js');
    echo link_js('assets_novo/js/plupload.full.min.js');
    echo link_js('assets_novo/js/pluginAnexo.js');
    echo link_js('assets_novo/js/dirPagination.js');
    echo link_ng('veiculos/controller/aprovacaoEmergencialController.js');
?>
<style type="text/css">
    .datepicker{z-index:9999 !important}
    .font-bold {font-weight: bold}
</style>
<script type="text/javascript">
    function changeValueData(element) {
        angular.element(element).triggerHandler('input');
    }

    $(document).ready(function() {
        $("#veiculosSelect").select2();
    });
</script>
<div ng-app="appAprovacaoEmergencial" ng-controller="aprovacaoEmergencialController" id="principal">
    <div>
        <div class="row">
            <?php $this->load->view("interface_titulo", $titulo_interface)?>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="pull-left">
                    <div class="form-group">
                        <br>
                        <button type="button" class="btn btn-primary btn-sm" ng-click="relatorioAprovacoesEmergenciais()">
                            <span class="glyphicon glyphicon-align-left"></span> Relat&oacute;rio de Aprovações
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                    <div class="form-group">
                        <div class="col-sm-5">
                            <label for="dt_inicial">Data Inicial:</label>
                            <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                <input type="text" name="dt_inicial" ng-model="dt_inicial" id="dt_inicial"
                                class="form-control input-sm mask_date form-control"
                                onkeydown="changeValueData(this)" onkeyup="changeValueData(this)">
                                <span class="input-group-addon icon_addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <label for="dt_final">Data Final:</label>
                            <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                <input type="text" name="dt_final" ng-model="dt_final" id="dt_final"
                                class="form-control input-sm mask_date form-control"
                                onkeydown="changeValueData(this)" onkeyup="changeValueData(this)">
                                <span class="input-group-addon icon_addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label for="filtrar">&nbsp;</label>
                            <button type="button" class="btn btn-warning btn-sm" ng-click="filtrarData(dt_inicial,dt_final)">
                                <span class="glyphicon glyphicon-search"></span> Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            <div class="col-sm-3">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="filtro">&nbsp;</label>
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtro">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="tabbable tabs-left tabbable-bordered">
                <ul class="nav nav-tabs">
                    <li class='tab-li1' ng-class="{'active': aba == 'P'}">
                        <a ng-click="alterarAba('P')" href="#">
                            Aguardando <span class='badge'>{{statusP}}</span>
                        </a>
                    </li>
                    <li class='tab-li2' ng-class="{'active': aba == 'A'}">
                        <a ng-click="alterarAba('A')" href="#">
                            Aprovados <span class='badge alert-success'>{{statusA}}</span>
                        </a>
                    </li>
                    <li class='tab-li3' ng-class="{'active': aba == 'R'}">
                        <a ng-click="alterarAba('R')" href="#">
                            Reprovados <span class='badge alert-danger'>{{statusR}}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="tabbable tabs-left tabbable-bordered">
                <table id="tabela_agendamentos" class="table table-hover table-striped table_satc table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Solicitante</th>
                            <th>Condutor</th>
                            <th width="15%" colspan="2" class="text-center">Hor&aacute;rio uso</th>
                            <th>Local</th>
                            <th>Unidade</th>
                            <th width="20%">Motivo</th>
                            <th class="text-center">Nº Passageiros</th>
                            <th class='text-center' ng-hide="aba == 'A' || aba == 'R'">Aprova&ccedil;&atilde;o</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-hide="listaAgendamentos.length == 0" ng-repeat="listaAgendamento in listaAgendamentos|filter:{status_aprov:aba}|filter:alterarFiltro| filter:filtro">
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{listaAgendamento.solicitante}}</td>
                            <td>{{listaAgendamento.condutor}}</td>
                            <td class="text-center">{{listaAgendamento.dt_alocacao_ini}}</td>
                            <td class="text-center">{{listaAgendamento.dt_alocacao_fim}}</td>
                            <td>{{listaAgendamento.cidade}}</td>
                            <td>{{listaAgendamento.nome_unidade}}<br><small style="color: red;">({{listaAgendamento.nome_coordenador}})</small></td>
                            <td width="20%">{{listaAgendamento.justificativa}}</td>
                            <td class="text-center">{{listaAgendamento.n_passageiros}}</td>
                            <td class="text-center" ng-hide="aba == 'A' || aba == 'R'">
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="responderEmergencial(listaAgendamento)">
                                    <span class='glyphicon icon-ok'></span>
                                    Responder
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr ng-show="statusP == 0 && aba == 'P'">
                            <td class="text-center" colspan="10"><strong>N&atilde;o h&aacute; aloca&ccedil;&otilde;es pendentes</strong></td>
                        </tr>
                        <tr ng-show="statusA == 0 && aba == 'A'">
                            <td class="text-center" colspan="10"><strong>N&atilde;o h&aacute; aloca&ccedil;&otilde;es emergenciais aprovadas</strong></td>
                        </tr>
                        <tr ng-show="statusR == 0 && aba == 'R'">
                            <td class="text-center" colspan="10"><strong>N&atilde;o h&aacute; aloca&ccedil;&otilde;es emergenciais reprovadas</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    $this->load->view('veiculos/modalRespondeEmergencial');
    ?>
</div>