<?php echo link_js('assets/js/angular.min.js'); ?>
<script type="text/javascript">
    var app = angular.module('appVeiculos',[]);

    app.controller('listaHistorico', function($scope) {
        $scope.listaPatrimonios = JSON.parse(call_valor("veiculos/get_patrimonios"));

        $scope.filtrar = function(filtro) {
            $scope.filtros = angular.copy(filtro);
            $scope.listaHistorico = JSON.parse(call_valor("veiculos/get_historico", $scope.filtros));
        };

        $scope.validaStatus = function(status){
            var retorno = '';
            if (status == 'R') {
                retorno = "danger text-danger text-center";
            } else if (status == 'P') {
                retorno = "warning text-warning text-center";
            } else if (status ==  'A') {
                retorno = "success text-success text-center";
            }
            return retorno;
        };
    });
</script>
<div ng-app="appVeiculos">
    <div class="row"><?php $this->load->view("interface_titulo", $titulo_interface)?></div>
    <div class="row">
        <p>
            <?php
                echo anchor(
                    'veiculos/aprovacao',
                    '<span class="glyphicon glyphicon-arrow-left"></span> Voltar',
                    'class="btn btn-default"'
                );
            ?>
        </p>
    </div>
    <div ng-controller="listaHistorico">
        <div class="row text-center">
            <div class="form-horizontal">
                <div class="row">
                    <div class="form-group">
                        <label for="carteira" class="col-sm-4 control-label">Ve&iacute;culo: </label>
                        <div class="col-sm-4">
                            <select
                                ng-options="option.id as option.nome for option in listaPatrimonios"
                                ng-model="filtro.i_patrimonio"
                                class="form-control"
                                id="i_patrimonio"
                                name="i_patrimonio"
                                valida="1">
                            </select>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="form-group">
                        <label for="dt_inicio" class="col-sm-4 control-label">Data inicio:</label>
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
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="form-group">
                        <label for="dt_fim" class="col-sm-4 control-label">Data fim:</label>
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
        </div>
        <div class="row">
            <table ng-show="listaHistorico.length > 0" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Seq</th>
                        <th width="30%" colspan="2" class="text-center">Horário uso</th>
                        <th width="20%">Veículo</th>
                        <th width="25%">Condutor</th>
                        <th width="30%">Local</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="itens in listaHistorico">
                        <td class='text-center'>{{itens.seq}}</td>
                        <td class="{{validaStatus(itens.status)}}"><strong>{{itens.dt_inicio}}</strong></td>
                        <td class="{{validaStatus(itens.status)}}"><strong>{{itens.dt_fim}}</strong></td>
                        <td>{{itens.veiculo}}</td>
                        <td>{{itens.condutor}}</td>
                        <td>{{itens.local}}</td>
                        <td class="{{validaStatus(itens.status)}}"><span><strong>{{itens.situacao}}</strong></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>