<?php
echo link_ng('produtos/controllers/listaProdutosController.js');
?>
<div ng-controller="listaProdutosController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Lista de Produtos
        </h3>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <div class="pull-left">
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-sm" ng-click="listaTiposProdutos()">
                        <span class="glyphicon glyphicon-list-alt"></span> Tipos de produtos
                    </button>
                    <button type="button" class="btn btn-success btn-sm" ng-click="cadProdutos()">
                        <span class="glyphicon glyphicon-plus"></span> Cadastro de produtos
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
                            <th width="45%">Nome</th>
                            <th class="text-center">Tipo produto</th>
                            <th class="text-center">Estq. Mínimo</th>
                            <th class="text-center">Estq. Máximo</th>
                            <th class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="e in listaProdutos|filter:filtro">
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{e.nome}}</td>
                            <td class="text-center">{{e.tipo_produto}}</td>
                            <td class="text-center">{{e.estoque_minimo}}</td>
                            <td class="text-center">{{e.estoque_maximo}}</td>
                            <td class="text-center">
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
                        <tr ng-show="listaProdutos.length == 0">
                            <td class="text-center" colspan="6">Nenhum produto cadastrado</td>
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