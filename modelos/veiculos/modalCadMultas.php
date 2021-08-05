<script type="text/javascript">
    function changeValueData(element) {
        angular.element(element).triggerHandler('input');
    }
    $(function(){
        $("#i_cidade_ibge").select2({
            minimumInputLength: 3,
            ajax: {
                url: "getCidadesAjax",
                dataType: "json",
                data: function (term, page) {
                    return {
                        busca: term
                    };
                },
                results: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
        });
        $("#i_patrimonio").select2({
            minimumInputLength: 0,
            ajax: {
                url: "getVeiculosAjax",
                dataType: "json",
                data: function (term, page) {
                    return {
                        busca: term
                    };
                },
                results: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
        });
    });
</script>
<style>
    .form-group-error a.select2-choice {
        border:1px solid #FF0000;
    }
</style>
<div class="modal fade" id="modalCadMultas" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro de Multas</h4>
            </div>
            <div class="modal-body">
                <form novalidate name="form_multas" id="form_multas" ng-submit="submitMultas()">
                    <div class="alert alert-danger" ng-if="form_multas.$submitted && form_multas.$error.required">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        &Eacute; necess&aacute;rio preencher a Data de Validade e/ou a Descrição.
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="i_patrimonio">Ve&iacute;culos:</label>
                                <input ng-model="multas.i_patrimonio" select2 type="hidden" name="i_patrimonio" id="i_patrimonio">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="dt_multa">Data:</label>
                                <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                    <input type="text" name="dt_multa" ng-model="multas.dt_multa" id="dt_multa"
                                    class="form-control input-sm mask_date form-control"
                                    onkeydown="changeValueData(this)" onkeyup="changeValueData(this)" ng-required="true">
                                    <span class="input-group-addon icon_addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="hr_multa">Hora:</label>
                            <div class="input-group bootstrap-timepicker">
                                <input type="text" name="hr_multa" value="" id="hr_multa" maxlength="8"
                                class="form-control input-sm form-control ng-pristine ng-untouched ng-invalid ng-invalid-required ng-valid-maxlength"
                                data-inputmask="'mask': '99:99'" ng-model="multas.hr_multa" ng-required="true">
                                <span class="input-group-addon">
                                    <i class="icon-time"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group" ng-class="form_multas.$submitted && form_multas.i_cidade_ibge.$error.required?'form-group-error':''">
                                <label for="i_cidade_ibge">Cidade:</label>
                                <input ng-model="multas.i_cidade_ibge" select2 type="hidden" name="i_cidade_ibge" id="i_cidade_ibge">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="cod_infracao">C&oacute;d. Infra&ccedil;&atilde;o:</label>
                                <input type="text" name="cod_infracao" id="cod_infracao" maxlength="15" class="form-control input-sm  ng-pristine ng-valid ng-valid-maxlength ng-touched" ng-required="true" ng-model="multas.cod_infracao">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="gravidade">Gravidade:</label>
                                <select name="gravidade" id="gravidade"
                                class="form-control input-sm form-control ng-pristine ng-invalid ng-invalid-required ng-touched"
                                ng-model="multas.gravidade" ng-required="true" >
                                    <option value="V">Grav&iacute;ssima</option>
                                    <option value="G">Grave</option>
                                    <option value="M">M&eacute;dia</option>
                                    <option value="L">Leve</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="pontos">Pontos:</label>
                                <select name="pontos" id="pontos"
                                class="form-control input-sm form-control ng-pristine ng-invalid ng-invalid-required ng-touched"
                                ng-model="multas.pontos" ng-required="true">
                                    <option value="7">7 pontos</option>
                                    <option value="5">5 pontos</option>
                                    <option value="4">4 pontos</option>
                                    <option value="3">3 pontos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary" ng-disabled="form_multas.$invalid">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
