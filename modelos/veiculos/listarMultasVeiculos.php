<?php
    echo link_tag('assets_novo/css/select2.css');
    echo link_tag('assets_novo/css/ebro_select2.css');
    echo link_js('assets_novo/js/select2.min.js');
    echo link_js('assets/js/angular-sanitize.min.js');
    echo link_js('assets_novo/js/plupload.full.min.js');
    echo link_js('assets_novo/js/pluginAnexo.js');
    echo link_js('assets_novo/js/dirPagination.js');
    echo link_ng('veiculos/controller/multasController.js');
?>
<style type="text/css">
    .datepicker{z-index:9999 !important}
    .font-bold {font-weight: bold}
</style>
<div ng-app="appVeiculosMultas" ng-controller="multasVeiculosController" id="principal">
    <div>
        <div class="row">
            <?php $this->load->view("interface_titulo", $titulo_interface)?>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="pull-left">
                    <div class="form-group ">
                        <button type="button" class="btn btn-success btn-sm" ng-click="cadMultas()">
                            <span class="glyphicon glyphicon-plus"></span> Nova Multa
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtro">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="tabbable tabs-left tabbable-bordered">
                <table id="tabela_agendamentos" class="table table-hover table-striped table_satc table-bordered">
                    <thead>
                      <tr>
                          <th class="text-center" width="10%">C&oacute;d. Infra&ccedil;&atilde;o</th>
                          <th class="text-center" width="7%">Gravidade</th>
                          <th class="text-center" width="7%">Pontos</th>
                          <th>Ve&iacute;culo</th>
                          <th>Condutor</th>
                          <th class="text-center">Cidade</th>
                          <th class="text-center" width="7%">Data</th>
                          <th class="text-center" width="7%">Hora</th>
                          <th class="text-center" width="7%">Status</th>
                          <th class="text-center">Anexo</th>
                      </tr>
                    </thead>
                    {{listarMultas}}
                    <tbody>
                        <tr ng-hide="listarMultas.length == 0" ng-repeat="listarMulta in listarMultas|filter:filtro">
                            <td class="text-center">{{listarMulta.cod_infracao}}</td>
                            <td class="text-center">{{listarMulta.gravidade}}</td>
                            <td class="text-center">{{listarMulta.pontos}}</td>
                            <td>{{listarMulta.veiculo}}</td>
                            <td>{{listarMulta.nome_usuario}}</td>
                            <td class="text-center">{{listarMulta.cidade}}</td>
                            <td class="text-center">{{listarMulta.dt_multa}}</td>
                            <td class="text-center">{{listarMulta.hr_multa}}</td>
                            <td class="text-center">{{listarMulta.status}}</td>
                            <td class="text-center">
                                <button class="btn btn-default btn-sm btn-xs"
                                    ng-class="{disabled:  listarMulta.arquivo == ' '}"
                                    ng-click="getDownload(listarMulta.arquivo)">
                                    <span class="glyphicon glyphicon-file"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr ng-show="listarMultas.length == 0">
                            <td class="text-center" colspan="10"><strong>N&atilde;o a multas cadastradas</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    $this->load->view('veiculos/modalCadMultas');
    ?>
</div>