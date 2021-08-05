<?php
echo link_ng('terrenos/controllers/terrenosController.js');
?>
<div ng-controller="terrenosController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Lista de terrenos
        </h3>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <div class="pull-left">
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-sm" ng-click="cadTerreno()">
                        <span class="glyphicon glyphicon-plus"></span> Cadastro de terreno
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
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th width="45%">Descrição</th>
                            <th class="text-center">Hectares</th>
                            <th class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="e in listaTerrenos|filter:filtro">
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{e.descricao}}</td>
                            <td class="text-center">{{e.hectares}}</td>
                            <td class="text-center">
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="editarProduto(e.id_produto)">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="editarProduto(e.id_produto)">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                                <!-- <button class="btn btn-danger btn-sm btn-xs"
                                    ng-click="editarProduto(e.id_produto)">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button> -->
                            </td>
                        </tr>
                        <tr ng-show="listaTerrenos.length == 0">
                            <td class="text-center" colspan="6">Nenhum terreno cadastrado</td>
                        </tr>
                        <!-- <tr pagination-id="pag_fornecedores" dir-paginate="e in listaFornecedores|itemsPerPage:10">
                            <td>{{e.nome}}</td>
                            <td>OPa</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-sm-12">
            <div class="pull-right">
                <dir-pagination-controls
                    max-size="7"
                    direction-links="true"
                    boundary-links="true"
                    pagination-id="pag_fornecedores">
                </dir-pagination-controls>
            </div>
        </div>
    </div> -->
</div>