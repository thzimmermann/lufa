<?php
echo link_ng('estoque/controllers/listaMovimentacoesController.js');
?>
<div ng-controller="listaMovimentacoesController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Lista de movimentações
        </h3>
        <h4>
            <span>
                Produto : <b>{{dadosProduto.nome}}</b>
            </span>
            <span style="margin-left: 10%;">
                Tipo : <b>{{dadosProduto.tipo_produto}}</b>
            </span>
        </h4>
        <h4>
            <span>
                Estoque mín: <b>{{dadosProduto.estoque_minimo}}</b>
            </span>
            <span style="margin-left: 10%;">
                Estoque máx: <b>{{dadosProduto.estoque_maximo}}</b>
            </span>
        </h4>
        <a type="button" class="btn btn-danger" ng-href="relatorioMovimentacoesProduto?id_produto={{id_produto}}&tipo=P" target="_blank">
            <span class="glyphicon glyphicon-list-alt"></span> Gerar PDF
        </a>
        <a type="button" class="btn btn-success" ng-href="relatorioMovimentacoesProduto?id_produto={{id_produto}}&tipo=E" target="_blank">
            <span class="glyphicon glyphicon-list-alt"></span> Gerar Excel
        </a>
        <hr>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <div class="pull-left">
                <form class="form-inline">
                    <div class="form-group ">
                        <label>Filtro por ano: </label>
                        <select class="form-control" ng-model="filtroAno">
                            <option ng-repeat="a in filtroAnos" ng-value="a.ano">{{a.label}}</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="pull-left">
                <form class="form-inline">
                    <div class="form-group">
                        <label>Movimentacao: </label>
                        <select class="form-control" ng-model="movimentacao">
                            <option value="">Todas</option>
                            <option value="C">Compras</option>
                            <option value="E">Entrada</option>
                            <option value="S">Saída</option>
                            <option value="V">Vendas</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="pull-right">
                <form class="form-inline">
                    <div class="form-group">
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
                            <th class="text-center">Data</th>
                            <th>Plantação</th>
                            <th>Movimentação</th>
                            <th class="text-center">Qtde</th>
                            <th class="text-center">Valor unitário</th>
                            <th class="text-center">Valor total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="e in listaMovimentacoes|filter:filtro|filter: {filtro_ano: filtroAno}|filter: {status: movimentacao}" ng-style="{'background-color':e.local == 'I'?(e.status == 'C'?'#9df7bd':'#ff968e'):(e.status == 'S'?'#ff968e':'#9df7bd')}"/>
                            <td class="text-center">{{$index+1}}</td>
                            <td class="text-center">{{e.data}}</td>
                            <td>{{e.item_cultivo == ''?'':e.dt_plantacao+' - '+e.item_cultivo+' - '+e.desc_terreno}}</td>
                            <td>{{e.local == 'I'?(e.status == 'C'?'Compras':'Vendas'):(e.status == 'S'?'Saída (Utilizações)':'Entrada (Utilizações)')}}</td>
                            <td class="text-center">{{e.qtde}}</td>
                            <td class="text-center">{{e.valor_uni == 0?' - ':e.valor_uni | currency:'R$ '}}</td>
                            <td class="text-center">{{e.valor_total == 0?' - ':e.valor_total | currency:'R$ '}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="pull-right">
        <button type="button" class="btn btn-warning" ng-click="voltar()">Voltar</button>
    </div>
</div>