<?php
    echo link_js('assets/js/angular.min.js');
    echo link_js("assets/js/angular-sanitize.min.js");
    echo link_js("assets/js/ng-csv.js");
?>
<script type="text/javascript">

    var app = angular.module('appVeiculos',['ngSanitize', 'ngCsv']);

    app.controller('listaRelatorios', function($scope) {

        $scope.listaCondutor = JSON.parse(call_valor("veiculos/getCondutorRelatorio"));
        $scope.listaPatrimonios = JSON.parse(call_valor("veiculos/get_patrimonios"));
        $scope.listaVeiculosManutencao = JSON.parse(call_valor("veiculos/getVeiculosManutencao"));
        $scope.listaStatusManutencao = [
            {id:'A',status:'EM ANDAMENTO'},
            {id:'N',status:'CANCELADO'},
            {id:'C',status:'CONCLUIDO'}
        ];

        $scope.listaRelatorios = {};
        $scope.listaRelatoriosManutencao = {};
        $scope.objExcel = {};
        $scope.filtro = {};
        $scope.filtroManutencao = {};
        $scope.ordem = true;

        $scope.filtrar = function(filtro) {
            var data = {
                'i_patrimonio' : filtro.i_patrimonio,
                'i_condutor' : filtro.i_condutor,
                'dt_inicio' : filtro.dt_inicio,
                'dt_fim' : filtro.dt_fim
            };
            $scope.listaRelatorios = JSON.parse(call_valor("veiculos/getRelatorios", data));
            $scope.objExcel = angular.copy($scope.listaRelatorios);
        }

        $scope.filtrarManutencao = function(filtroManutencao) {
            var data = {
                'i_patrimonio' : filtroManutencao.i_patrimonio,
                'i_status' : filtroManutencao.status,
                'dt_inicio' : filtroManutencao.dt_inicio,
                'dt_fim' : filtroManutencao.dt_fim
            };

            $scope.listaRelatoriosManutencao = JSON.parse(call_valor("veiculos/getRelatoriosManutencao", data));
            $scope.objExcel = angular.copy($scope.listaRelatoriosManutencao);
        }

        $scope.ordenadoPor = function(campo){
            $scope.ordenar = campo;
            if($scope.ordem == false){
               $scope.ordem = true;
            }else{
               $scope.ordem = false;
            }
        }

        $scope.getHeader = function () {
            return [
                'Seq'
                ,'Veiculo'
                ,'Condutor'
                ,'Local'
                ,'Data/Hora Inicio'
                ,'Data/Hora Fim'
                ,'Km Percorrida'
                ,'Gasto c/ Combustivel'
            ];
        };

        $scope.getHeaderManutencao = function () {
            return [
                'Seq'
                ,'Veiculo'
                ,'Previa Conclusão'
                ,'Observações'
                ,'Descrição'
                ,'Nº Nota'
                ,'Valor Gasto'
            ];
        };

        $scope.getResult = function (obj) {
            for (var i in obj) {
                delete obj[i].vl_total;
                delete obj[i].km_total;
            }
            return obj;
        };

        $scope.getResultManutencao = function (obj) {
            for (var i in obj) {
                delete obj[i].vl_total;
            }
            return obj;
        };

    });

</script>
<div ng-app="appVeiculos" ng-controller="listaRelatorios">
    <div class="row hidden-print" ><?php $this->load->view("interface_titulo", $titulo_interface)?></div>
    <div class="row">
        <p class="col-sm-2"><?=anchor('veiculos/index', '<span class="glyphicon glyphicon-arrow-left"></span> Voltar', 'class="btn btn-default hidden-print"')?></p>
    </div>
    <ul class="nav nav-tabs hidden-print" >
        <li ng-init="tab=1" ng-class="{'active' : tab==1}"><a ng-click="tab=1" >Relat&oacute;rios de Agendameto</a></li>
        <!--li ng-class="{'active' : tab==2}"><a ng-click="tab=2" >Relatórios de Manutenção</a></li-->
    </ul>
    <div class="tabs-container">
    <div class="tab-content" ng-show="tab == 1">
        <br>
        <h4 class="text-center">Relat&oacute;rios De Agendamento</h4>
        <br>
        <div class="row text-center hidden-print" >
            <form name="form_filtro">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="carteira" class="col-sm-4 control-label">Ve&iacute;culo:</label>
                        <div class="col-sm-4">
                            <select
                                ng-options="option.id as option.nome for option in listaPatrimonios"
                                ng-model="filtro.i_patrimonio"
                                class="form-control"
                                id="i_patrimonio"
                                name="i_patrimonio"
                                valida="1">
                                <option value="">- TODOS -</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="carteira" class="col-sm-4 control-label">Condutor:</label>
                        <div class="col-sm-4">
                            <select
                                ng-options="option.id as option.nome for option in listaCondutor"
                                ng-model="filtro.i_condutor"
                                class="form-control"
                                id="i_condutor"
                                name="i_condutor"
                                valida="1">
                                <option value="">- TODOS -</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="dt_inicio" class="col-sm-4 control-label">Data inicio/Data fim:</label>
                        <div
                            data-date-autoclose="true"
                            data-date-format="dd/mm/yyyy"
                            class="col-sm-2 input-group date ebro_datepicker">
                            <input
                                type="text"
                                ng-model="filtro.dt_inicio"
                                name="dt_inicio"
                                id="dt_inicio"
                                maxlength="10"
                                class="form-control input-sm mask_date"
                                valida="1">
                            <span class="input-group-addon icon_addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <div
                            data-date-autoclose="true"
                            data-date-format="dd/mm/yyyy"
                            class="col-sm-2 input-group date ebro_datepicker">
                            <input
                                type="text"
                                ng-model="filtro.dt_fim"
                                name="dt_fim"
                                id="dt_fim"
                                maxlength="10"
                                class="form-control input-sm mask_date"
                                valida="1">

                            <span class="input-group-addon icon_addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <p class="text-center">
                            <button class="btn btn-default" ng-click="filtrar(filtro)">
                                <span class="glyphicon icon-search"></span>
                                Pesquisar
                            </button>
                        </p>
                    </div>
                </div>
            </form>
        </div>
        <br/>
        <div class="row" id="div_tabela">
            <table id="tabela-relatorio" ng-show="listaRelatorios.length>0" class="table table-hover table-striped ">
                <thead>
                    <tr>
                        <th width="5%">
                            <a ng-click="ordenadoPor('seq')">
                                Seq
                                <?php echo $this->satc->ordenaTabela('seq'); ?>
                            </a>
                        </th>
                        <th width="18%">
                            <a ng-click="ordenadoPor('veiculo')" >
                                Ve&iacute;culo
                                <?php echo $this->satc->ordenaTabela('veiculo'); ?>
                            </a>
                        </th>
                        <th width="18%" >
                            <a ng-click="ordenadoPor('condutor')">
                                Condutor
                                <?php echo $this->satc->ordenaTabela('condutor'); ?>
                            </a>
                        </th>
                        <th width="18%" >
                            <a ng-click="ordenadoPor('local')">
                                Local
                                <?php echo $this->satc->ordenaTabela('local'); ?>
                        </th>
                        <th width="18%" colspan="2"class="text-center">
                            <a ng-click="ordenadoPor('dt_inicio')" >
                                Hor&aacute;rio uso
                                <?php echo $this->satc->ordenaTabela('dt_inicio'); ?>
                            </a>
                        </th>
                        <th width="10%" >
                            <a ng-click="ordenadoPor('km_percorrida')">
                                Km Percorrida
                                <?php echo $this->satc->ordenaTabela('km_percorrida'); ?>
                            </a>
                        </th>
                        <th width="10%">

                            <a ng-click="ordenadoPor('vl_gasto')">
                                Gasto c/ Combust&iacute;vel
                                <?php echo $this->satc->ordenaTabela("vl_gasto"); ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="itens in listaRelatorios|orderBy:ordenar:ordem">
                        <td class='text-center'>{{itens.seq}}</td>
                        <td>{{itens.veiculo}}</td>
                        <td>{{itens.condutor}}</td>
                        <td>{{itens.local}}</td>
                        <td class="text-center"><strong>{{itens.dt_inicio}}</strong></td>
                        <td class="text-center"><strong>{{itens.dt_fim}}</strong></td>
                        <td class="text-right">{{itens.km_percorrida|number:2}} Km</td>
                        <td class="text-right">{{itens.vl_gasto|currency:'R$'}}</td>
                    </tr>
                </tbody>
                 <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th width="10%" class="text-right">Total:</th>
                        <th class="text-right" width="5%" ng-if="$last" ng-repeat="itens in listaRelatorios">{{itens.km_total|number:2}} Km</th>
                        <th class="text-right" width="5%" ng-if="$last" ng-repeat="itens in listaRelatorios">{{itens.vl_total|currency:'R$'}}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div>
            <p class="text-center">
                <button id="bt_imprimir" ng-show="listaRelatorios.length>0" class="btn btn-default hidden-print " onclick="window.print()">
                    <span class="glyphicon glyphicon-print"></span>
                    Imprimir
                </button>
                <button id="bt_Excel" ng-show="listaRelatorios.length>0" class="btn btn-default hidden-print" ng-csv="getResult(objExcel)" field-separator=";" decimal-separator="," csv-header="getHeader()" filename="Planilha-Agendamentos.csv">
                    <?php echo img("assets_novo/img/excel.png"); ?>
                    Gerar Excel
                </button>
            </p>
        </div>
        <div>
            <p class="alert alert-warning text-center" ng-show="listaRelatorios.length <= 0">
                <strong>Nenhum registro encontrado!</strong>
            </p>
        </div>
    </div>
    </div>

    <div class="tab-content" ng-show="tab == 2">
        <br>
        <h4 class="text-center">Relatórios De Manutenções</h4>
        <br>
        <div class="row text-center hidden-print" >
            <form name="form_filtro">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="carteira" class="col-sm-4 control-label">Ve&iacute;culo:</label>
                        <div class="col-sm-4">
                            <select
                                ng-options="option.id as option.nome for option in listaVeiculosManutencao"
                                ng-model="filtroManutencao.i_patrimonio"
                                class="form-control"
                                id="i_patrimonio"
                                name="i_patrimonio"
                                valida="1">
                                <option value="">- TODOS -</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="carteira" class="col-sm-4 control-label">Status:</label>
                        <div class="col-sm-4">
                            <select
                                ng-options="option.id as option.status for option in listaStatusManutencao"
                                ng-model="filtroManutencao.status"
                                class="form-control"
                                id="i_condutor"
                                name="i_condutor"
                                valida="1">
                                <option value="">- TODOS -</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="dt_inicio" class="col-sm-4 control-label">Data inicio/Data fim:</label>
                        <div
                            data-date-autoclose="true"
                            data-date-format="dd/mm/yyyy"
                            class="col-sm-2 input-group date ebro_datepicker">
                            <input
                                type="text"
                                ng-model="filtroManutencao.dt_inicio"
                                name="dt_inicio"
                                id="dt_inicio"
                                maxlength="10"
                                class="form-control input-sm mask_date"
                                valida="1">
                            <span class="input-group-addon icon_addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <div
                            data-date-autoclose="true"
                            data-date-format="dd/mm/yyyy"
                            class="col-sm-2 input-group date ebro_datepicker">
                            <input
                                type="text"
                                ng-model="filtroManutencao.dt_fim"
                                name="dt_fim"
                                id="dt_fim"
                                maxlength="10"
                                class="form-control input-sm mask_date"
                                valida="1">

                            <span class="input-group-addon icon_addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <p class="text-center">
                            <button class="btn btn-default" ng-click="filtrarManutencao(filtroManutencao)">
                                <span class="glyphicon icon-search"></span>
                                Pesquisar
                            </button>
                        </p>
                    </div>
                </div>
            </form>
        </div>
        <br/>
        <div class="row" id="div_tabela">
            <table id="tabela-relatorio" ng-show="listaRelatoriosManutencao.length>0" class="table table-hover table-striped ">
                <thead>
                    <tr>
                        <th width="5%">
                            <a ng-click="ordenadoPor('seq')">
                                Seq
                                <?php echo $this->satc->ordenaTabela('seq'); ?>
                            </a>
                        </th>
                        <th width="18%">
                            <a ng-click="ordenadoPor('veiculo')" >
                                Veículo
                                <?php echo $this->satc->ordenaTabela('veiculo'); ?>
                            </a>
                        </th>
                        <th width="15%" class="text-center">
                            <a ng-click="ordenadoPor('data_manutencao')" >
                                Previa de Conclusão
                                <?php echo $this->satc->ordenaTabela('data_manutencao'); ?>
                            </a>
                        </th>
                        <th width="18%" >
                            <a ng-click="ordenadoPor('observacoes')">
                                Observações
                                <?php echo $this->satc->ordenaTabela('observacoes'); ?>
                            </a>
                        </th>
                        <th width="18%" >
                            <a ng-click="ordenadoPor('descricao')">
                                Descrição
                                <?php echo $this->satc->ordenaTabela('descricao'); ?>
                        </th>
                        <th width="7%" >
                            <a ng-click="ordenadoPor('nr_nota')">
                                Nº Nota
                                <?php echo $this->satc->ordenaTabela('nr_nota'); ?>
                            </a>
                        </th>
                        <th width="7%">
                            <a ng-click="ordenadoPor('vl_gasto')">
                                Valor Gasto
                                <?php echo $this->satc->ordenaTabela("vl_gasto"); ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="itens in listaRelatoriosManutencao|orderBy:ordenar:ordem">
                        <td>{{itens.seq}}</td>
                        <td>{{itens.veiculo}}</td>
                        <td class="text-center"><strong>{{itens.data_manutencao}}</strong></td>
                        <td>{{itens.observacoes}}</td>
                        <td style="white-space:pre">{{itens.descricao}}</td>
                        <td >{{itens.nr_nota}}</td>
                        <td class="text-right">{{itens.vl_gasto|currency:'R$'}}</td>
                    </tr>
                </tbody>
                 <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th width="10%" class="text-right">Total:</th>
                        <th class="text-right" width="5%" ng-if="$last" ng-repeat="itens in listaRelatoriosManutencao"><strong>{{itens.vl_total|currency:'R$'}}</strong></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div>
            <p class="text-center">
                <button id="bt_imprimir" ng-show="listaRelatoriosManutencao.length>0" class="btn btn-default hidden-print " onclick="window.print()">
                    <span class="glyphicon glyphicon-print"></span>
                    Imprimir
                </button>
                <button id="bt_Excel" ng-show="listaRelatoriosManutencao.length>0" class="btn btn-default hidden-print" ng-csv="getResultManutencao(objExcel)" field-separator=";" decimal-separator="," csv-header="getHeaderManutencao()" filename="Planilha-Manutencao.csv">
                    <?php echo img("assets_novo/img/excel.png"); ?>
                    Gerar Excel
                </button>
            </p>
        </div>
        <div>
            <p class="alert alert-warning text-center" ng-show="listaRelatoriosManutencao==''">
                <strong>Nenhum registro encontrado!</strong>
            </p>
        </div>
    </div>
    </div>
    </div>
</div>
<style type="text/css">
    a:hover, a:visited {
        text-decoration: none;
        color: #333;
    }
</style>