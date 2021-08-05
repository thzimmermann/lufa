<style type="text/css">
    .form-group-error {
        border:1px solid #FF0000;
    }
    .select2-container .select2-choice {
        height: 34px !important;
    }
</style>
<?php
echo link_ng('plantacao/controllers/cadPlantacaoController.js');
?>
<div ng-controller="cadPlantacaoController" ng-cloak>
    <div class="page-header-topo">
        <h3 id="grid" class="">
            Cadastro de plantação
        </h3>
    </div>
    <hr>
    <br>
    <form novalidate name="form_plantacao" id="form_plantacao" ng-submit="submitPlantacao()">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="id_cultivo">*Item cultivo:</label>
                    <input ng-model="dados.id_cultivo"  ng-class="form_plantacao.id_cultivo.$dirty && form_plantacao.id_cultivo.$invalid?'form-group-error':''" select2 urlajax="../plantacao/getItemColheitaFiltro" type="hidden" name="id_cultivo" id="id_cultivo">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="id_terreno">*Terreno:</label>
                    <input ng-model="dados.id_terreno"  ng-class="form_plantacao.id_terreno.$dirty && form_plantacao.id_terreno.$invalid?'form-group-error':''" select2 urlajax="../terrenos/getTerrenoFiltro" type="hidden" name="id_terreno" id="id_terreno">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" >
                <div class="form-group">
                    <label for="dt_plantacao">*Dt. da plantação:</label>
                    <datepicker date-format="dd/MM/yyyy" selector="form-control" ng-class="form_compras.dt_plantacao.$dirty && form_compras.dt_plantacao.$invalid?'form-group-error':''">
                        <div class="input-group">
                            <input class="form-control" placeholder="Escolha a data"  ng-model="dados.dt_plantacao"/>
                            <span class="input-group-addon" style="cursor: pointer">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </div>
                    </datepicker>
                </div>
            </div>
            <div class="col-sm-4" >
                <div class="form-group">
                    <label for="dt_estimativa_colheita">*Dt. da estimativa:</label>
                    <datepicker date-format="dd/MM/yyyy" selector="form-control" ng-class="form_compras.dt_estimativa_colheita.$dirty && form_compras.dt_estimativa_colheita.$invalid?'form-group-error':''">
                        <div class="input-group">
                            <input class="form-control" placeholder="Escolha a data"  ng-model="dados.dt_estimativa_colheita"/>
                            <span class="input-group-addon" style="cursor: pointer">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </div>
                    </datepicker>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="status">*Status:</label>
                    <select name="status" id="status" ng-class="form_utilizacao.status.$dirty && form_utilizacao.status.$invalid?'form-group-error':''" class="form-control" ng-model="dados.status">
                        <option value="S">Ativo</option>
                        <option value="I">Inativo</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <div class="pull-right">
                    <button type="button" class="btn btn-warning" ng-click="voltar()">Voltar</button>
                    <button type="submit" class="btn btn-success" ng-disabled="form_plantacao.$invalid">Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>
