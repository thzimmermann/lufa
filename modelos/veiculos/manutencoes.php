<?php
    echo link_tag('assets_novo/css/hint.css');
    echo link_js('assets/js/angular.min.js');
    echo link_js('assets/js/angular-messages.min.js');
?>
<style type="text/css">
    .datepicker{ z-index: 1151 !important; }
</style>
<script type="text/javascript">

    function substituiVirgula(campo) {
        campo.value = campo.value.replace(/,/gi, ".");
    }

    var app = angular.module('appVeiculos',['ngMessages']);

    app.controller('listaManutencao', function($scope,$filter) {

        $scope.listaVeiculosMan = {};
        $scope.formManutencao = {};
        $scope.AgendaMan = {};

        $scope.listaVeiculosMan = JSON.parse(call_valor("veiculos/getManutencoes"));
        $scope.listaVeiculosManutencao = JSON.parse(call_valor("veiculos/getVeiculoslista"));

        $scope.setAgendamentoMan = function(AgendaMan) {

            var data = {
                'i_patrimonio' : AgendaMan.i_patrimonio,
                'data_manutencao':AgendaMan.dataAgendamento,
                'observacoes' : AgendaMan.observacoes,
                'status':'A',
                'modo':'new'
            };

            var retorno = call_valor("veiculos/setAgendamentoMan",data);

            if (retorno==1) {
                alert('Salvo com sucesso!');
                location.reload();
            }
        };

        $scope.setManutencao = function(formManutencao) {
            var data = {
                'i_manutencao':formManutencao.i_manutencao,
                'descricao' : formManutencao.descricao,
                'vl_gasto' : formManutencao.vl_gasto,
                'nr_nota' : formManutencao.nr_nota,
                'modo':'edit'
            };
            var retorno = call_valor("veiculos/setAgendamentoMan",data);
            if (retorno==1) {
                alert('Salvo com sucesso!');
                location.reload();
            }
        };

        $scope.liberaManutencao = function(i_manutencao,i_veiculo) {
            var l = $scope.listaVeiculosMan.length;
            i = 0;
            for (i = 0; i < l; i = i + 1) {
                if ($scope.listaVeiculosMan[i]['i_manutencao'] === i_manutencao) {
                    indice = i;
                }
            }

            if (confirm('Deseja Realmente Concluir a Manutenção')) {

                var data = {
                    'i_manutencao':i_manutencao,
                    'i_patrimonio':i_veiculo,
                    'status':'C',
                    'modo':'update'
                };
                var retorno = call_valor("veiculos/setAgendamentoMan",data);
                $scope.listaVeiculosMan.splice(indice, 1);
            }
        }

        $scope.cancelaManutencao = function(i_manutencao, i_veiculo) {
            var l = $scope.listaVeiculosMan.length;
            i = 0;
            for (i = 0; i < l; i = i + 1) {
                if ($scope.listaVeiculosMan[i]['i_manutencao'] === i_manutencao) {
                    indice = i;
                }
            }

            if(confirm('Deseja Realmente Cancelar a Manutenção')){
                var data = {
                    'i_manutencao':i_manutencao,
                    'i_patrimonio':i_veiculo,
                    'status':'N',
                    'modo':'update'
                };
            }
            var retorno = call_valor("veiculos/setAgendamentoMan",data);
            $scope.listaVeiculosMan.splice(indice, 1);
        }

        $scope.ordenadoPor = function(campo){
            $scope.ordenar = campo;

            if($scope.ordem == false){
               $scope.ordem = true;
            }else{
               $scope.ordem = false;
            }
        }


        $scope.getDadosAgendamento = function(placa, veiculo, i_manutencao, nr_nota, vl_gasto, descricao) {
            $scope.formManutencao.i_manutencao = i_manutencao;
            $scope.formManutencao.nr_nota = nr_nota;
            $scope.formManutencao.descricao = descricao;
            $scope.formManutencao.vl_gasto = vl_gasto;
            $scope.modalTitle = 'Descrição de Manutenção - '+veiculo+' - '+placa;

            angular.element('#i_patrimonio').trigger('click');
        };

        $scope.buscaPatrimonioMan = function(AgendaMan) {
            var data ={'placa': AgendaMan.id_placa};

            $scope.AgendaManPatrimonio = JSON.parse(call_valor("veiculos/getPatrimoniosMan", data));

            if( $scope.AgendaManPatrimonio!=''){
                $scope.AgendaMan.nome_patrimonio= $scope.AgendaManPatrimonio[0].nome;
                $scope.AgendaMan.i_patrimonio = $scope.AgendaManPatrimonio[0].id;

                angular.element('#nome_patrimonio').trigger('click');

            }else{
                alert('Veiculo já esta em Manutenção');
                $scope.AgendaMan.id_placa ='';
            }
        }


        $scope.setVeiculoMan = function (nome, placa,i_patrimonio) {
            $scope.AgendaMan.i_patrimonio= i_patrimonio;
            $scope.AgendaMan.nome_patrimonio= nome;
            $scope.AgendaMan.id_placa = placa;
            angular.element('#bt_fechar').trigger('click');

        };

        $scope.dataLocal = function() {
            var options = { month: 'numeric', day:'numeric', year: 'numeric' };
            var dataAtual =new Date();
            $scope.AgendaMan.dataAgendamento = dataAtual.toLocaleString("pt-BR",options);
        }

        $scope.validaCampo = function (campo, status_form) {
            if (status_form && campo.$invalid) {
                return 'alert-danger';
            } else {
                return '';
            }
        };
    });

</script>
<div ng-app="appVeiculos" ng-controller="listaManutencao">
    <div class="row"><?php $this->load->view("interface_titulo", $titulo_interface)?></div>
    <div class="row form-group col-sm-4">
            <a><?=anchor('veiculos/index', '<span class="glyphicon glyphicon-arrow-left"></span> Voltar', 'class="btn btn-default"')?></a>
            <button data-toggle='modal'
                    data-target='#modal-agendamentoMan'
                    class='btn btn-default'
                    ng-click="dataLocal()">
                    <span class='glyphicon glyphicon-pencil'></span>
                    Agendar Manutenção
            </button>
    </div>
    <legend class="text-center">Manutenções</legend>
     <div>
        <div>
            <div>
                <br>
                <table id="tabela-relatorio" ng-if="listaVeiculosMan!=''" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="4%">
                                <a ng-click="ordenadoPor(' i_manutencao')" >
                                    Cód.
                                <?php echo $this->satc->ordenaTabela(' i_manutencao'); ?>
                                </a>
                            </th>
                            <th width="20%">
                                <a ng-click="ordenadoPor('marca')" >
                                    Veículo
                                    <?php echo $this->satc->ordenaTabela('marca'); ?>
                                </a>
                            </th>
                            <th width="20%" class="text-center">
                                <a ng-click="ordenadoPor('data_manutencao')" >
                                    Data Previa de Conclusão
                                    <?php echo $this->satc->ordenaTabela('data_manutencao'); ?>
                                </a>
                            </th>
                            <th width="20%" >
                                <a ng-click="ordenadoPor('observacoes')" >
                                    Observações
                                    <?php echo $this->satc->ordenaTabela('observacoes'); ?>
                                </a>
                            </th>
                            <th width="10%" class="text-center">
                                Lançar valores
                            </th>
                            <th width="10%" class="text-center">
                                Concluir
                            </th>
                            <th width="10%" class="text-center">
                                Cancelar
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="itens in listaVeiculosMan | orderBy:ordenar:ordem" ng-class="{'success' : itens.descricao!=''}">
                            <td>{{itens.i_manutencao}}</td>
                            <td>{{itens.marca}} - {{itens.placa}}</td>
                            <td class="text-center"><strong>{{itens.data_manutencao}}</strong></td>
                            <td>{{itens.observacoes}}</td>
                            <td  class="text-center" ><button data-toggle='modal'
                                        ng-click="getDadosAgendamento(
                                            itens.placa,
                                            itens.marca,
                                            itens.i_manutencao,
                                            itens.nr_nota,
                                            itens.vl_gasto,
                                            itens.descricao
                                        )"
                                        data-target='#modal-resumoman'
                                        class='btn btn-default btn-sm'>
                                        <span class='glyphicon glyphicon-pencil'></span>
                                        Lançar
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                        name="Confirma_manutencao"
                                        ng-model="confirmar.status"
                                        ng-class="{'disabled' : itens.descricao==''}"
                                        ng-click="liberaManutencao(itens.i_manutencao,itens.i_veiculo)"
                                        class='btn btn-default btn-sm'>
                                        <span class="glyphicon glyphicon-ok"></span>
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                        name="Check_manutencao"
                                        ng-model="confirmar.status"
                                        ng-class="{'disabled' : itens.descricao!=''}"
                                        ng-click="cancelaManutencao(itens.i_manutencao,itens.i_veiculo)"
                                        class='btn btn-default btn-sm'>
                                        <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="alert alert-warning text-center" ng-if="listaVeiculosMan==''">
                        <strong>Nenhuma Manutenção!</strong>
                </p>
            </div>
        </div>
    </div>
        <div
            ng-required="true"
            class="modal fade"
            id="modal-resumoman"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" ng-bind="modalTitle"></h4>
                </div>
                <form name="formResumoMan" role="form" class="form-horizontal">
                <div class="modal-body">
                <input
                    type="hidden"
                    id="i_manutencao"
                    name="i_manutencao"
                    ng-model="formManutencao.i_manutencao"
                    ng-required="true" >
                    <div class="row">
                        <div class="form-group">
                            <label for="dt_fim" class="col-sm-3 control-label">Nº Nota/Valor: </label>
                            <div class="col-sm-5 input-group">
                                 <input
                                    ng-model="formManutencao.nr_nota"
                                    ng-class="validaCampo(formResumoMan.nr_nota, formResumoMan.$dirty)"
                                    ng-required="true"
                                    type="text"
                                    class="form-control"
                                    id="nr_nota"
                                    name="nr_nota"
                                    placeholder="N° Nota">
                                    <span data-hint="Campo Vazio!"
                                            ng-if="formResumoMan.$dirty == true && formResumoMan.nr_nota.$invalid"
                                            class="hint hint--top input-group-addon icon_addon alert-danger">
                                        <i class='icon-exclamation-sign'></i>
                                    </span>
                            </div>
                            <div class="col-sm-3 input-group">
                                     <input
                                    ng-model="formManutencao.vl_gasto"
                                    ng-class="validaCampo(formResumoMan.vl_gasto, formResumoMan.$dirty)"
                                    ng-required="true"
                                    onkeyup="substituiVirgula(this)"
                                    type="text"
                                    class="form-control text-right"
                                    id="vl_gasto"
                                    name="vl_gasto"
                                    placeholder="Valor gasto">
                                    <span data-hint="Campo Vazio!"
                                        ng-if="formResumoMan.$dirty == true && formResumoMan.vl_gasto.$invalid"
                                        class="hint hint--top input-group-addon icon_addon alert-danger">
                                    <i class='icon-exclamation-sign'></i>
                                    </span>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="form-group">
                            <label for="observacoes" class="col-sm-3 control-label">Descrição: </label>
                            <div class="col-sm-8">
                                <textarea
                                    ng-model="formManutencao.descricao"
                                    ng-class="validaCampo(formResumoMan.descricao, formResumoMan.$dirty)"
                                    ng-required="true"
                                    id="descricao"
                                    name="descricao"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Descrição">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    </div>
                <div class="modal-footer">
                    <button
                        type="button" class="btn btn-default" ng-click="formResumoMan.$valid? setManutencao(formManutencao):formResumoMan.$dirty=true">Salvar agendamento
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade"
            id="modal-agendamentoMan"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel"
            aria-hidden="true"
            ng-required="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title-agendarMan" id="myModalLabel">Agendar Manutenção</h4>
                </div>
                <form name="formAgendaMan" role="form" class="form-horizontal">
                <input
                    type="hidden"
                    id="i_patrimonio"
                    name="i_patrimonio"
                    ng-model="AgendaMan.i_patrimonio"
                    ng-required="true" >
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="carteira" class="col-sm-2 ">Nº Placa: </label>
                            <div class="input-group">
                                <div class="col-sm-4 input-group">
                                    <input
                                        ng-model="AgendaMan.id_placa"
                                        ng-class="validaCampo(formAgendaMan.id_placa, formAgendaMan.$dirty)"
                                        ng-required="true"
                                        ng-blur="buscaPatrimonioMan(AgendaMan)"
                                        type="text"
                                        name="id_placa"
                                        id="id_placa"
                                        maxlength="11"
                                        class="form-control input-sm">
                                        <span class="input-group-btn" ng-if="!formAgendaMan.$dirty == true || !formAgendaMan.id_placa.$invalid">
                                            <button data-toggle='modal'
                                                    data-target='#modal-BuscaVeiculos' >
                                                <i class="glyphicon icon-search"></i>
                                            </button>
                                        </span>
                                        <span data-hint="Campo vazio!"
                                              ng-if="formAgendaMan.$dirty == true && formAgendaMan.id_placa.$invalid"
                                              class="hint hint--top input-group-addon icon_addon alert-danger">
                                              <i class='icon-exclamation-sign'></i>
                                        </span>
                                </div>
                                <div class="col-sm-7">
                                    <input
                                        ng-model="AgendaMan.nome_patrimonio"
                                        type="text"
                                        name="nome_patrimonio"
                                        id="nome_patrimonio"
                                        maxlength="100"
                                        class="form-control input-sm"
                                        readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <label for="dt_inicio" class="col-sm-5 ">Data Previa de Conclusão:</label>
                            <div
                                data-date-autoclose="true"
                                data-date-format="dd/mm/yyyy"
                                class="col-sm-3 input-group date ebro_datepicker">
                                <input
                                    ng-model="AgendaMan.dataAgendamento"
                                    ng-required="true"
                                    type="text"
                                    name="dataAgendamento"
                                    id="dataAgendamento"
                                    maxlength="10"
                                    class="form-control input-sm mask_date"
                                    placeholder="Data">
                                <span class="input-group-addon icon_addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="form-group">
                            <label for="observacoes" class="col-sm-2 control-label">Observações: </label>
                            <div class="col-sm-8">
                                <textarea
                                    ng-model="AgendaMan.observacoes"
                                    ng-class="validaCampo(formAgendaMan.observacoes, formAgendaMan.$dirty)"
                                    ng-required="true"
                                    id="observacoes"
                                    name="observacoes"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Observações">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button
                        type="button" class="btn btn-default" ng-click="formAgendaMan.$valid? setAgendamentoMan(AgendaMan):formAgendaMan.$dirty=true ">Salvar agendamento
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade"
            id="modal-BuscaVeiculos"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button
                        id="bt_fechar"
                        name="bt_fechar"
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title-agendarMan" id="myModalLabel">Busca de Veiculos</h4>
                </div>
                <form name="formBuscaVeiculo" role="form" class="form-horizontal">
                <div class="modal-body">
                    <div class="row ">
                        <label for="carteira" class="col-sm-2 ">Veiculo: </label>
                            <div class="col-sm-6 input-group">
                                <input
                                    ng-model="busca_veiculo"
                                    ng-required="true"
                                    type="text"
                                    name="busca_veiculo"
                                    id="busca_veiculo"
                                    maxlength="11"
                                    class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-sm-12" id="div_tabela">
                        <table id="tabela-relatorio" ng-show="listaVeiculosManutencao.length>0" class="table table-condensed table-bordered table-hover table_satc">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        Cód
                                    </th>
                                    <th width="18%">
                                        Veículo
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="itens in listaVeiculosManutencao|filter:busca_veiculo" ng-click="setVeiculoMan(itens.marca,itens.placa,itens.id)">
                                    <td class='text-center'>{{itens.id}}</td>
                                    <td>{{itens.marca}} - {{itens.placa}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    a:hover, a:visited {
        text-decoration: none;
        color: #333;
    }
</style>
