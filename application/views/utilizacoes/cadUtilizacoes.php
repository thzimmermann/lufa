<style type="text/css">
    .form-group-error {
        border:1px solid #FF0000;
    }
    .select2-container .select2-choice {
        height: 34px !important;
    }
</style>
<?php
echo link_ng('utilizacoes/controllers/cadUtilizacoesController.js');
?>
<div ng-controller="cadUtilizacoesController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Registro de movimentações
        </h3>
    </div>
    <hr>
    <br>
    <form novalidate name="form_utilizacao" id="form_utilizacao" ng-submit="submitUtilizacao()">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="id_plantacao">*Plantação:</label>
                    <input ng-model="dados.id_plantacao"  ng-class="form_utilizacao.id_plantacao.$dirty && form_utilizacao.id_plantacao.$invalid?'form-group-error':''" select2 urlajax="../plantacao/getPlantacaoFiltro" type="hidden" name="id_plantacao" id="id_plantacao">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="id_produto">*Produto(Estoque: {{qtde_max}}):</label>
                    <input ng-change="changeProduto()" ng-model="dados.id_produto"  ng-class="form_utilizacao.id_produto.$dirty && form_utilizacao.id_produto.$invalid?'form-group-error':''" select2 urlajax="../produtos/getProdutosFiltro" type="hidden" name="id_produto" id="id_produto">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2" >
                <div class="form-group">
                    <label for="qtde">*Qtde:</label>
                    <input  ng-class="form_utilizacao.qtde.$dirty && form_utilizacao.qtde.$invalid?'form-group-error':''" type="text" name="qtde" id="qtde" class="form-control" ng-required="true" ng-model="dados.qtde" ui-number-mask="2" max="qtde_max" ui-hide-group-sep>
                </div>
            </div>
            <div class="col-sm-2" >
                <div class="form-group">
                    <label for="status">*Movimentação:</label>
                    <select name="status" id="status" ng-class="form_utilizacao.status.$dirty && form_utilizacao.status.$invalid?'form-group-error':''" class="form-control" ng-model="dados.status">
                        <option value="S">Saída</option>
                        <option value="E">Entrada</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4" >
                <div class="form-group">
                    <label for="dt_utilizacao">*Dt. da utilização:</label>
                    <input  ng-class="form_utilizacao.dt_utilizacao.$dirty && form_utilizacao.dt_utilizacao.$invalid?'form-group-error':''" type="date" name="dt_utilizacao" id="dt_utilizacao" class="form-control" ng-required="true" ng-model="dados.dt_utilizacao">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="pull-right">
                   <!--  <button type="button" class="btn btn-warning" ng-click="voltar()">Voltar</button> -->
                    <button type="submit" class="btn btn-success" ng-disabled="form_utilizacao.$invalid">Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>
