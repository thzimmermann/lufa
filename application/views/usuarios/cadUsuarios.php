<style type="text/css">
    .form-group-error {
        border:1px solid #FF0000;
    }

    .select2-container .select2-choice {
        height: 34px !important;
    }
</style>
<?php
echo link_ng('usuarios/controllers/cadUsuariosController.js');
?>
<div ng-controller="cadUsuariosController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Cadastro de usuários
        </h3>
    </div>
    <hr>
    <br>
    <form novalidate name="form_usuarios" id="form_usuarios" ng-submit="submitUsuario()">
        <div class="row">
            <div class="col-sm-6" >
                <div class="form-group">
                    <label for="nome">*Usuário:</label>
                    <input ng-class="form_usuarios.nome.$dirty && form_usuarios.nome.$invalid?'form-group-error':''" type="text" name="nome" id="nome" maxlength="100" class="form-control" ng-required="true" ng-model="dados.nome">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="email">*E-mail:</label><br>
                    <input ng-class="form_usuarios.email.$dirty && form_usuarios.email.$invalid?'form-group-error':''" type="email" name="email" id="email" class="form-control" ng-required="true" ng-model="dados.email">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="usuario_login">*Login:</label><br>
                    <input ng-class="form_usuarios.usuario_login.$dirty && form_usuarios.usuario_login.$invalid?'form-group-error':''" type="text" name="usuario_login" id="usuario_login" class="form-control" ng-required="true" ng-model="dados.usuario_login" ng-disabled="dados.id_usuario > 0">
                </div>
            </div>
            <div class="col-sm-3" ng-if="(!dados.id_usuario > 0)">
                <div class="form-group">
                    <label for="senha">*Senha</label>
                    <input ng-class="(form_usuarios.senha.$dirty && form_usuarios.senha.$invalid?'form-group-error':'') ||( senhaConfirmada != dados.senha?'form-group-error':'')" type="password" name="senha" id="senha" maxlength="20" class="form-control" ng-required="true" ng-model="dados.senha">
                </div>
            </div>
            <div class="col-sm-3" ng-if="(!dados.id_usuario > 0)">
                <div class="form-group">
                    <label for="senhaConfirmada">*Confirmar senha</label>
                    <span style="font-size: 10px; color: red;" ng-show="senhaConfirmada != dados.senha?'form-group-error':''">(Senhas não conferem)</span>
                    <input ng-class="senhaConfirmada != dados.senha?'form-group-error':''" type="password" name="senhaConfirmada" id="senhaConfirmada" maxlength="20" class="form-control" ng-required="true" ng-model="senhaConfirmada">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="telefone">*Telefone</label>
                    <input  ng-class="form_usuarios.telefone.$dirty && form_usuarios.telefone.$invalid?'form-group-error':''" type="text" name="telefone" id="telefone" maxlength="20" class="form-control" ng-required="true" ng-model="dados.telefone" ui-br-phone-number>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="pull-right">
                    <button type="button" class="btn btn-warning" ng-click="voltar()">Voltar</button>
                    <button type="submit" class="btn btn-success" ng-disabled="form_usuarios.$invalid">Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>