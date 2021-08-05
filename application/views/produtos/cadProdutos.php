<style type="text/css">
    .form-group-error {
        border:1px solid #FF0000;
    }

    .select2-container .select2-choice {
        height: 34px !important;
    }
</style>
<?php
echo link_ng('produtos/controllers/cadProdutosController.js');
?>
<div ng-controller="cadProdutosController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Cadastro de produtos
        </h3>
    </div>
    <hr>
    <br>
    <form novalidate name="form_produto" id="form_produto" ng-submit="submitProdutos()">
        <div class="row">
            <div class="col-sm-8" >
                <div class="form-group">
                    <label for="nome">*Produto:</label>
                    <input ng-class="form_produto.nome.$dirty && form_produto.nome.$invalid?'form-group-error':''" type="text" name="nome" id="nome" maxlength="100" class="form-control" ng-required="true" ng-model="dados.nome">
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="estoque_minimo">*Estoque mínimo:</label><br>
                    <input ui-number-mask="0" ng-class="form_produto.estoque_minimo.$dirty && form_produto.estoque_minimo.$invalid?'form-group-error':''" type="text" name="estoque_minimo" id="estoque_minimo" maxlength="20" class="form-control" ng-required="true" ng-model="dados.estoque_minimo">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="estoque_maximo">*Estoque máximo</label>
                    <input ui-number-mask="0" ng-class="form_produto.estoque_maximo.$dirty && form_produto.estoque_maximo.$invalid?'form-group-error':''" type="text" name="estoque_maximo" id="estoque_maximo" maxlength="20" class="form-control" ng-required="true" ng-model="dados.estoque_maximo">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="id_tipo_produto">*Tipo de Produto:</label>
                    <input ng-model="dados.id_tipo_produto"  ng-class="form_produto.id_tipo_produto.$dirty && form_produto.id_tipo_produto.$invalid?'form-group-error':''" select2 urlajax="../produtos/getTipoProdutoFiltro" type="hidden" name="id_tipo_produto" id="id_tipo_produto">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="pull-right">
                    <button type="button" class="btn btn-warning" ng-click="voltar()">Voltar</button>
                    <button type="submit" class="btn btn-success" ng-disabled="form_produto.$invalid">Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>