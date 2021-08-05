<?php
echo link_ng('fornecedores/controllers/listaFornecedoresController.js');
?>
<div ng-controller="listaFornecedoresController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Lista de fornecedores
        </h3>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <div class="pull-left">
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-sm" ng-click="cadFornecedor()">
                        <span class="glyphicon glyphicon-plus"></span> Cadastrar fornecedor
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
                            <th>Nome</th>
                            <th>Email</th>
                            <th class="text-center">CNPJ/CPF</th>
                            <th class="text-center" width="10%">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="e in listaFornecedores|filter:filtro">
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{e.nome}}</td>
                            <td>{{e.email}}</td>
                            <td class="text-center">{{e.tipo_empresa == 'J'?(e.cnpj_cpf | brCnpj):(e.cnpj_cpf | brCpf)}}</td>
                            <th class="text-center">
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-click="editarFornecedor(e.id_fornecedor)">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>