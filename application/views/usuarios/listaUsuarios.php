<?php
echo link_ng('usuarios/controllers/listaUsuariosController.js');
?>
<div ng-controller="listaUsuariosController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Lista de Usuarios
        </h3>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <div class="pull-left">
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-sm" ng-click="cadUsuarios()">
                        <span class="glyphicon glyphicon-plus"></span> Cadastro de usuários
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
                        <th width="25%">Nome</th>
                        <th>Login</th>
                        <th>E-mail</th>
                        <th class="text-center">Telefone</th>
                        <th class="text-center" width="8%">Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="e in listaUsuarios|filter:filtro">
                        <td class="text-center">{{$index+1}}</td>
                        <td>{{e.nome}}</td>
                        <td>{{e.usuario_login}}</td>
                        <td>{{e.email}}</td>
                        <td class="text-center">{{e.telefone | brPhoneNumber}}</td>
                        <td class="text-center">
                            <button class="btn btn-default btn-sm btn-xs"
                                ng-click="editarUsuario(e.id_usuario)">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </td>
                    </tr>
                    <tr ng-show="listaUsuarios.length == 0">
                        <td class="text-center" colspan="6">Nenhum usuário cadastrado</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>