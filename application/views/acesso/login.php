<?php
    echo link_ng('acesso/controller/loginController.js');
?>
<div class="container login-container" ng-controller="loginController">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 login-form-1">
            <h3 class="text-center">Login</h3>
            <form ng-submit="setAutenticacao()">
                <div class="form-group">
                    <label>Usuário: </label>
                    <input type="text" class="form-control" placeholder="login" ng-model="dados.usuario"  ng-required="true"/>
                </div>
                <div class="form-group">
                    <label>Senha: </label>
                    <input type="password" class="form-control" placeholder="senha" ng-model="dados.senha" ng-required="true"/>
                </div>
                <!-- <div class="form-group text-right">
                    <a href="#" class="ForgetPwd">Esqueceu a senha?</a>
                </div> -->
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Login"/>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
