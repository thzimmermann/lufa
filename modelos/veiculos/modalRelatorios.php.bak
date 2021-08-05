<script type="text/javascript">
  function changeValueData(element) {
    angular.element(element).triggerHandler('input');
  }

  function changeChackbox(idElement) {
    elementGeral = document.getElementById('geral');
    elementPreventiva = document.getElementById('preventiva');
    elementCorretiva = document.getElementById('corretiva');

    if(idElement == 'geral') {
      elementPreventiva.checked = false;
      elementCorretiva.checked = false;

      angular.element(elementPreventiva).scope().formRelatorio.manPreventiva = false;
      angular.element(elementCorretiva).scope().formRelatorio.manCorretiva = false;
      angular.element(elementGeral).scope().formRelatorio.geral = true;

    }else if(idElement == 'corretiva' || idElement == 'preventiva'){
      if (idElement == 'corretiva') {
        angular.element(elementCorretiva).scope().formRelatorio.manCorretiva = true;
        angular.element(elementPreventiva).scope().formRelatorio.manPreventiva = false;
      }else if(idElement == 'preventiva'){
        angular.element(elementPreventiva).scope().formRelatorio.manPreventiva = true;
        angular.element(elementCorretiva).scope().formRelatorio.manCorretiva = false;
      }
      elementGeral.checked = false;
      angular.element(elementGeral).scope().formRelatorio.geral = false;
    }
  }
</script>
<div class="modal fade" id="modalRelatorios" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Relatórios</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 form-group">
            <label class="control-label">Relatório:</label><br>
            <label class="radio-inline"><input type="radio" name="tipoRelatorio" ng-click="formRelatorio.opcaoRelatorio='K'">Quilômetros rodados</label>
            <label class="radio-inline"><input type="radio" name="tipoRelatorio" ng-click="formRelatorio.opcaoRelatorio='M'">Manutenção</label>
            <label class="radio-inline"><input type="radio" name="tipoRelatorio" ng-click="formRelatorio.opcaoRelatorio='C'">Combustivel</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 form-group">
            <label for="dt_inicial">Data Inicial:</label>
            <div data-date-autoclose="true"
                 data-date-format="dd/mm/yyyy"
                 class="input-group date ebro_datepicker">
              <input id="dt_inicial"
                     name="dt_inicial"
                     type="text"
                     class="form-control input-sm mask_date form-control"
                     ng-model="formRelatorio.dt_inicial"
                     onkeydown="changeValueData(this)"
                     onkeyup="changeValueData(this)">
              <span class="input-group-addon icon_addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
          <div class="col-sm-6 form-group">
            <label for="dt_final">Data Final:</label>
            <div data-date-autoclose="true"
                 data-date-format="dd/mm/yyyy"
                 class="input-group date ebro_datepicker">
              <input id="dt_final"
                     name="dt_final"
                     type="text"
                     class="form-control input-sm mask_date form-control"
                     ng-model="formRelatorio.dt_final"
                     onkeydown="changeValueData(this)"
                     onkeyup="changeValueData(this)">
              <span class="input-group-addon icon_addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
        <div ng-switch="formRelatorio.opcaoRelatorio">
          <div ng-switch-when="K">
            <div class="row">
              <div class="col-sm-12 form-group">
                <label class="control-label">Filtrar por:</label><br>
                <label class="radio-inline"><input type="radio" name="opcaoFiltroRelatorio" ng-click="formRelatorio.opcaoFiltroRelatorio='V'">Ve&iacute;culo</label>
                <label class="radio-inline"><input type="radio" name="opcaoFiltroRelatorio" ng-click="formRelatorio.opcaoFiltroRelatorio='U'">Unidade</label>
              </div>
            </div>
            <div ng-switch="formRelatorio.opcaoFiltroRelatorio">
              <div ng-switch-when="U">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="i_unidade">Unidade:</label>
                    <input select2
                           type="hidden"
                           id="i_unidade"
                           name="i_unidade"
                           ng-model="formRelatorio.i_unidade"
                           urlajax="<?php echo base_url();?>index.php/veiculos/getUnidadesAjax"
                           minl="3">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group text-center">
                    <button class="btn btn-sm btn-primary" ng-click="relatorioKmRodado(formRelatorio.opcaoFiltroRelatorio)">
                      <span class="glyphicon glyphicon-print"></span> Relatório
                    </button>
                    <button class="btn btn-sm btn-default" ng-click="geraGraficoKmRodado()">
                      <span class="glyphicon glyphicon-stats"></span> Gráfico
                    </button>
                  </div>
                </div>
              </div>
              <div ng-switch-when="V">
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="i_veiculo">Ve&iacute;culo:</label>
                    <input select2
                           type="hidden"
                           id="i_veiculo"
                           name="i_veiculo"
                           ng-model="formRelatorio.i_veiculo"
                           urlajax="<?php echo base_url();?>index.php/veiculos/getVeiculosAjax"
                           minl="0">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3 form-group text-center">
                    <button class="btn btn-sm btn-primary" ng-click="relatorioKmRodado(formRelatorio.opcaoFiltroRelatorio)">
                      <span class="glyphicon glyphicon-print"></span> Relatório
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div ng-switch-when="M">
            <div class="row">
              <div class="col-sm-12 form-group">
                <label class="control-label">Tipo da manutenção:</label><br>
                <label class="checkbox-inline"><input type="checkbox" name="tipoManutencaoPre" id="preventiva" onclick="changeChackbox(this.id)" ng-model="formRelatorio.manPreventiva">Manutenção preventiva</label>
                <label class="checkbox-inline"><input type="checkbox" name="tipoManutencaoCor" id="corretiva" onclick="changeChackbox(this.id)" ng-model="formRelatorio.manCorretiva">Manutenção corretiva</label>
                <label class="checkbox-inline"><input type="checkbox" name="tipoGeral" id="geral" onclick="changeChackbox(this.id)" ng-model="formRelatorio.geral">Geral</label>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 form-group">
                <label for="i_veiculo">Ve&iacute;culo:</label>
                <input select2
                       type="hidden"
                       id="i_veiculo"
                       name="i_veiculo"
                       ng-model="formRelatorio.i_veiculo"
                       urlajax="<?php echo base_url();?>index.php/veiculos/getVeiculosAjax"
                       minl="0">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-sm-offset-3 form-group text-center">
                <button class="btn btn-sm btn-primary" ng-click="relatorioManutencao()" ng-disabled="!formRelatorio.manPreventiva && !formRelatorio.manCorretiva && !formRelatorio.geral">
                  <span class="glyphicon glyphicon-print"></span> Relatório
                </button>
              </div>
            </div>
          </div>
          <div ng-switch-when="C">
            <div class="row">
              <div class="col-sm-6 col-sm-offset-3 form-group text-center">
                <button class="btn btn-sm btn-primary" ng-click="relatorioCombustivel()">
                  <span class="glyphicon glyphicon-print"></span> Relatório Consumo
                </button>
              </div>
            </div>
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