<?php
    echo link_tag('assets_novo/css/select2.css');
    echo link_tag('assets_novo/css/ebro_select2.css');
    echo link_js('assets_novo/js/select2.min.js');
    echo link_js('assets/js/angular-sanitize.min.js');
    echo link_js('assets_novo/js/plupload.full.min.js');
    echo link_js('assets_novo/js/pluginAnexo.js');
    echo link_js('assets_novo/js/dirPagination.js');
    echo link_js('assets_novo/js/angular-filter.min.js');
    echo link_js('assets_novo/js/masks.js');
    echo link_ng('veiculos/controller/gerenciarVeiculosController.js');
    echo link_js('assets_novo/js/angular-locale_pt-br.js');
?>
<style type="text/css">
    .datepicker{z-index:9999 !important}
    .font-bold {font-weight: bold}
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
            'url': BASE_URL_UPLOAD+'/veiculos/uploadDocumentos',
            'removefiles': 'input-file-itens',
            'browser': 'pickfiles_itens',
            'container': 'container_itens',
            'btn_salva': 'salvar',
            'tam': '10mb',
            'adicionar': 'anexo_documento',
            'salvar': angular.element("#principal").scope().setSalvarDocumentos,
            'id_li': 'div_arquivo_itens_',
            'li_class': 'div_arquivo_itens input-group form-group col-sm-12',
            'input_class': 'form-control input-sm',
            'input_type': 'text',
            'id_span': 'removerArquivoItens',
            'span_class': 'input-group-addon icon_addon cursor_pointer',
            'condicao': 1
        });

        $.plupload_anexo({
            'nome_plupload': 'upload_mult',
            'uploader': 'upload_multa',
            'url': BASE_URL_UPLOAD+'/veiculos/uploadMultas',
            'removefiles': 'input-file-itens',
            'browser': 'pickfiles_itens_mult',
            'container': 'container_itens',
            'btn_salva': 'salvar_multa',
            'tam': '10mb',
            'adicionar': 'anexo_multa',
            'salvar': angular.element("#principal").scope().setSalvarMultas,
            'id_li': 'div_arquivo_itens_',
            'li_class': 'div_arquivo_itens input-group form-group col-sm-12',
            'input_class': 'form-control input-sm',
            'input_type': 'text',
            'id_span': 'removerArquivoItens',
            'span_class': 'input-group-addon icon_addon cursor_pointer',
            'condicao': 1
        });

        $.plupload_anexo({
            'nome_plupload': 'upload_manu',
            'uploader': 'upload_manu_',
            'url': BASE_URL_UPLOAD+'/veiculos/uploadManutencoes',
            'removefiles': 'input-file-itens',
            'browser': 'pickfiles_itens_manu',
            'container': 'container_itens',
            'btn_salva': 'salvar_manutencao',
            'tam': '30mb',
            'adicionar': 'anexo_manutencao',
            'salvar': angular.element("#principal").scope().submitManutencao,
            'id_li': 'div_arquivo_itens_',
            'li_class': 'div_arquivo_itens input-group form-group col-sm-12',
            'input_class': 'form-control input-sm',
            'input_type': 'text',
            'id_span': 'removerArquivoItens',
            'span_class': 'input-group-addon icon_addon cursor_pointer'
        });
    });
</script>
<div ng-app="appVeiculos" ng-controller="gerenciamentoVeiculosController" id="principal">
    <!-- VEICULOS -->
    <div ng-if="detalhes == '0'">
        <div class="row">
            <?php $this->load->view("interface_titulo", $titulo_interface)?>
        </div>
        <div class="row">
            <div class="col-sm-8"></div>
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
                <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Patrim&ocirc;nio</th>
                            <th class="text-center">Placa</th>
                            <th class="text-center">Ve&iacute;culo</th>
                            <th class="text-center" width="10%">Manuten&ccedil;&atilde;o Preventiva</th>
                            <th class="text-center" width="8%">Manuten&ccedil;&otilde;es</th>
                            <th class="text-center" width="10%">Data de Validade</th>
                            <th class="text-center" width="8%">Documentos</th>
                            <th class="text-center" width="10%">Multas Pendentes</th>
                            <th class="text-center" width="8%">Combust&iacute;vel</th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="lista in listaVeiculos|filter:filtro| groupBy:'i_grupo'">
                        <tr>
                            <td colspan="9"><b>{{lista[0].i_grupo}}<b></td>
                        </tr>
                        <tr ng-repeat="listaVeiculo in lista">
                            <td class="text-center">{{listaVeiculo.i_patrimonio}}</td>
                            <td class="text-center">{{listaVeiculo.placa}}</td>
                            <td>{{listaVeiculo.marca_modelo}}</td>
                            <td  class="text-center" ng-class="{'danger font-bold':  listaVeiculo.proximamanutencao > -15 && listaVeiculo.total_manutencoes > 0 && listaVeiculo.dt_manutencao_prev}">{{listaVeiculo.dt_manutencao_prev}}</td>
                            <td class="text-center">
                                ({{listaVeiculo.total_manutencoes}})
                                <button class="btn btn-default btn-sm btn-xs" ng-click="listarManutencoes(listaVeiculo)">
                                    <span class="glyphicon glyphicon-wrench"></span>
                                </button>
                            </td>
                            <td  class="text-center" ng-class="{'danger font-bold':  listaVeiculo.diferenca > -30 && listaVeiculo.total_documentos > 0}">{{listaVeiculo.dt_vencimento}}</td>
                            <td class="text-center">
                                ({{listaVeiculo.total_documentos}})
                                <button class="btn btn-default btn-sm btn-xs" ng-click="listarDocumentos(listaVeiculo)">
                                    <span class="glyphicon glyphicon-list"></span>
                                </button>
                            </td>
                            <td class="text-center"  ng-class="{'danger font-bold':  listaVeiculo.total_multas > 0}">
                                ({{listaVeiculo.total_multas}})
                                <button class="btn btn-default btn-sm btn-xs" ng-click="listarMultas(listaVeiculo)">
                                    <span class="glyphicon glyphicon-usd"></span>
                                </button>
                            </td>
                            <td class="text-center">
                                ({{listaVeiculo.total_combustiveis}})
                                <button class="btn btn-default btn-sm btn-xs" ng-click="listarCombustiveis(listaVeiculo)">
                                    <span class="glyphicon glyphicon-fire"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- FIM VEICULOS -->
    <!-- DOCUMENTOS -->
    <div ng-if="detalhes == '1'">
        <div class="page-header-topo">
            <h3 id="grid">
                Lista de Documentos
                <h4>&nbsp;&nbsp;Placa: {{placa}}</h4>
                <h4>&nbsp;&nbsp;Modelo: {{modelo}}</h4>
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <button type="button" class="btn btn-warning btn-sm" ng-click="voltar()">
                    Voltar
                </button>
                <button type="button" class="btn btn-success btn-sm" ng-click="cadDocumentos(i_patrimonio)">
                    <span class="glyphicon glyphicon-plus"></span> Novo Documento
                </button>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtroDoc" ng-model-options="{debounce:500}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="tabbable tabs-left tabbable-bordered">
                <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" width="15%">Data de Validade</th>
                            <th class="text-center">Descrição</th>
                            <th class="text-center" width="10%">Arquivo</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaDocumentos.length != 0">
                        <tr ng-repeat="listaDocumento in listaDocumentos|filter:filtroDoc">
                            <td class="text-center">{{listaDocumento.dt_vencimento}}</td>
                            <td>{{listaDocumento.descricao}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm" ng-click="getDownload(listaDocumento.arquivo)">
                                    <span class="glyphicon glyphicon-file"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-if="listaDocumentos.length == 0">
                        <tr>
                            <td class="text-center" colspan="3">Não a Documentos cadastrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- FIM DOCUMENTOS -->
    <!-- MULTAS -->
    <div ng-if="detalhes == '2'">
        <div class="page-header-topo">
            <h3 id="grid">
                Lista de Multas Pedentes
                <h4>&nbsp;&nbsp;Placa: {{placa}}</h4>
                <h4>&nbsp;&nbsp;Modelo: {{modelo}}</h4>
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <button type="button" class="btn btn-warning btn-sm" ng-click="voltar()">
                    Voltar
                </button>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtroMult" ng-model-options="{debounce:500}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="tabbable tabs-left tabbable-bordered">
                <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Data</th>
                            <th class="text-center">Hora</th>
                            <th class="text-center">C&oacute;d. Infra&ccedil;&atilde;o</th>
                            <th class="text-center">Gravidade</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="10%">Editar</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaMultas.length != 0">
                        <tr ng-repeat="listaMulta in listaMultas|filter:filtroMult">
                            <td class="text-center">{{listaMulta.dt_multa}}</td>
                            <td class="text-center">{{listaMulta.hr_multa}}</td>
                            <td class="text-center">{{listaMulta.cod_infracao}}</td>
                            <td class="text-center">{{listaMulta.gravidade}}</td>
                            <td class="text-center">{{listaMulta.status_view}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm" ng-click="editarMulta(listaMulta)">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-if="listaMultas.length == 0">
                        <tr>
                            <td class="text-center" colspan="6">Não a multas pendentes</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- FIM MULTAS -->
    <!-- MANUTENÇOES -->
    <div ng-if="detalhes == '3'">
        <div class="page-header-topo">
            <h3 id="grid">
                Lista de Manutenções
                <h4>&nbsp;&nbsp;Placa: {{placa}}</h4>
                <h4>&nbsp;&nbsp;Modelo: {{modelo}}</h4>
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <button type="button" class="btn btn-warning btn-sm" ng-click="voltar()">
                    Voltar
                </button>
                <button type="button" class="btn btn-success btn-sm" ng-click="cadManutencao(i_patrimonio)">
                    <span class="glyphicon glyphicon-plus"></span> Nova Manuten&ccedil;&atilde;o
                </button>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtroManu" ng-model-options="{debounce:500}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="tabbable tabs-left tabbable-bordered">
                <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Data</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Km</th>
                            <th class="text-center">Custo</th>
                            <th class="text-center">Descri&ccedil;&atilde;o</th>
                            <th class="text-center">Arquivos</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaManutencoes.length != 0">
                        <tr ng-repeat="listaManutencao in listaManutencoes|filter:filtroManu">
                            <td class="text-center">{{listaManutencao.dt_manutencao}}</td>
                            <td class="text-center">{{listaManutencao.tipo_view}}</td>
                            <td class="text-center">{{listaManutencao.kilometragem}}</td>
                            <td class="text-center">{{listaManutencao.custo | currency : "R$"}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm" ng-click="verDescricao(listaManutencao)">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </td>
                            <td class="text-center">
                                ({{listaManutencao.total_arquivos}})
                                <button type="button" class="btn btn-default btn-sm" ng-click="getArquivosManutencao(modelo, listaManutencao.i_manutencao)">
                                    <span class="glyphicon glyphicon-file"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-if="listaManutencoes.length == 0">
                        <tr>
                            <td class="text-center" colspan="6">Não a manutenções realizadas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- FIM MANUTENÇOES -->
    <!-- MANUTENÇOES -->
    <div ng-if="detalhes == '4'">
        <div class="page-header-topo">
            <h3 id="grid">
                Lista de Combustiveis
                <h4>&nbsp;&nbsp;Placa: {{placa}}</h4>
                <h4>&nbsp;&nbsp;Modelo: {{modelo}}</h4>
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <button type="button" class="btn btn-warning btn-sm" ng-click="voltar()">
                    Voltar
                </button>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtroCombust" ng-model-options="{debounce:500}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="tabbable tabs-left tabbable-bordered">
                <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                    <thead>
                        <tr>
                            <th>Setor</th>
                            <th class="text-center" width="15%">Gasolina (l)</th>
                            <th class="text-center" width="15%">Valor Gasolina</th>
                            <th class="text-center" width="15%">Diesel (l)</th>
                            <th class="text-center" width="15%">Valor Diesel</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaCombustiveis.length != 0">
                        <tr ng-repeat="listaCombustivel in listaCombustiveis|filter:filtroCombust">
                            <td>{{listaCombustivel.nome_unidade}}</td>
                            <td class="text-center">{{listaCombustivel.litros_g}}</td>
                            <td class="text-center">{{listaCombustivel.valor_g | currency : "R$ "}}</td>
                            <td class="text-center">{{listaCombustivel.litros_d}}</td>
                            <td class="text-center">{{listaCombustivel.valor_d | currency : "R$ "}}</td>
                        </tr>
                    </tbody>
                    <tbody ng-if="listaCombustiveis.length == 0">
                        <tr>
                            <td class="text-center" colspan="6">N&atilde;o a combust&iacute;veis cadastrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- FIM MANUTENÇOES -->
    <?php
    $this->load->view('veiculos/modalCadDocumentos');
    $this->load->view('veiculos/modalEditMulta');
    $this->load->view('veiculos/modalCadManutencao');
    $this->load->view('veiculos/modaldescricaoManutencao');
    ?>
</div>
