<style>
    .form-group-error a.select2-choice {
        border:1px solid #FF0000;
    }
    .select2-container {
        height: 40px;
    }
</style>
<div class="modal fade" id="modalRespondeEmergencial" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title-aprovacao" id="myModalLabel">Aprova&ccedil;&atilde;o do Agendamento</h4>
            </div>
            <div class="modal-body">
                <form novalidate name="form_emergencial" id="form_emergencial">
                    <input type="hidden" name="i_alocacao" id="i_alocacao" ng-model="dados.i_alocacao">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="status" class="col-sm-3 control-label">Status: </label>
                                <div class="col-sm-6">
                                    <input ng-click="escondeEsconde('M')" type="radio" name="status" ng-model="dados.status_aprov" id="status1" value="A"> Aprovar &nbsp;
                                    <input ng-click="escondeEsconde('V')" type="radio" name="status" id="status2" ng-model="dados.status_aprov" value="R"> Reprovar
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-if="sumir == 'V'">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="motivo" class="col-sm-3 control-label">Motivo: </label>
                                <div class="col-sm-7">
                                    <textarea rows="3" class="form-control" id="motivo" name="motivo" ng-model="dados.motivo_cancel" placeholder="Motivo"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row" ng-if="sumir == 'M'">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="veiculo" class="col-sm-3 control-label">Ve&iacute;culo: </label>
                                <div class="col-sm-7">
                                    <select class='form-control' id="veiculosSelect2" name="veiculosSelect2" ng-model="veiculo" ng-options="listaVeiculo.nome group by listaVeiculo.categoria for listaVeiculo in listaVeiculos">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </form>
                <div class="modal-footer">
                    <div class="row">
                        <button type="button" class="btn btn-primary btn-sm" ng-click="salvarResposta(dados, veiculo.i_patrimonio)">Salvar</button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
