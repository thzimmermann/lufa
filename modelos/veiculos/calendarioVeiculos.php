<?php
echo link_tag('assets/css/fullcalendar.css');
echo link_tag('assets/css/fullcalendar.print.css');
echo link_js('assets/js/fullcalendar.js');
echo link_js('assets_novo/js/funcoes_javascript.js');
echo link_tag('assets_novo/css/select2.css');
echo link_tag('assets_novo/css/ebro_select2.css');
echo link_js('assets_novo/js/select2.min.js');
?>
<style>
    .datepicker{ z-index: 1151 !important; }
    .select2-drop {
        margin-bottom: 10px;
        margin-top: 10px;
    }

    .fc-event-title {
        font-size: 16px !important;
    }
</style>
<script type="text/javascript">
    function salva_agendamento()
    {
        var data_atual = "<?=date('d/m/Y')?>";
        var tempo_atual = "<?=date('H:i')?>";

        var nova_data_atual = data_atual.split("/")[2].toString()
                            + data_atual.split("/")[1].toString()
                            + data_atual.split("/")[0].toString();
        var novo_tempo_atual = tempo_atual.split(':')[0].toString() + tempo_atual.split(':')[1].toString();

        var data_retirada = $("#dt_inicio").val();
        var data_entrega = $("#dt_fim").val();
        var hr_inicio = $("#hr_inicio").val();
        var hr_fim = $("#hr_fim").val();

        if ($.trim(data_retirada)=='') {
            alert('Informe a data de retirada do veículo!');
            $("#dt_inicio").focus();
            return false;
        }

        if ($.trim(hr_inicio)=='') {
            alert('Informe o tempo de retirada do veículo!');
            $("#hr_inicio").focus();
            return false;
        }

        if ($.trim(data_entrega)=='') {
            alert('Informe a data de entrega do veículo!');
            $("#dt_fim").focus();
            return false;
        }

        if ($.trim(hr_fim)=='') {
            alert('Informe o tempo de entrega do veículo!');
            $("#hr_fim").focus();
            return false;
        }

        if (!verificaData(data_retirada)) {
            alert('Data de retirada invalída!');
            $("#dt_inicio").focus();
            return false;
        }

        if (!verificaData(data_entrega)) {
            alert('Data de entrega invalída!');
            $("#dt_fim").focus();
            return false;
        }

        var nova_data_retirada = data_retirada.split("/")[2].toString()
                               + data_retirada.split("/")[1].toString()
                               + data_retirada.split("/")[0].toString();

        var nova_data_entrega = data_entrega.split("/")[2].toString()
                              + data_entrega.split("/")[1].toString()
                              + data_entrega.split("/")[0].toString();

        var novo_tempo_retirada = hr_inicio.split(':')[0].toString() + hr_inicio.split(':')[1].toString();
        var novo_tempo_entrega = hr_fim.split(':')[0].toString() + hr_fim.split(':')[1].toString();

        if (parseInt(nova_data_retirada+novo_tempo_retirada) < parseInt(nova_data_atual+novo_tempo_atual)) {
            alert('Data de retirada não pode ser menor que a data atual!');
            $("#dt_inicio").focus();
            return false;
        }

        if (parseInt(nova_data_entrega+novo_tempo_entrega) < parseInt(nova_data_retirada+novo_tempo_retirada)) {
            alert('Data de entrega não pode ser menor que a data de retirada!');
            $("#dt_fim").focus();
            return false;
        }

        $("#form_salvar_locacao").submit();
    }

    $(document).ready(function(){
        $('.calendar').fullCalendar({
            events: <?php echo $list_calendar; ?>,
            timeFormat: 'H(:mm)',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            eventClick: function(e) {
                openList(e.i_alocacao)
            },
            eventRender: function (event, element) {
                element.find('span.fc-event-title').html(
                    element.find('span.fc-event-title').text()
                );
            }
        });

        $("#i_usuario").blur(function(){
            $("#cnh_reprovada").addClass("hidden");
            $("#cnh_vencida").addClass("hidden");
            $("#chn_semcadastro").addClass("hidden");
            $("#agendamento").removeClass("hidden");
            $("#agendamento_").removeClass("hidden");
            if ($.trim(this.value) != '') {
                var campo = call_valor("veiculos/getCondutor", {valor:this.value});
                if (campo!="") {
                    var obj = $.decodeJSON(campo);
                    $("#i_usuario").val(obj.i_func);
                    $("#dc_condutor").val(obj.nome);
                    var dados_cadastrados = call_valor("veiculos/getDadosCondutor", {valor:obj.i_usuario});
                    if (dados_cadastrados!='') {
                        var obj_cadastro = $.decodeJSON(dados_cadastrados);
                        if (obj_cadastro.status_cnh == 'R') {
                            $("#cnh_reprovada").removeClass("hidden");
                            $("#agendamento").addClass("hidden");
                            $("#agendamento_").addClass("hidden");
                        }

                        if (obj_cadastro.vencida == 1) {
                            $("#cnh_avencer").removeClass("hidden");
                        }

                        if (obj_cadastro.status_cnh == 'VN') {
                            $("#agendamento").addClass("hidden");
                            $("#agendamento_").addClass("hidden");
                            $("#cnh_vencida").removeClass("hidden");
                            if (obj_cadastro.vencida == 1) {
                                $("#cnh_avencer").addClass("hidden");
                            }
                        }

                        $("#carteira").val(obj_cadastro.numero_carteira);
                        $("#dataValidade").val(obj_cadastro.data_validade);
                        $("#categoria").val(obj_cadastro.categoria);
                        $("#observacoes").val(obj_cadastro.observacao);
                    } else {
                        $("#agendamento").addClass("hidden");
                        $("#agendamento_").addClass("hidden");
                        $("#chn_semcadastro").removeClass("hidden");
                        $("#carteira, #dataValidade, #categoria, #observacoes").val('');
                    }
                } else {
                    $("#i_usuario, #dc_condutor, #carteira, #dataValidade, #categoria, #observacoes").val("");
                }
            }
        });

        $("#i_patrimonio").select2({
            minimumInputLength: 0,
            ajax: {
                url: "../veiculosMultas/getVeiculosAjax",
                dataType: "json",
                data: function (term, page) {
                    return {
                        busca: term
                    };
                },
                results: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
        });

        $('#i_cidade_ibge').select2({
            minimumInputLength: 3,
            ajax: {
                url: "<?php echo base_url();?>index.php/veiculos/getCidadesAjax",
                dataType: 'json',
                data: function (term, page) {
                    return {
                        busca: term
                    };
                },
                results: function (data, page) {
                    return {
                        results: data
                    };
                }
            },
        });

        $('#i_unidade').select2({
            minimumInputLength: 3,
            ajax: {
                url: "<?php echo base_url();?>index.php/veiculos/getUnidadesAjax",
                dataType: 'json',
                data: function (term, page) {
                    return {
                        busca: term
                    };
                },
                results: function (data, page) {
                    return {
                        results: data
                    };
                }
            },
        });

        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    });

    function openList(i_alocacao)
    {
        var obj = JSON.parse(call_valor('veiculos/getDadosAlocacao?i_alocacao='+i_alocacao));
        var obj_usuarios = JSON.parse(call_valor('veiculos/verificaAdmin'));
        if (obj) {
            $("#i_patrimonio").select2("data",{id: '',text: ''});
            $('#btn-aprovar').addClass('hide');
            $('#div-observacao').addClass('hide');
            $('#veiculo_select2').addClass('hide');
            $('#btn-reprovar').addClass('hide');
            $('#veiculo').addClass('hide');
            $('#compromisso').addClass('hide');
            $('#sala').addClass('hide');
            for (var i in obj) {
                $('#'+i+'_v').html(obj[i]);
            }
            $('#veiculo').removeClass('hide');
            $('#title-list').html('<span class="i-veiculo"></span> Agendamento Veículo');
            if (obj['status_aprov'] == 'P') {
                if (obj_usuarios['admin'] == 'S') {
                    $('#btn-aprovar').attr('onclick',"aprovarVeiculo("+i_alocacao+")");
                    $('#btn-reprovar').attr('onclick',"reprovarVeiculo("+i_alocacao+")");
                    $('#btn-aprovar').removeClass('hide');
                    $('#btn-reprovar').removeClass('hide');
                    $('#div-observacao').removeClass('hide');
                    $('#veiculo_select2').removeClass('hide');
                }
            }
            $('#modal-list').modal('show');
        }
    }

    function reprovarVeiculo(i_alocacao)
    {
        if (confirm('Deseja realmente remover o agendamento?')) {
            var msg = call_valor('veiculos/setAgendamentoStatus?i_alocacao='+i_alocacao+'&status=R');
            if (msg=='') {
                alert('Reprovado com sucesso!');
                location.reload();
            }
        }
    }

    function aprovarVeiculo(i_alocacao)
    {
        if ($('#i_patrimonio').select2('data') == null) {
            alert('Selecione um veículo para Aprovar.');
            return false;
        }
        var i_patrimonio = $('#i_patrimonio').select2('data').id;
        if (confirm('Deseja realmente aprovar o agendamento?')) {
            var msg = call_valor('veiculos/setAgendamentoStatus?i_alocacao='+i_alocacao+'&i_patrimonio='+i_patrimonio+'&status=A'+'&observacao='+document.getElementById("observacao").value);
            if (msg=='') {
                alert('Aprovado com sucesso!');
                location.reload();
            }
        }
    }

    function adicionar_compromisso(data) {
        var data_atual = "<?php echo DATE('d/m/Y'); ?>";
        var vetor_atual = data_atual.split("/");
        var vetor_limit = data.split("/");

        data_atual = parseInt(vetor_atual[2]+vetor_atual[1]+vetor_atual[0]);
        data_form = parseInt(vetor_limit[2]+vetor_limit[1]+vetor_limit[0]);

        if (data_form < data_atual) {
            alert("Agendamento a partir da data atual e com 12 horas de antecedência!");
        } else {
            $('.date-calendar').val(data);
            $('#modal-compromisso').modal('show');
        }
    }

    function verificaData(Data)
    {
        Data = Data.substring(0,10);
        var dma = -1;
        var data = Array(3);
        var ch = Data.charAt(0);

        for(i=0; i < Data.length && (( ch >= '0' && ch <= '9' ) || ( ch == '/' && i != 0 ) ); ){
            data[++dma] = '';
            if(ch!='/' && i != 0) return false;
            if(i != 0 ) ch = Data.charAt(++i);
            if(ch=='0') ch = Data.charAt(++i);
            while( ch >= '0' && ch <= '9' ){
                data[dma] += ch;
                ch = Data.charAt(++i);
            }
        }
        if(ch!='') return false;
        if(data[0] == '' || isNaN(data[0]) || parseInt(data[0]) < 1) return false;
        if(data[1] == '' || isNaN(data[1]) || parseInt(data[1]) < 1 || parseInt(data[1]) > 12) return false;

        if(data[2] == '' || isNaN(data[2]) || ((parseInt(data[2]) < 0 ||
            parseInt(data[2]) > 99 ) && (parseInt(data[2]) < 1900 || parseInt(data[2]) > 9999))) return false;

        if(data[2] < 50) data[2] = parseInt(data[2]) + 2000;
        else if(data[2] < 100) data[2] = parseInt(data[2]) + 1900;
        switch(parseInt(data[1])){
            case 2: {
                if(((parseInt(data[2])%4!=0 || (parseInt(data[2])%100==0 &&
                     parseInt(data[2])%400!=0)) && parseInt(data[0]) > 28) || parseInt(data[0]) > 29 ) return false;
                break;
            }
            case 4:
            case 6:
            case 9:
            case 11: {
                if(parseInt(data[0]) > 30) return false;
                break;
            }
            default: {
                if(parseInt(data[0]) > 31) return false;
            }
        } return true;
    }

    function verificarHorario(nome_form, tipo, id_dataini, id_datafim, id_horaini, id_horafim, id_params)
    {

        if ($.trim($('#'+id_dataini).val())!=''&&$.trim($('#'+id_datafim).val())!=''&&
            $.trim($('#'+id_horaini).val())!=''&&$.trim($('#'+id_horafim).val())!=''&&
            $.trim($('#'+id_params).val())!=''
        ) {
            var data_atual = "<?php echo DATE('d/m/Y'); ?>";
            var vetor_atual = data_atual.split("/");
            var vetor_limit = $('#'+id_dataini).val().split("/");

            data_atual = parseInt(vetor_atual[2]+vetor_atual[1]+vetor_atual[0]);
            data_form = parseInt(vetor_limit[2]+vetor_limit[1]+vetor_limit[0]);

            if (data_form < data_atual) {
                alert("Agendamento a partir da data atual e com 12 horas de antecedência!");
                return false;
            }
        } else {
            $('#'+nome_form).submit();
        }
    }

    function clickMenu(item)
    {
        $('div[class*="item-"]').each(function(e){
            if ((parseInt(e)+1)==parseInt(item)){
                $(this).addClass('btn-primary');
                $(this).removeClass('btn-default');
            } else {
                $(this).addClass('btn-default');
                $(this).removeClass('btn-primary');
            }
        });

        $('div[class*="formulario-"]').each(function(e){
            if ((parseInt(e)+1)==parseInt(item)){
                $(this).removeClass('hide');
            } else {
                $(this).addClass('hide');
            }
        });
    }
</script>
<?php $this->load->view("interface_titulo", $titulo_interface); ?>
<?php $tipo_solicitacao = 'P'; ?>
<div class="row">
    <div class="col-sm-12">
        <a href="/portais/colaborador_novo/index.php/veiculos/geraPdfCalendario/" target="_blank" class="btn btn-default pull-right" style="margin-bottom: 10px"> Imprimir </a href="portais/colaborador_novo/">
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="calendar"></div>
    </div>
</div>

<div id="modal-list" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">×</button>
                <h4 id="title-list" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div id="veiculo" class="hide">
                    <div class="panel panel-default">
                        <div class="panel-heading">Formulário de Veículos</div>
                        <div class="panel-body">
                            <dl class="dl-horizontal">
                                <dt>Condutor:</dt>
                                <dd id='nome_solicitante_v'></dd>
                                <dt>Justificativa:</dt>
                                <dd id='justificativa_v'></dd>
                                <dt>N° Passageiros:</dt>
                                <dd id='n_passageiros_v'></dd>
                                <dt>Destino:</dt>
                                <dd id='destino_v'></dd>
                                <dt>Data Inicial:</dt>
                                <dd id='dt_alocacao_ini_v'></dd>
                                <dt>Data Final:</dt>
                                <dd id='dt_alocacao_fim_v'></dd>
                                <dt>Unidade:</dt>
                                <dd id='nome_unidade_v'></dd>
                                <dt>Veículo:</dt>
                                <dd id='veiculo_v'></dd>
                                <dt>Observação:</dt>
                                <dd id='observacao_v'></dd>
                            </dl>
                            <div id="veiculo_select2" class="row hide">
                                <div class="form-group">
                                    <label>&nbsp; Ve&iacute;culo: </label>
                                    <input select2 type="hidden" name="i_patrimonio" id="i_patrimonio" class="input-sm">
                                </div>
                            </div>
                            <div id="div-observacao" class="row">
                                <div class="form-group">
                                    <label for="veiculo" class="col-sm-3 control-label">Observa&ccedil;&atilde;o:</label>
                                    <div class="col-sm-7">
                                       <textarea name="observacao" id="observacao" rows="3" cols="23" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group text-right">
                        <button id="btn-aprovar" type="button" class="btn btn-success btn-sm hide">Aprovar</button>
                        <button id="btn-reprovar" type="button" class="btn btn-danger btn-sm hide">Reprovar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-aprovar" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">×</button>
                <h4 class="modal-title">Aprova&ccedil;&atilde;o do Agendamento</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label>&nbsp; Ve&iacute;culo: </label>
                                <input select2 type="hidden" name="i_patrimonio" id="i_patrimonio" class="input-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group text-right">
                        <button id="btn-salvar-aprovacao" type="button" class="btn btn-success">Aprovar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modal-compromisso" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">×</button>
                <h4 class="modal-title">Agendamento de Ve&iacute;culos</h4>
            </div>
            <div class="modal-body">
                <?php
                    echo satc_form_open(
                        'form_salvar_locacao',
                        'veiculos/setAgendamento',
                        'veiculos/calendarioVeiculos',
                        '',
                        array('role'=>'form'),
                        0,
                        'form-horizontal'
                    );
                ?>
                <fieldset><legend>Condutor</legend></fieldset>
                <div class="row">
                    <div class="form-group">
                        <label for="carteira" class="col-sm-3 control-label">C&oacute;d. Condutor: </label>
                        <div class="input-group">
                            <div class="col-sm-4 input-group">
                                <input type="text" name="i_usuario" value="" id="i_usuario" maxlength="11" class="form-control input-sm ebro_inteiro" valida="1">
                                <span class="input-group-btn">
                                    <button type="button" id="btn_i_usuario" class="btn btn-default btn-sm gabox21" data-target="modal_i_usuario" data-title="Consultar Condutor" data-remote="<?=base_url().index_page()?>/veiculos/getCondutores?i_usuario=0&dc_condutor=1&id_box=modal_i_usuario">
                                        <span class="glyphicon icon-search"></span>
                                    </button>
                                </span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="dc_condutor" disabled value="" id="dc_condutor" maxlength="100" class="form-control input-sm" valida="1" readonly="true">
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="form-group">
                        <label for="carteira" class="col-sm-3 control-label">N&ordm; Carteira: </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="carteira" name="carteira" placeholder="N° Carteira" valida="1" disabled>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="form-group">
                        <label for="dataValidade" class="col-sm-3 control-label">Data Validade: </label>
                        <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="col-sm-4 input-group date ebro_datepicker">
                            <input type="text" name="dataValidade" id="dataValidade" maxlength="10" class="form-control input-sm mask_date" disabled placeholder="Data Validade" valida="1">
                            <span class="input-group-addon icon_addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="form-group">
                        <label for="categoria" class="col-sm-3 control-label">Categoria: </label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="categoria" name="categoria" placeholder="" valida="1" disabled>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="form-group">
                        <label for="observacoes" class="col-sm-3 control-label">Observa&ccedil;&otilde;es: </label>
                        <div class="col-sm-8">
                            <textarea rows="3" class="form-control" id="observacoes" name="observacoes" placeholder="Observações" disabled></textarea>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row hidden" id="cnh_reprovada" name="cnh_reprovada">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="alert alert-danger alert-dismissible text-center" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                CHN não habilitada.<br><small></small>
                            </div>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
                <div class="row hidden" id="cnh_vencida" name="cnh_vencida">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="alert alert-danger alert-dismissible text-center" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Sua carteira est&aacute; vencida.<br><small>Atualize suas informa&ccedil;&otilde;es em seu perfil</small>
                            </div>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
                <div class="row hidden" id="cnh_avencer" name="cnh_avencer">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="alert alert-warning alert-dismissible text-center" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Sua carteira est&aacute; para vencer.
                            </div>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
                <div class="row hidden" id="chn_semcadastro" name="chn_semcadastro">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="alert alert-danger alert-dismissible text-center" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Condutor com carteira n&atilde;o cadastrada.<br><small>Atualize suas informa&ccedil;&otilde;es em seu perfil</small>
                            </div>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
                <br/>
                <div id="agendamento" name="agendamento">
                    <fieldset><legend>Agendamento</legend></fieldset>
                    <div class="row">
                        <div class="form-group">
                            <label for="dt_inicio" class="col-sm-4 control-label">Data/Hora de retirada: </label>
                            <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="col-sm-3 input-group date ebro_datepicker">
                                <input type="text" name="dt_inicio" id="dt_inicio" maxlength="10" class="form-control input-sm mask_date date-calendar" placeholder="Data" valida="1">
                                <span class="input-group-addon icon_addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" data-inputmask="'mask': '99:99'" class="form-control" id="hr_inicio" name="hr_inicio" placeholder="Hora" valida="1">
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="form-group">
                            <label for="dt_fim" class="col-sm-4 control-label">Data/Hora de entrega: </label>
                            <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="col-sm-3 input-group date ebro_datepicker">
                                <input type="text" name="dt_fim" id="dt_fim" maxlength="10" class="form-control input-sm mask_date date-calendar" placeholder="Data" valida="1">
                                <span class="input-group-addon icon_addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" data-inputmask="'mask': '99:99'" class="form-control" id="hr_fim" name="hr_fim" placeholder="Hora" valida="1">
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="form-group">
                            <label for="cidade" class="col-sm-4 control-label">Cidade: </label>
                            <div class="col-sm-7">
                                <input type="hidden" name="i_cidade_ibge" id="i_cidade_ibge">
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="i_unidade" class="col-sm-4 control-label">Unidade: </label>
                                <input type="hidden" name="i_unidade" id="i_unidade" select2>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row" id="div_justificativa" name="div_justificativa">
                        <div class="form-group">
                            <label for="justificativa" class="col-sm-4 control-label">Motivo: </label>
                            <div class="col-sm-7">
                                <textarea rows="3" class="form-control" id="justificativa" name="justificativa" placeholder="Motivo" valida="1"></textarea>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row" id="div_n_passageiros" name="div_n_passageiros">
                        <div class="form-group">
                            <label for="n_passageiros" class="col-sm-4 control-label">N&ordm; Passageiros: </label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="n_passageiros" name="n_passageiros" placeholder="" valida="1">
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer" id="agendamento_" name="agendamento_">
                <button type="button" class="btn btn-success btn-sm" onclick="salva_agendamento()">Salvar agendamento</button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" aria-label="Close">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
