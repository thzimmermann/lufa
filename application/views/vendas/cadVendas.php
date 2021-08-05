<style type="text/css">
    .form-group-error {
        border:1px solid #FF0000 !important;
    }
    .select2-container .select2-choice {
        height: 34px !important;
    }
</style>
<?php
echo link_ng('vendas/controllers/cadVendasController.js');
?>
<div ng-controller="cadVendasController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Cadastro de venda
        </h3>
    </div><br>
    <hr>
    <form novalidate name="form_compras" id="form_compras">
        <div class="row">
            <div class="col-sm-6" >
                <div class="form-group">
                    <label for="nome">*Cliente:</label>
                    <input ng-model="dados.id_fornecedor"  ng-class="form_compras.id_fornecedor.$dirty && form_compras.id_fornecedor.$invalid?'form-group-error':''" select2 urlajax="../fornecedores/getFornecedorFiltro" type="hidden" name="id_fornecedor" id="id_fornecedor">
                </div>
            </div>
            <div class="col-sm-3" >
                <div class="form-group">
                    <label for="nf">*NF:</label>
                    <input  ng-class="form_compras.nf.$dirty && form_compras.nf.$invalid?'form-group-error':''" type="text" name="nf" id="nf" class="form-control" ng-required="true" ng-model="dados.nf" ui-number-mask="0" ui-hide-group-sep>
                </div>
            </div>
            <div class="col-sm-3" >
                <div class="form-group">
                    <label for="dt_compra">*Data da venda:</label>
                    <input  ng-class="form_compras.dt_compra.$dirty && form_compras.dt_compra.$invalid?'form-group-error':''" type="date" name="dt_compra" id="dt_compra" class="form-control" ng-required="true" ng-model="dados.dt_compra">
                </div>
            </div>
        </div>
        <div class="page-header-topo">
            <h3 id="grid" class="">
                Itens venda
            </h3>
        </div>
        <hr>
        <form novalidate name="form_item" id="form_item">
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="id_produto">*Produto(Estoque: {{qtde_max}}):</label>
                        <input ng-model="itensCompras.id_produto"  ng-class="form_compras.id_produto.$dirty && form_compras.id_produto.$invalid?'form-group-error':''" select2 urlajax="../produtos/getProdutosFiltro" type="hidden" name="id_produto" id="id_produto" ng-required="true" ng-change="changeProduto()">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="qtde">*Qtde.:</label>
                         <input  ng-class="form_compras.qtde.$dirty && form_compras.qtde.$invalid?'form-group-error':''" type="text" name="qtde" id="qtde" maxlength="20" class="form-control" ng-required="true" ng-model="itensCompras.qtde" ui-number-mask="0" max="qtde_max" ui-hide-group-sep>

                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="valor_uni">*Vl. unit&aacute;rio:</label>
                        <input type="text" class="form-control" ng-class="form_compras.valor_uni.$dirty && form_compras.valor_uni.$invalid?'form-group-error':''" ng-model="itensCompras.valor_uni" name="valor_uni" id="valor_uni" ui-money-mask ng-change="calculaValorTotal()" ng-required="true">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="valor_total">*Vl. total:</label>
                        <input type="text" class="form-control" ng-class="form_compras.valor_total.$dirty && form_compras.valor_total.$invalid?'form-group-error':''" ng-model="itensCompras.valor_total" name="valor_total" id="valor_total" ui-money-mask ng-required="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class=" pull-right">
                        <button type="button" class="btn btn-warning" ng-click="salvarItem()"><span class="glyphicon glyphicon-plus">Adicionar</span></button>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <div class="page-header-topo">
            <h3 id="grid" class="">
                Lista itens
            </h3>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Produto</th>
                                <th class="text-center" width="10%">Qtde.</th>
                                <th class="text-center">Valor unitário</th>
                                <th class="text-center">Valor total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="e in itensComprasArray|filter:filtro">
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{e.nome_produto}}</td>
                                <td class="text-center">{{e.qtde}}</td>
                                <td class="text-center">{{e.valor_uni | currency:'R$ '}}</td>
                                <td class="text-center">{{e.valor_total | currency:'R$ '}}</td>
                            </tr>
                            <tr ng-show="itensComprasArray.length == 0">
                                <td class="text-center" colspan="5">Nenhum item cadastrado</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="pull-right">
            <button type="button" class="btn btn-warning" ng-click="voltar()">Voltar</button>
            <button type="button" class="btn btn-success" ng-click="submitVendas()">Salvar venda</button>
        </div>
    </form>
</div>
