<?php
    echo link_tag('assets_novo/css/select2.css');
    echo link_tag('assets_novo/css/ebro_select2.css');
    echo link_js('assets_novo/js/select2.min.js');
    echo link_js('assets/js/angular-sanitize.min.js');
    echo link_js('assets_novo/js/plupload.full.min.js');
    echo link_js('assets_novo/js/pluginAnexo.js');
    echo link_js('assets_novo/js/dirPagination.js');
    echo link_js('assets_novo/js/masks.js');
    echo link_js('assets_novo/js/Chart.min.js');
    echo link_js('assets_novo/js/angular-chart.min.js');
    echo link_ng('veiculos/controller/combustiveisController.js');
    echo link_js('assets_novo/js/angular-locale_pt-br.js');
?>
<style type="text/css">
    .datepicker{z-index:9999 !important}
    .font-bold {font-weight: bold}
    .mGR-15 {margin-right: 15px !important;}
    .mGT-20 {margin-top: 20px !important;}
    .mGT-3 {margin-top: 3px !important;}
    .display-inline {display: inline-flex !important;}
</style>
<script type="text/javascript">
    function changeValueData(element) {
        angular.element(element).triggerHandler('input');
    }
    $(document).ready(function() {
        var BASE_URL_UPLOAD = "<?php echo base_url().index_page(); ?>";
        $.plupload_anexo({
            'nome_plupload': 'upload_doc',
            'uploader': 'upload_',
            'url': BASE_URL_UPLOAD+'/VeiculosCombustivel/uploadCupomFiscal',
            'removefiles': 'input-file-itens',
            'browser': 'pickfiles_itens',
            'container': 'container_itens',
            'btn_salva': 'salvar_combustivel',
            'tam': '10mb',
            'adicionar': 'anexo_cupom_fiscal',
            'salvar': angular.element("#principal").scope().submitcombustiveis,
            'id_li': 'div_arquivo_itens_',
            'li_class': 'div_arquivo_itens input-group form-group col-sm-12',
            'input_class': 'form-control input-sm',
            'input_type': 'text',
            'id_span': 'removerArquivoItens',
            'span_class': 'input-group-addon icon_addon cursor_pointer',
            'condicao': 1
        });

        var uploader = new plupload.Uploader({
            runtimes : 'html5, html4',
            browse_button : 'pickfiles', // you can pass an id...
            container: document.getElementById('container'), // ... or DOM Element itself
            url : BASE_URL_UPLOAD+'/VeiculosCombustivel/uploadXml',
            multi_selection : false,
            filters : {
                max_file_size : '10mb',
                mime_types: []
            },
            init: {
                PostInit: function() {
                    document.getElementById('filelist').innerHTML = '';
                },
                FilesAdded: function(up, files) {
                    plupload.each(files, function(file) {
                        document.getElementById('filelist').innerHTML += '<div id="anexo_xml">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    });
                    uploader.start();
                },
                UploadProgress: function(up, file) {
                    document.getElementById('anexo_xml').getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                },
                FileUploaded: function(up, file, result) {
                    angular.element("#principal").scope().importarXml(result.response);
                },
                Error: function(up, err) {
                    alert(err.message);
                }
            }
        });

        uploader.init();
    });
</script>
<div ng-app="appVeiculosCombustivel" ng-controller="combustiveisController" id="principal">
    <div>
        <div ng-show="detalhes==0">
            <div class="row">
                <?php $this->load->view("interface_titulo", $titulo_interface)?>
            </div>
            <div class="row">
                <div class="col-sm-4 mGT-20">
                    <div class="form-group">
                        <button type="button" class="btn btn-sm btn-success mGT-3" ng-click="cadCombustivel()">
                            <span class="glyphicon glyphicon-plus"></span> Novo Combust&iacute;vel
                        </button>
                        <button type="button" class="btn btn-sm btn-primary mGT-3" ng-click="relatorioCombustivel()">
                            <span class="glyphicon glyphicon-print"></span> Relat&oacute;rio Geral
                        </button>
                        <button type="button" class="btn btn-sm btn-default mGT-3" ng-click="graficoConsumo()" ng-disabled="listarCombustiveis.length==0">
                            <span class="glyphicon glyphicon-stats"></span> Gr&aacute;ficos
                        </button>
                    </div>
                </div>
                <div class="col-sm-5 display-inline">
                    <div class="form-group mGR-15">
                        <label for="dt_inicial">Data Inicial:</label>
                        <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                            <input type="text" name="dt_inicial" ng-model="dt_inicial" id="dt_inicial"
                            class="form-control input-sm mask_date form-control"
                            onkeydown="changeValueData(this)" onkeyup="changeValueData(this)">
                            <span class="input-group-addon icon_addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group mGR-15">
                        <label for="dt_final">Data Final:</label>
                        <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                            <input type="text" name="dt_final" ng-model="dt_final" id="dt_final"
                            class="form-control input-sm mask_date form-control"
                            onkeydown="changeValueData(this)" onkeyup="changeValueData(this)">
                            <span class="input-group-addon icon_addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group mGR-15">
                        <label for="filtrar">&nbsp;</label>
                        <button type="button" class="btn btn-warning btn-sm" ng-click="filtrarData(dt_inicial,dt_final)">
                            <span class="glyphicon glyphicon-search"></span> Filtrar
                        </button>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="pesquisa">&nbsp;</label>
                        <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtro">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="tabbable tabs-left tabbable-bordered">
                        <table id="tabela_agendamentos" class="table table-hover table-striped table_satc table-bordered">
                            <thead>
                              <tr>
                                  <th>Setor</th>
                                  <th class="text-center" width="10%">Detalhes</th>
                                  <th class="text-center" width="10%">Litros Gasolina</th>
                                  <th class="text-center" width="10%">Valor Gasolina</th>
                                  <th class="text-center" width="10%">Litros Diesel</th>
                                  <th class="text-center" width="10%">Valor Diesel</th>
                                  <th class="text-center" width="10%">Litros Etanol</th>
                                  <th class="text-center" width="10%">Valor Etanol</th>
                                  <th class="text-center" width="10%">Litros Interno</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr ng-hide="listarCombustiveis.length == 0" ng-repeat="listarCombustivel in listarCombustiveis|filter:filtro">
                                    <td>{{listarCombustivel.i_unidade_formatada}} - {{listarCombustivel.nome_unidade}}</td>
                                    <td class="text-center">
                                        <button class="btn btn-default btn-sm btn-xs" ng-click="relatorioUnidade(listarCombustivel)">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </td>
                                    <td class="text-center">{{listarCombustivel.litros_g | currency : ""}}</td>
                                    <td class="text-center">{{listarCombustivel.valor_g | currency : "R$ "}}</td>
                                    <td class="text-center">{{listarCombustivel.litros_d | currency : ""}}</td>
                                    <td class="text-center">{{listarCombustivel.valor_d | currency : "R$ "}}</td>
                                    <td class="text-center">{{listarCombustivel.litros_a | currency : ""}}</td>
                                    <td class="text-center">{{listarCombustivel.valor_a | currency : "R$ "}}</td>
                                    <td class="text-center">{{listarCombustivel.litros_i | currency : ""}}</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr ng-show="listarCombustiveis.length == 0">
                                    <td class="text-center" colspan="9"><strong>N&atilde;o a registro de combust&iacute;veis</strong></td>
                                </tr>
                            </tbody>
                            <tfoot ng-hide="listarCombustiveis.length == 0">
                                <tr>
                                    <td colspan="2"><b>Total de gastos: </b></td>
                                    <td class="text-center"><b>{{totalCombustivel.litros_g | currency : ""}}</b></td>
                                    <td class="text-center"><b>{{totalCombustivel.valor_g | currency : "R$ "}}</b></td>
                                    <td class="text-center"><b>{{totalCombustivel.litros_d | currency : ""}}</b></td>
                                    <td class="text-center"><b>{{totalCombustivel.valor_d | currency : "R$ "}}</b></td>
                                    <td class="text-center"><b>{{totalCombustivel.litros_a | currency : ""}}</b></td>
                                    <td class="text-center"><b>{{totalCombustivel.valor_a | currency : "R$ "}}</b></td>
                                    <td class="text-center"><b>{{totalCombustivel.litros_i | currency : ""}}</b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div ng-show="detalhes==1">
            <div class="page-header-topo">
                <h3 id="grid">
                    Combust&iacute;veis da Unidade: {{i_unidade_formatada}} - {{unidade}}
                </h3>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="pull-left">
                        <div class="form-group">
                            <br>
                            <button type="button" class="btn btn-warning btn-sm " ng-click="voltar()">
                                <span class="glyphicon glyphicon-arrow-left"></span> Voltar
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" ng-click="relatorioCombustivelUnidade(i_unidade)">
                                <span class="glyphicon glyphicon-print"></span> Relat&oacute;rio
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <div class="col-sm-5">
                            <label for="dt_inicial">Data Inicial:</label>
                            <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                <input type="text" name="dt_inicial" ng-model="dt_inicial" id="dt_inicial"
                                class="form-control input-sm mask_date form-control"
                                onkeydown="changeValueData(this)" onkeyup="changeValueData(this)">
                                <span class="input-group-addon icon_addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <label for="dt_final">Data Final:</label>
                            <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                <input type="text" name="dt_final" ng-model="dt_final" id="dt_final"
                                class="form-control input-sm mask_date form-control"
                                onkeydown="changeValueData(this)" onkeyup="changeValueData(this)">
                                <span class="input-group-addon icon_addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label for="filtrar">&nbsp;</label>
                            <button type="button" class="btn btn-warning btn-sm" ng-click="getCombustiveisUnidade(i_unidade)">
                                <span class="glyphicon glyphicon-search"></span> Filtrar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="pull-right">
                        <form class="form-inline">
                            <div class="form-group">
                                <label for="pesquisa">&nbsp;</label>
                                <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtroCombU">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="tabbable tabs-left tabbable-bordered">
                        <table id="tabela_agendamentos" class="table table-hover table-striped table_satc table-bordered">
                            <thead>
                              <tr>
                                  <th>Ve&iacute;culo</th>
                                  <th class="text-center" width="10%">Combust&iacute;vel</th>
                                  <th class="text-center" width="10%">Litros</th>
                                  <th class="text-center" width="10%">Valor</th>
                                  <th class="text-center" width="20%">Data/Hora Abastecimento</th>
                                  <th class="text-center" width="5%">Interno</th>
                                  <th class="text-center" width="10%">Cupom Fiscal</th>
                                  <th class="text-center" width="5%">Xml</th>
                                  <th class="text-center" width="5%">Excluir</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr ng-hide="combustivelUnidade.length == 0" ng-repeat="listaCombustivel in combustivelUnidade|filter:filtroCombU">
                                    <td>{{listaCombustivel.veiculo}}</td>
                                    <td class="text-center">{{listaCombustivel.combustivel_view}}</td>
                                    <td class="text-center">{{listaCombustivel.litros | currency : ""}}</td>
                                    <td class="text-center">{{listaCombustivel.valor | currency : "R$ "}}</td>
                                    <td class="text-center">{{listaCombustivel.dt_abastecimento_format}}</td>
                                    <td class="text-center">{{listaCombustivel.interno_view}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-default btn-sm" ng-class="{disabled: listaCombustivel.arquivo == ' '}" ng-click="getDownload(listaCombustivel.arquivo)">
                                            <span class="glyphicon glyphicon-file"></span>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-default btn-sm" ng-class="{disabled: listaCombustivel.arquivo_xml == ''}" ng-click="getDownload(listaCombustivel.arquivo_xml,'Xml')">
                                            <span class="glyphicon glyphicon-file"></span>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-default btn-sm" ng-click="excluirCombustivel(listaCombustivel.i_combustivel, listaCombustivel.i_unidade)">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr ng-show="combustivelUnidade.length == 0">
                                    <td class="text-center" colspan="7"><strong>N&atilde;o a registro de combust&iacute;veis desta unidade</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        $this->load->view('veiculos/modalCadCombustivel');
        $this->load->view('veiculos/modalGraficoConsumo');
    ?>
</div>