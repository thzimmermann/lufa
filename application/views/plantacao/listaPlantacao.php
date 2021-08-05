<?php
echo link_ng('plantacao/controllers/listaPlantacaoController.js');
?>
<div ng-controller="listaPlantacaoController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Lista de plantações
        </h3>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <div class="pull-left">
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-sm" ng-click="cadPlantacao()">
                        <span class="glyphicon glyphicon-plus"></span> Cadastrar plantação
                    </button>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="pull-right">
                <form class="form-inline">
                    <div class="form-group ">
                        <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtro">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <h4 style="font-size: 20px;color: #52AC55FF">Plantações Ativas</h4>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Item cultivado</th>
                            <th class="text-center">Terreno</th>
                            <th class="text-center">Dt. plantação</th>
                            <th class="text-center">Dt. estimativa</th>
                            <th class="text-center" width="10%">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="e in listaPlantacoes|filter:filtro|filter:{status:'S'}">
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{e.item_cultivo}}</td>
                            <td class="text-center">{{e.desc_terreno}}</td>
                            <td class="text-center">{{e.data_plantacao}}</td>
                            <td class="text-center">{{e.data_estimativa_colheita}}</td>
                            <td class="text-center">
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="editarPlantacao(e.id_plantacao)">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </td>
                        </tr>
                        <tr ng-show="listaPlantacoes.length == 0">
                            <td colspan="8" class="text-center">Nenhuma plantação registrada</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <h4 style="font-size: 20px;color: #B5494BFF">Plantações Inativas</h4>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Item cultivado</th>
                            <th class="text-center">Terreno</th>
                            <th class="text-center">Dt. plantação</th>
                            <th class="text-center">Dt. estimativa</th>
                            <th class="text-center" width="10%">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="e in listaPlantacoes|filter:filtro|filter:{status:'I'}">
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{e.item_cultivo}}</td>
                            <td class="text-center">{{e.desc_terreno}}</td>
                            <td class="text-center">{{e.data_plantacao}}</td>
                            <td class="text-center">{{e.data_estimativa_colheita}}</td>
                            <td class="text-center">
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="editarPlantacao(e.id_plantacao)">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </td>
                        </tr>
                        <tr ng-show="listaPlantacoes.length == 0">
                            <td colspan="8" class="text-center">Nenhuma plantação registrada</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>