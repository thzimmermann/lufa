<style type="text/css">
	.graficos {
		    width: 529px;
    height: 263px;display: block;
	}

</style>
<?php
echo link_ng('plantacao/controllers/gerenciadorPlantacaoController.js');
?>
<div ng-controller="gerenciadorPlantacaoController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Gerenciador
        </h3>
    </div><br>
    <hr>
    <!-- <div class="row">
        <div class="col-sm-8">
            <div class="pull-left">
                <div class="form-group">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="pull-right">
                <form class="form-inline">
                    <div class="form-group">
                        <label>Selecionar plantação: </label>
                        <select class="form-control" ng-model="filtroPlantacao" ng-change="buscaPlantacao(filtroPlantacao)">
                            <option value="">Todas</option>
                            <option ng-repeat="a in filtroPlantacoes" ng-value="a.id">{{a.text}}</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br> -->
    <div class="panel panel-default">
        <div class="panel-heading">Fornecedores Compras/Vendas(R$)</div>
        <div class="panel-body graficos">
            <canvas id="bar" class="chart chart-bar" chart-data="data_fornecedores" chart-colors="colors" chart-labels="label_fornecedores" chart-series="series_fornecedores"  chart-options="options_fornecedores" width="529" height="263"> </canvas>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Estoque de produtos</div>
        <div class="panel-body graficos">
            <canvas id="pie" class="chart chart-pie chart-xs ng-isolate-scope" chart-data="data_estoques" chart-colors="colors" chart-labels="label_estoques" chart-series="series_estoques"  chart-options="options_estoques" width="529" height="263"></canvas>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Razão Compras/Vendas(R$)</div>
        <div class="panel-body graficos">
            <canvas id="bar" class="chart chart-bar chart-xs ng-isolate-scope" chart-data="data" chart-colors="colors" chart-labels="labels" width="529" height="263"></canvas>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Vendas(R$) por plantação </div>
        <div class="panel-body graficos">
            <canvas id="pie" class="chart chart-pie chart-xs ng-isolate-scope" chart-data="data1" chart-colors="colors" chart-labels="labels1" width="529" height="263"></canvas>
        </div>
    </div>
</div>