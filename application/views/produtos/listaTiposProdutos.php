<?php
echo link_ng('produtos/controllers/listaTiposProdutosController.js');
?>
<div ng-controller="listaTiposProdutosController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Lista Tipos de Produtos
        </h3>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <div class="pull-left">
                <div class="form-group">
                    <button type="button" class="btn btn-warning btn-sm" ng-click="voltar()">
                        <span class="glyphicon glyphicon-arrow-left"></span> Voltar
                    </button>
                    <button type="button" class="btn btn-success btn-sm" ng-click="cadTipoProdutos()">
                        <span class="glyphicon glyphicon-plus"></span> Cadastro de tipos
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
                            <th class="text-center" width="10%">#</th>
                            <th>Nome</th>
                            <th class="text-center" width="10%">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="e in listaTipoProdutos|filter:filtro">
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{e.nome}}</td>
                            <td class="text-center">
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="editarTipoProduto(e.id_tipo_produto)">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </td>
                        </tr>
                        <tr ng-show="listaTipoProdutos.length == 0">
                            <td class="text-center" colspan="3">Nenhum tipo de produto cadastrado</td>
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