<script type="text/javascript">
    function changeValueData(element) {
        angular.element(element).triggerHandler('input');
    }
    $(function(){
        $("#i_unidade").select2({
            minimumInputLength: 0,
            ajax: {
                url: "getContasUnidadesAjax",
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
<style type="text/css">
    .linkBut {
        background-color: #ececec;
        width: 100%;
        border: solid 1px #d8d8d8;
        padding: 4px 4px;
        -webkit-transition: 0.3s;
        transition: 0.3s;
    }

    .linkBut:hover {
        background-color: rgb(220, 220, 220);
        text-decoration: none;color: #000;
    }

    .form-group-error a.select2-choice {
        border:1px solid #FF0000;
    }
</style>
<div class="modal fade" id="modalCadCombustivel" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro de Combust&iacute;veis</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" ng-show="mensagem" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{mensagem}}
                </div>
                <form novalidate name="form_combustiveis" id="form_combustiveis">
                    <div class="row text-center">
                        <div class="form-group" id="container">
                            <a id="pickfiles" href="javascript:;" class="btn btn-anexo btn-primary btn-sm">
                                <span class="glyphicon glyphicon-paperclip"></span>
                                Anexar Xml
                            </a>
                            <br>
                            <small>
                                (Tamanho max&iacute;mo de 10mb)
                            </small>
                            <div id="filelist"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" ng-class="form_combustiveis.$submitted && form_combustiveis.i_patrimonio.$error.required?'form-group-error':''">
                                <label for="i_patrimonio">Ve&iacute;culo:</label>
                                <input ng-model="combustiveis.i_patrimonio" select2 type="hidden" name="i_patrimonio" id="i_patrimonio">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" ng-class="form_combustiveis.$submitted && form_combustiveis.i_unidade.$error.required?'form-group-error':''">
                                <label for="i_unidade">Unidade:</label>
                                <input ng-model="combustiveis.i_unidade" select2 type="hidden" name="i_unidade" id="i_unidade">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" ng-class="form_combustiveis.$submitted && form_combustiveis.dt_abastecimento.$error.required?'form-group-error':''">
                                <label for="dt_abastecimento">Data:</label>
                                <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                    <input type="text" name="dt_abastecimento" ng-model="combustiveis.dt_abastecimento" id="dt_abastecimento"
                                    class="form-control input-sm mask_date form-control"
                                    onkeydown="changeValueData(this)" onkeyup="changeValueData(this)" ng-required="true">
                                    <span class="input-group-addon icon_addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="hr_abastecimento">Hora:</label>
                            <div class="input-group bootstrap-timepicker" ng-class="form_combustiveis.$submitted && form_combustiveis.hr_abastecimento.$error.required?'form-group-error':''">
                                <input type="text" name="hr_abastecimento" value="" id="hr_abastecimento" maxlength="8"
                                class="form-control input-sm form-control"
                                data-inputmask="'mask': '99:99'" ng-model="combustiveis.hr_abastecimento" ng-required="true">
                                <span class="input-group-addon">
                                    <i class="icon-time"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group" ng-class="form_combustiveis.$submitted && form_combustiveis.combustivel.$error.required?'form-group-error':''">
                                <label for="combustivel">Combust&iacute;vel:</label>
                                <select name="combustivel" id="combustivel"
                                class="form-control input-sm form-control ng-pristine ng-invalid ng-invalid-required ng-touched"
                                ng-model="combustiveis.combustivel" ng-required="true" >
                                    <option value="">-Selecione-</option>
                                    <option value="G">Gasolina</option>
                                    <option value="D">Diesel</option>
                                    <option value="A">Etanol</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" ng-class="form_combustiveis.$submitted && form_combustiveis.litros.$error.required?'form-group-error':''">
                                <label for="litros">Litros:</label>
                                <input type="text" ui-number-mask="2" name="litros" id="litros" maxlength="15" class="form-control input-sm" ng-required="true" required="1" ng-model="combustiveis.litros">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" ng-class="form_combustiveis.$submitted && form_combustiveis.valor.$error.required?'form-group-error':''">
                                <label for="valor">Valor:</label>
                                <input type="text" ui-money-mask="2"  name="valor" id="valor" maxlength="15" class="form-control input-sm" ng-required="true"  required="1" ng-model="combustiveis.valor">
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-hide="combustiveis.arquivo_xml" >
                        <div class="col-sm-4">
                            <div class="form-group linkBut">
                                <b><input type="checkbox" name="interno" id="interno" ng-model="interno" ng-true-value="'S'" ng-false-value="'N'"> Combust&iacute;vel Interno</b>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row text-left">
                        <div id="container_itens">
                            <div class="col-sm-12">
                                <button id="pickfiles_itens" type="button" class="btn btn-primary btn-sm">
                                    <span class="glyphicon glyphicon-file"></span>
                                    Anexar Cupom Fiscal
                                </button>
                                <br>
                                <small>
                                    (Tamanho max&iacute;mo de 10mb)
                                </small>
                                <input type="text" class="hide" name="anexo_cupom_fiscal" id="anexo_cupom_fiscal">
                                <br><br>
                                <div class="row">
                                    <ul id="upload_"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary" id="salvar_combustivel" name="salvar_combustivel">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
