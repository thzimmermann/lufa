<style type="text/css">
    .form-group-error {
        border:1px solid #FF0000;
    }
</style>
<?php
echo link_ng('produtos/controllers/cadTiposProdutosController.js');
?>
<div ng-controller="cadTiposProdutosController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Cadastro Tipos de Produtos
        </h3>
    </div>
    <hr>
    <br>
    <form novalidate name="form_tipo_produto" id="form_tipo_produto" ng-submit="submitTipoProdutos()">
        <div class="row">
            <div class="col-sm-8" >
                <div class="form-group">
                    <label for="nome">*Tipo de Produto:</label>
                    <input ng-class="form_tipo_produto.nome.$dirty && form_tipo_produto.nome.$invalid?'form-group-error':''" type="text" name="nome" id="nome" maxlength="100" class="form-control" ng-required="true" ng-model="dados.nome">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="pull-right">
                    <button type="button" class="btn btn-warning" ng-click="voltar()">Voltar</button>
                    <button type="submit" class="btn btn-success" ng-disabled="form_tipo_produto.$invalid">Salvar</button>
                </div>
            </div>
        </div>
    </form>
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