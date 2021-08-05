<style type="text/css">
    .form-group-error {
        border:1px solid #FF0000;
    }
    .select2-container .select2-choice {
        height: 34px !important;
    }
</style>
<?php
echo link_ng('fornecedores/controllers/cadFornecedorController.js');
?>
<div ng-controller="cadFornecedorController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Cadastro de fornecedor
        </h3>
    </div>
    <hr>
    <br>
    <form novalidate name="form_fornecedor" id="form_fornecedor" ng-submit="submitFornecedor()">
        <div class="row">
            <div class="col-sm-6" >
                <div class="form-group">
                    <label for="nome">*Nome do fornecedor:</label>
                    <input  ng-class="form_fornecedor.nome.$dirty && form_fornecedor.nome.$invalid?'form-group-error':''" type="text" name="nome" id="nome" maxlength="100" class="form-control" ng-required="true" ng-model="dados.nome">
                </div>
            </div>
            <div class="col-sm-3" >
                <div class="form-group">
                    <label for="nome">*Tipo fornecedor:</label><br>
                    <label class="radio-inline"><input type="radio" value="J" name="optradio" ng-model="dados.tipo_empresa" checked>Juridica</label>
                    <label class="radio-inline"><input type="radio" value="F" name="optradio" ng-model="dados.tipo_empresa">Fisica</label>
                </div>
            </div>
            <div class="col-sm-3" >
                <div class="form-group">
                    <label for="cnpj_cpf">*{{dados.tipo_empresa == 'J'?'CNPJ':dados.tipo_empresa == 'F'?'CPF':'CNPJ/CPF'}}:</label>
                    <input  ng-class="form_fornecedor.cnpj_cpf.$dirty && form_fornecedor.cnpj_cpf.$invalid?'form-group-error':''" type="text" name="cnpj_cpf" id="cnpj_cpf" maxlength="20" class="form-control" ng-required="true" ng-model="dados.cnpj_cpf" ui-br-cpfcnpj-mask>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6" >
                <div class="form-group">
                    <label for="email">*E-mail:</label>
                    <input  ng-class="form_fornecedor.email.$dirty && form_fornecedor.email.$invalid?'form-group-error':''" type="email" name="email" id="email" maxlength="150" class="form-control" ng-required="true" ng-model="dados.email">
                </div>
            </div>
            <div class="col-sm-3" >
                <div class="form-group">
                    <label for="telefone">*Telefone:</label>
                    <input  ng-class="form_fornecedor.telefone.$dirty && form_fornecedor.telefone.$invalid?'form-group-error':''" type="text" name="telefone" id="telefone" maxlength="20" class="form-control" ng-required="true" ng-model="dados.telefone" ui-br-phone-number>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="id_cidade">*Cidade:</label>
                    <input ng-model="dados.id_cidade"  ng-class="form_fornecedor.endereco.$dirty && form_fornecedor.endereco.$invalid?'form-group-error':''" select2 urlajax="../cidades/getCidadesFiltro" minl="3" type="hidden" name="id_cidade" id="id_cidade">
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="endereco">*Endereço:</label>
                    <input type="text" class="form-control" ng-class="form_fornecedor.endereco.$dirty && form_fornecedor.endereco.$invalid?'form-group-error':''" ng-model="dados.endereco" name="endereco" id="endereco">
                </div>
            </div>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-warning" ng-click="voltar()">Voltar</button>
            <button type="submit" class="btn btn-success" ng-disabled="form_fornecedor.$invalid">Salvar</button>
        </div>
    </form>
</div>
