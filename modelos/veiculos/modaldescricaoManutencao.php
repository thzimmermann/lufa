<style>
    .form-group-error a.select2-choice {
        border:1px solid #FF0000;
    }
</style>
<div class="modal fade" id="modaldescricaoManutencao" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Placa: {{placa}}</h4>
                <h4 class="modal-title">Modelo: {{modelo}}</h4>
                <h5 class="modal-title">Manuten&ccedil;&atilde;o do dia: {{exibir.dt_manutencao}}</h5>
                <h5 class="modal-title">Tipo de Manuten&ccedil;&atilde;o: {{exibir.tipo_view}}</h5>
                <h5 class="modal-title">Km: {{exibir.kilometragem}}</h5>
                <h5 class="modal-title">Valor da Manuten&ccedil;&atilde;o: {{exibir.custo | currency : "R$"}}</h5>
                <h4 ng-show="exibir.tipo == 'P'">Dados manutenção para prox. preventiva:</h4>
                <h5 class="modal-title" ng-show="exibir.tipo == 'P'">Data(Max) prox. manutenção: {{exibir.dt_prox_manutencao}}</h5>
                <h5 class="modal-title" ng-show="exibir.tipo == 'P'">Km(Max) prox. manutenção: {{exibir.km_prox_manutencao}}</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="descricao" class="control-label">Descrição da Manuten&ccedil;&atilde;o:</label>
                            <textarea name="descricao" cols="40" rows="3" id="descricao"
                                maxlength="800" class="form-control input-sm  ng-pristine ng-valid ng-valid-maxlength ng-touched"
                                ng-model="exibir.descricao" disabled>
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
