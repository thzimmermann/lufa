 <?php
echo link_ng('compras/controllers/listaComprasController.js');
?>
<div ng-controller="listaComprasController" ng-cloak>
    <div ng-show="tela == 1">
        <div class="page-header-topo">
            <h3 id="grid" class="">
                Lista de Compras
            </h3>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-8">
                <div class="pull-left">
                    <div class="form-group">
                        <button type="button" class="btn btn-success btn-sm" ng-click="cadCompras()">
                            <span class="glyphicon glyphicon-plus"></span> Cadastrar compra
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
                                <th class="text-center" width="5%">#</th>
                                <th>Fornecedor</th>
                                <th class="text-center" width="15%">NF</th>
                                <th class="text-center" width="20%">Data compra</th>
                                <th class="text-center" width="20%">Valor total</th>
                                <th class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="e in listaCompras|filter:filtro">
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{e.nome_fornecedor}}</td>
                                <td class="text-center">{{e.nf}}</td>
                                <td class="text-center">{{e.dt_compra}}</td>
                                <td class="text-center">{{e.valor_total | currency: 'R$ '}}</td>
                                <td class="text-center">
                                    <button class="btn btn-default btn-sm" title="Ver itens da compra"
                                        ng-click="verItensCompras(e)">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-show="listaCompras.length == 0">
                                <td class="text-center" colspan="6">Nenhuma compra cadastrada</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div ng-show="tela == 2">
        <div class="page-header-topo">
            <h3 id="grid" class="">
                Itens da compra
            </h3>
            <h5>Fornecedor : <b>{{compra.nome_fornecedor}}</b></h5>
            <h5>
                <span>
                    Data da compra : <b>{{compra.dt_compra}}</b>
                </span>
                <span style="margin-left: 10%;">
                    Valor total : <b>{{compra.valor_total | currency:'R$ '}}</b>
                </span>
            </h5>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-8">
                <div class="pull-left">
                    <div class="form-group">
                        <button type="button" class="btn btn-warning btn-sm" ng-click="tela = 1">
                            <span class="glyphicon glyphicon-arrow-left"></span> Voltar
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="pesquisa">
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
                                <th class="text-center" width="5%">#</th>
                                <th>Produto</th>
                                <th class="text-center" width="20%">Qtde.</th>
                                <th class="text-center" width="20%">Vl. Unitário</th>
                                <th class="text-center" width="20%">Vl. total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in itensCompras|filter:pesquisa">
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{item.nome_produto}}</td>
                                <td class="text-center">{{item.qtde}}</td>
                                <td class="text-center">{{item.valor_uni | currency: 'R$ '}}</td>
                                <td class="text-center">{{item.valor_total | currency: 'R$ '}}</td>
                            </tr>
                            <tr ng-show="itensCompras.length == 0">
                                <td class="text-center" colspan="7">Nenhum item referente a essa compra</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>