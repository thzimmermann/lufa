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
    echo link_js('assets_novo/js/Chart.min.js');
    echo link_js('assets_novo/js/angular-chart.min.js');
    echo link_ng('veiculos/controller/gerenciarVeiculosController.js');
    echo link_js('assets_novo/js/angular-locale_pt-br.js');
?>
<style type="text/css">
    .datepicker{z-index:9999 !important}
    .font-bold {font-weight: bold}
    .select2-drop {
        position: fixed !important;
        margin-bottom: 10px;
        margin-top: 10px;
    }
    .mGR-15 {margin-right: 15px !important;}
    .mGT-20 {margin-top: 20px !important;}
    .mGT-3 {margin-top: 3px !important;}
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
            <div class="col-sm-9">
                <div class="form-group">
                    <button class="btn btn-sm btn-default" onclick="location.href='condutoresCarros'">
                        <span class="glyphicon glyphicon-list-alt"></span> Alocações Condutor/Carro
                    </button>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalRelatorios">
                        <span class="glyphicon glyphicon-print"></span> Relatórios
                    </button>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group ">
                    <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtro">
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
                            <th class="text-center" width="10%">Pr&oacute;x. Manuten&ccedil;&atilde;o Preventiva</th>
                            <th class="text-center" width="8%">Manuten&ccedil;&otilde;es</th>
                            <th class="text-center" width="10%">Data de Validade</th>
                            <th class="text-center" width="8%">Documentos</th>
                            <th class="text-center" width="10%">Multas Pendentes</th>
                            <th class="text-center" width="8%">Combust&iacute;vel</th>
                            <th class="text-center" width="8%">Utiliza&ccedil;&otilde;es</th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="lista in listaVeiculos|filter:filtro| groupBy:'i_grupo'">
                        <tr>
                            <td colspan="10"><b>{{lista[0].i_grupo}}<b></td>
                        </tr>
                        <tr ng-repeat="listaVeiculo in lista">
                            <td class="text-center">{{listaVeiculo.i_patrimonio}}</td>
                            <td class="text-center">{{listaVeiculo.placa}}</td>
                            <td>{{listaVeiculo.marca_modelo}}</td>
                            <td  class="text-center" ng-class="{'danger font-bold':  listaVeiculo.proximamanutencao > -5 && listaVeiculo.total_manutencoes_prev > 0}">
                                {{listaVeiculo.dt_manutencao_prev!='' && listaVeiculo.total_manutencoes_prev>0?listaVeiculo.dt_manutencao_prev:listaVeiculo.total_manutencoes_prev > 0?'Manutenção a fazer':''}}
                            </td>
                            <td class="text-center">
                                ({{listaVeiculo.total_manutencoes}})
                                <button class="btn btn-default btn-sm btn-xs" ng-click="listarManutencoes(listaVeiculo)">
                                    <span class="glyphicon glyphicon-wrench"></span>
                                </button>
                            </td>
                            <td  class="text-center" ng-class="{'danger font-bold':  listaVeiculo.diferenca > -30 && listaVeiculo.total_documentos > 0}">
                                {{listaVeiculo.dt_vencimento==''&&listaVeiculo.total_documentos>0?'Há documentos vencidos':listaVeiculo.dt_vencimento}}
                            </td>
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
                            <td class="text-center">
                                ({{listaVeiculo.total_utilizacoes}})
                                <button class="btn btn-default btn-sm btn-xs" ng-click="listarUtilizacoes(listaVeiculo)">
                                    <span class="glyphicon glyphicon-stats"></span>
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
                    <span class="glyphicon glyphicon-arrow-left"></span> Voltar
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
                            <th class="text-center">Descri&ccedil;&atilde;o</th>
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
                            <td class="text-center" colspan="3">N&atilde;o a Documentos cadastrados</td>
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
                    <span class="glyphicon glyphicon-arrow-left"></span> Voltar
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
                            <th>Condutor</th>
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
                            <td>{{listaMulta.nome_usuario}}</td>
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
                            <td class="text-center" colspan="7">N&atilde;o a multas pendentes</td>
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
                    <span class="glyphicon glyphicon-arrow-left"></span> Voltar
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
                            <th class="text-center" colspan="2" rowspan="2">Data</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center" colspan="3" rowspan="2">Km</th>
                            <th class="text-center">Custo</th>
                            <th class="text-center">Descri&ccedil;&atilde;o</th>
                            <th class="text-center">Arquivo</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaManutencoes.length != 0">
                        <tr>
                            <td class="text-center"><b>Manuten&ccedil;&atilde;o</b></td>
                            <td class="text-center"><b>Prox.</b></td>
                            <td></td>
                            <td class="text-center"><b>Manuten&ccedil;&atilde;o</b></td>
                            <td class="text-center"><b>Atual</b></td>
                            <td class="text-center"><b>Prox.</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr ng-repeat="listaManutencao in listaManutencoes|filter:filtroManu"  ng-class="{'danger':listaManutencao.dt_prox_manutencao < listaManutencao.dt_atual && listaManutencao.dt_prox_manutencao != '' || listaManutencao.km_prox_manutencao < listaManutencao.km_atual && listaManutencao.km_prox_manutencao > 0}">
                            <td class="text-center">{{listaManutencao.dt_manutencao}}</td>
                            <td class="text-center">{{listaManutencao.dt_prox_manutencao}}</td>
                            <td class="text-center">{{listaManutencao.tipo_view}} <span ng-show="listaManutencao.tipo == 'P' && listaManutencao.dt_prox_manutencao != ''"><br>Pr&oacute;xima manutenção: {{listaManutencao.dt_prox_manutencao}} ou {{listaManutencao.km_prox_manutencao}}km</span></td>
                            <td class="text-center">{{listaManutencao.kilometragem}}</td>
                            <td class="text-center">{{listaManutencao.km_atual}}</td>
                            <td class="text-center">{{listaManutencao.km_prox_manutencao}}</td>
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
                            <td class="text-center" colspan="6">N&atilde;o a manutenções realizadas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- FIM MANUTENÇOES -->
    <!-- COMBUSTIVEIS -->
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
                    <span class="glyphicon glyphicon-arrow-left"></span> Voltar
                </button>
                <button type="button" class="btn btn-primary btn-sm" ng-click="relatorioCombustivelCarro(i_patrimonio)">
                    <span class="glyphicon glyphicon-print"></span> Relat&oacute;rio
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
                            <th class="text-center" width="15%">Litros</th>
                            <th class="text-center" width="15%">Valor</th>
                            <th class="text-center" width="15%">Combustivel</th>
                            <th class="text-center" width="15%">Combust&iacute;vel Interno</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaCombustiveis.length != 0">
                        <tr ng-repeat="listaCombustivel in listaCombustiveis|filter:filtroCombust">
                            <td>{{listaCombustivel.nome_unidade}}</td>
                            <td class="text-center">{{listaCombustivel.litros | currency : ""}}</td>
                            <td class="text-center">{{listaCombustivel.valor | currency : "R$ "}}</td>
                            <td class="text-center">{{(listaCombustivel.combustivel == 'D')?'Diesel':(listaCombustivel.combustivel == 'G')?'Gasolina':'Alcool'}}</td>
                            <td class="text-center">{{(listaCombustivel.interno == 'S') ?'Sim':'Não'}}</td>
                        </tr>
                    </tbody>
                    <tbody ng-if="listaCombustiveis.length == 0">
                        <tr>
                            <td class="text-center" colspan="5">N&atilde;o a combust&iacute;veis cadastrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- FIM COMBUSTIVEIS -->
    <!-- UTILIZACOES -->
    <div ng-if="detalhes == '5'">
        <div class="page-header-topo">
            <h3 id="grid">
                Lista de Utilizações
                <h4>&nbsp;&nbsp;Placa: {{placa}}</h4>
                <h4>&nbsp;&nbsp;Modelo: {{modelo}}</h4>
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <button type="button" class="btn btn-warning btn-sm" ng-click="voltar()">
                    <span class="glyphicon glyphicon-arrow-left"></span> Voltar
                </button>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                    <form class="form-inline">
                        <div class="form-group ">
                            <input placeholder="Pesquisa" type="text"  class="form-control" ng-model="filtroUti" ng-model-options="{debounce:500}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12 tabbable tabs-left tabbable-bordered">
                <table id="table_veiculos" class="table table-hover table-striped table_satc table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Condutor</th>
                            <th class="text-center" width="15%">Data Inicial</th>
                            <th class="text-center" width="15%">Data Final</th>
                            <th class="text-center" width="9%">Nº Passageiros</th>
                            <th class="text-center">Destino</th>
                            <th class="text-center" width="7%">Justificativa</th>
                        </tr>
                    </thead>
                    <tbody ng-if="listaUtilizacoes.length != 0">
                        <tr ng-repeat="listaUtilizacao in listaUtilizacoes|filter:filtroUti">
                            <td>{{listaUtilizacao.condutor}}</td>
                            <td class="text-center">{{listaUtilizacao.dt_alocacao_inicial}}</td>
                            <td class="text-center">{{listaUtilizacao.dt_alocacao_final}}</td>
                            <td class="text-center">{{listaUtilizacao.n_passageiros}}</td>
                            <td>{{listaUtilizacao.destino}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm" ng-click="verMotivo(listaUtilizacao.justificativa)">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-if="listaUtilizacoes.length == 0">
                        <tr>
                            <td class="text-center" colspan="6">N&atilde;o a hist&oacute;rico de utiliza&ccedil;&otilde;es</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- FIM UTILIZACOES -->
    <?php
        $this->load->view('veiculos/modalCadDocumentos');
        $this->load->view('veiculos/modalEditMulta');
        $this->load->view('veiculos/modalCadManutencao');
        $this->load->view('veiculos/modaldescricaoManutencao');
        $this->load->view('veiculos/modalMotivoAlocacao');
        $this->load->view('veiculos/modalRelatorios');
        $this->load->view('veiculos/modalGraficoKmRodado');
    ?>
</div>
