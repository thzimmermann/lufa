<div class="modal fade" id="modalGraficoConsumo" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Consumo de combustível(L)</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 text-center">
              <div class="btn-group">
                <button class="btn btn-default"
                        ng-disabled="totalCombustivel['litros_g']==0"
                        ng-click="opcaoGrafico='G'">Gasolina
                </button>
                <button class="btn btn-default"
                        ng-disabled="totalCombustivel['litros_d']==0"
                        ng-click="opcaoGrafico='D'">Diesel
                </button>
                <button class="btn btn-default"
                        ng-disabled="totalCombustivel['litros_a']==0"
                        ng-click="opcaoGrafico='E'">Etanol
                </button>
              </div>
          </div>
        </div>
          <div ng-switch="opcaoGrafico">
            <div class="row" ng-switch-when="G">
              <div class="col-sm-8 col-sm-offset-2 text-center">
                <h4>Gasolina</h4>
                <canvas class="chart chart-pie"
                        chart-data="dataCombustivelArea.gasolina"
                        chart-labels="labelCombustivelArea"
                        chart-options="options">
                </canvas>
                <strong>Total de gasolina: {{totalCombustivel['litros_g']}} L</strong>
              </div>
            </div>
            <div class="row" ng-switch-when="D">
              <div class="col-sm-8 col-sm-offset-2 text-center">
                <h4>Diesel</h4>
                <canvas class="chart chart-pie"
                        chart-data="dataCombustivelArea.diesel"
                        chart-labels="labelCombustivelArea"
                        chart-options="options">
                </canvas>
                <strong>Total de diesel: {{totalCombustivel['litros_d']}} L</strong>
              </div>
            </div>
            <div class="row" ng-switch-when="E">
              <div class="col-sm-8 col-sm-offset-2 text-center">
                <h4>Etanol</h4>
                <canvas class="chart chart-pie"
                        chart-data="dataCombustivelArea.etanol"
                        chart-labels="labelCombustivelArea"
                        chart-options="options">
                </canvas>
                <strong>Total de etanol: {{totalCombustivel['litros_a']}} L</strong>
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
