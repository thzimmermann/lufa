<?php
echo link_ng('estoque/controllers/controleEstoqueController.js');
?>
<div ng-controller="controleEstoqueController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Controle de estoque
        </h3>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <div class="pull-left">
                <!-- <form class="form-inline">
                    <div class="form-group ">
                        <label>Tipo : </label>
                        <select class="form-control" ng-model="filtroAno">
                            <option ng-repeat="a in filtroAnos" ng-value="a.ano">{{a.label}}</option>
                        </select>
                    </div>
                </form> -->
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
                            <th>Tipo produto</th>
                            <th>Produto</th>
                            <th class="text-center" width="15%">Estoque atual</th>
                            <th class="text-center" width="15%">Movimentação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="listaProduto in listaProdutos|filter:filtro">
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{listaProduto.tipo_produto}}</td>
                            <td>{{listaProduto.nome}}</td>
                            <td class="text-center" >{{listaProduto.estoque_atual}}</td>
                            <th class="text-center">
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="listaMovimentacoes(listaProduto.id_produto)">
                                    <span class="glyphicon glyphicon-transfer"></span>
                                </button>
                                <!-- <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="listaMovimentacoes(listaProduto.id_produto, 'E')">
                                    <span class="glyphicon glyphicon-log-in"></span> Entrada
                                </button>
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="listaMovimentacoes(listaProduto.id_produto, 'S')">
                                    <span class="glyphicon glyphicon-log-out"></span> Saída
                                </button> -->
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>