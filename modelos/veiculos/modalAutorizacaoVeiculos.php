<script type="text/javascript">
    function changeValueData(element) {
        angular.element(element).triggerHandler('input');
    }
</script>
<style>
    .form-group-error a.select2-choice {
        border:1px solid #FF0000;
    }
</style>
<div class="modal fade" id="modalAutorizacaoVeiculos" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registro para uso de ve&iacute;culos - SATC</h4>
            </div>
            <div class="modal-body">
                <form novalidate name="form_manutencoes" id="form_manutencoes">
                    <div class="alert alert-danger" ng-show="mensagem_erro">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{mensagem_erro}}
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="tipo">Condutor:</label>
                                <input disabled type="text" name="condutor" class="form-control input-sm" ng-model="autorizacao.condutor">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numero_carteira">N&ordm; CNH:</label>
                                <input disabled type="text" name="numero_carteira" id="numero_carteira" maxlength="15" class="form-control input-sm" ng-model="autorizacao.numero_carteira">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="dt_validade_cnh">Vencimento CNH:</label>
                                <input disabled type="text" name="dt_validade_cnh" id="dt_validade_cnh" class="form-control input-sm" ng-model="autorizacao.dt_validade_cnh">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="n_passageiros">Passageiros:</label>
                                <input disabled type="text" name="n_passageiros" id="n_passageiros" class="form-control input-sm" ng-model="autorizacao.n_passageiros">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="veiculo" class="control-label">Ve&iacute;culo:</label>
                                <input disabled type="text" name="veiculo" id="veiculo" class="form-control input-sm" ng-model="autorizacao.veiculo">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nome_unidade" class="control-label">Centro de custo:</label>
                                <input disabled type="text" name="nome_unidade" id="nome_unidade" class="form-control input-sm" ng-model="autorizacao.nome_unidade">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="destino">Destino:</label>
                                <input disabled type="text" name="destino" id="destino" class="form-control input-sm" ng-model="autorizacao.destino">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dt_alocacao_inicial">Data sa&iacute;da: </label>
                                <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                    <input type="text" name="dt_alocacao_inicial" ng-model="autorizacao.dt_alocacao_inicial" id="dt_alocacao_inicial"
                                    class="form-control input-sm mask_date form-control"
                                    onkeydown="changeValueData(this)" onkeyup="changeValueData(this)">
                                    <span class="input-group-addon icon_addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="hr_alocacao_inicial">Hora sa&iacute;da: </label>
                            <div class="input-group bootstrap-timepicker">
                                <input type="text" name="hr_alocacao_inicial" value="" id="hr_alocacao_inicial" maxlength="8"
                                class="form-control input-sm form-control"
                                data-inputmask="'mask': '99:99'" ng-model="autorizacao.hr_alocacao_inicial">
                                <span class="input-group-addon">
                                    <i class="icon-time"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dt_alocacao_final">Data chegada: </label>
                                <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                    <input type="text" name="dt_alocacao_final" ng-model="autorizacao.dt_alocacao_final" id="dt_alocacao_final"
                                    class="form-control input-sm mask_date form-control"
                                    onkeydown="changeValueData(this)" onkeyup="changeValueData(this)">
                                    <span class="input-group-addon icon_addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="hr_alocacao_final">Hora chegada:</label>
                            <div class="input-group bootstrap-timepicker">
                                <input type="text" name="hr_alocacao_final" value="" id="hr_alocacao_final" maxlength="8"
                                class="form-control input-sm form-control"
                                data-inputmask="'mask': '99:99'" ng-model="autorizacao.hr_alocacao_final">
                                <span class="input-group-addon">
                                    <i class="icon-time"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="km_inicial" class="control-label">KM sa&iacute;da:</label>
                                <input type="text" name="km_inicial" id="km_inicial" ui-number-mask="0" class="form-control input-sm" ng-model="autorizacao.km_inicial">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="km_final" class="control-label">KM chegada:</label>
                                <input type="text" name="km_final" id="km_final" ui-number-mask="0" class="form-control input-sm" ng-model="autorizacao.km_final">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="nivel_combustivel">N&iacute;vel de combustivel:</label>
                                <div id="g1" class="gauge"></div>
                                <input type="hidden" name="nivel_combustivel" id="nivel_combustivel" ng-model="autorizacao.nivel_combustivel">
                                <button type="button" id="gauge_less" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-minus"></span></button>
                                <button type="button" id="gauge_plus" class="btn btn-sm btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></button>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="problemas_veiculo">Problemas do ve&iacute;culo:</label>
                                <textarea name="problemas_veiculo" cols="40" rows="6" id="problemas_veiculo"
                                    maxlength="800" class="form-control input-sm  ng-pristine ng-valid ng-valid-maxlength ng-touched"
                                    ng-model="autorizacao.problemas">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary" id="salvar_manutencao" ng-click="salvarAutorizacao(autorizacao)">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
