 <?php
echo link_ng('vendas/controllers/listaVendasController.js');
?>
<div ng-controller="listaVendasController" ng-cloak>
    <div ng-show="tela == 1">
        <div class="page-header-topo">
            <h3 id="grid" class="">
                Lista de Vendas
            </h3>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-8">
                <div class="pull-left">
                    <div class="form-group">
                        <button type="button" class="btn btn-success btn-sm" ng-click="cadVendas()">
                            <span class="glyphicon glyphicon-plus"></span> Cadastrar venda
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
                                <th class="text-center" width="20%">Data venda</th>
                                <th class="text-center" width="20%">Valor total</th>
                                <th class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="e in listaVendas|filter:filtro">
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{e.nome_fornecedor}}</td>
                                <td class="text-center">{{e.nf}}</td>
                                <td class="text-center">{{e.dt_compra}}</td>
                                <td class="text-center">{{e.valor_total | currency: 'R$ '}}</td>
                                <td class="text-center">
                                    <button class="btn btn-default btn-sm" title="Ver itens da venda"
                                        ng-click="verItensVendas(e)">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr ng-show="listaVendas.length == 0">
                                <td class="text-center" colspan="6">Nenhuma venda cadastrada</td>
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
                Itens da venda
            </h3>
            <h5>Cliente : <b>{{venda.nome_fornecedor}}</b></h5>
            <h5>
                <span>
                    Data da venda : <b>{{venda.dt_compra}}</b>
                </span>
                <span style="margin-left: 10%;">
                    Valor total : <b>{{venda.valor_total | currency:'R$ '}}</b>
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
                            <tr ng-repeat="item in itensVendas|filter:pesquisa">
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{item.nome_produto}}</td>
                                <td class="text-center">{{item.qtde}}</td>
                                <td class="text-center">{{item.valor_uni | currency: 'R$ '}}</td>
                                <td class="text-center">{{item.valor_total | currency: 'R$ '}}</td>
                            </tr>
                            <tr ng-show="itensVendas.length == 0">
                                <td class="text-center" colspan="7">Nenhum item referente a essa venda</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>