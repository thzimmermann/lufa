<div class="modal fade" id="modalGraficoKmRodado" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Quilometragem de veículos (veículos x km)</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <canvas id="line"
                    class="chart chart-line"
                    chart-data="graficoKmRodado.data"
                    chart-labels="graficoKmRodado.label"
                    chart-options="options">
            </canvas>
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