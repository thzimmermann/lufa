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
<div class="modal fade" id="modalCadManutencao" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Manutenções</h4>
      </div>
      <div class="modal-body">
        <form novalidate name="form_manutencoes" id="form_manutencoes">
          <div class="alert alert-danger" ng-show="mensagem_erro">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{mensagem_erro}}
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="tipo">Tipo de Manuten&ccedil;&atilde;o:</label>
                <select name="tipo" id="tipo"
                  class="form-control input-sm form-control
                  ng-pristine ng-invalid ng-invalid-required ng-touched"
                  ng-model="manutencoes.tipo"
                  required>
                  <option value="">Selecione uma opção</option>
                  <option value="P">Preventiva</option>
                  <option value="C">Corretiva</option>
                  <option value="S">Sinistro</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="dt_manutencao">Data:</label>
                <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                  <input type="text" name="dt_manutencao" ng-model="manutencoes.dt_manutencao"
                  id="dt_manutencao" class="form-control input-sm mask_date form-control"
                  onkeydown="changeValueData(this)" onkeyup="changeValueData(this)" ng-required="true">
                  <span class="input-group-addon icon_addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="kilometragem">Km:</label>
                <input type="text" name="kilometragem" somentenumeros id="kilometragem" maxlength="15" class="form-control input-sm" ng-required="true" ng-model="manutencoes.kilometragem">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="custo">Custo:</label>
                <input type="text" ui-money-mask="2" name="custo" id="custo"
                class="form-control input-sm" ng-model="manutencoes.custo">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="descricao" class="control-label">Descrição da Manuten&ccedil;&atilde;o:</label>
                <textarea name="descricao" cols="40" rows="3" id="descricao"
                maxlength="800" class="form-control input-sm"
                ng-required="true" ng-model="manutencoes.descricao">
                </textarea>
              </div>
            </div>
          </div>
          <div ng-show="manutencoes.tipo == 'P'">
            <h4>Proxima manutenção</h4>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="dt_revisao" class="control-label">Data p/ prox. revisão:</label>
                  <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                    <input type="text" name="dt_revisao" ng-model="manutencoes.dt_prox_manutencao"
                    id="dt_revisao" class="form-control input-sm mask_date form-control"
                    onkeydown="changeValueData(this)" onkeyup="changeValueData(this)" ng-required="manutencoes.tipo == 'P' && !manutencoes.km_prox_manutencao">
                    <span class="input-group-addon icon_addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="km_maxima" class="control-label">KM m&aacute;xima:</label>
                  <input type="text" somentenumeros name="km_maxima" id="km_maxima" class="form-control input-sm" ng-model="manutencoes.km_prox_manutencao" ng-required="manutencoes.tipo == 'P' && !manutencoes.dt_prox_manutencao">
                </div>
              </div>
            </div>
          </div>
          <div class="row text-left">
            <div id="container_itens">
              <div class="col-sm-12">
                <button id="pickfiles_itens_manu" type="button" class="btn btn-primary btn-sm">
                  <span class="glyphicon glyphicon-file"></span>
                  Anexar Arquivos
                </button>
                <br>
                <small>
                (Tamanho maxímo de 30mb)
                </small>
                <input type="text" class="hide" name="anexo_manutencao" id="anexo_manutencao">
                <br><br>
                <div class="row">
                <ul id="upload_manu_"></ul>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <!-- form_manutencoes.$invalid -->
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary" id="salvar_manutencao" ng-disabled="form_manutencoes.$invalid">Salvar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>