<?php
echo link_tag('assets_novo/css/select2.css');
echo link_tag('assets_novo/css/ebro_select2.css');
//echo link_tag('assets/css/plugins/jquery-ui/jquery-ui.css');
echo link_js('assets_novo/js/select2.min.js');
//echo link_js('assets/js/plugins/jquery-ui/jquery-ui.js');
?>
<style type="text/css">
    .datepicker{ z-index: 1151 !important; }
    .font-icone { font-size: 16px; }
    .bold { font-weight: bold; }
    .select2-drop {
        position: fixed !important;
        margin-bottom: 10px;
        margin-top: 10px;
    }
    .ui-widget-content{
        background: #ececec;
        padding: 1em;
        box-shadow: 2px 4px #cacaca;
    }

</style>
<script type="text/javascript">
    $(function(){
        $(document).tooltip({
            position: {
                at: "center"
            }
        });
    });
</script>
<script type="text/javascript">
    function salva_agendamento()
    {
        var data_atual = "<?=date('d/m/Y')?>";
        var tempo_atual = "<?=date('H:i')?>";

        var nova_data_atual = data_atual.split("/")[2].toString()
                            + data_atual.split("/")[1].toString()
                            + data_atual.split("/")[0].toString();
        var novo_tempo_atual = tempo_atual.split(':')[0].toString() + tempo_atual.split(':')[1].toString();

        var data_validade = $("#dataValidade").val();
        if ($.trim(data_validade)=='') {
            alert('Informe a data de validade da carteira!');
            $("#dataValidade").focus();
            return false;
        }

        if (!verificaData(data_validade)) {
            alert('Data da carteira invalída!');
            $("#dataValidade").focus();
            return false;
        }

        var nova_data_validade = parseInt(data_validade.split("/")[2].toString()
                               + data_validade.split("/")[1].toString()
                               + data_validade.split("/")[0].toString());

        if (nova_data_atual>nova_data_validade) {
            alert('Sua carteira está vencida!');
            $("#dataValidade").focus();
            return false;
        }

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

        if (parseInt(nova_data_retirada) > parseInt(nova_data_validade)) {
            alert('Data de retirada não pode ser maior que a data de validade da CNH!');
            $("#dt_inicio").focus();
            return false;
        }
        if (parseInt(nova_data_entrega) > parseInt(nova_data_validade)) {
            alert('Data de entrega não pode ser maior que a data de validade da CNH!');
            $("#dt_fim").focus();
            return false;
        }

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

    $(document).ready(function() {
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

        $("#dt_inicio").change(function(){
            setTimeout(function () {
                $('#tabe').addClass("hidden");
                $("#destino_igual").addClass("hidden");
                var data_retirada = $("#dt_inicio").val();
                var data_entrega = $("#dt_fim").val();
                var i_cidade_ibge = $('#i_cidade_ibge').select2('data').id;
                var hr_inicio = $("#hr_inicio").val();
                var hr_fim = $("#hr_fim").val();
                var campo = call_valor("veiculos/getAgendamentoData?data_retirada="+data_retirada+"&data_entrega="+data_entrega+"&i_cidade_ibge="+i_cidade_ibge);
                var array = JSON.parse(campo);
                if (array != null) {
                    $('#tabe').removeClass("hidden");
                    $("#destino_igual").removeClass("hidden");
                    $("#lb_condutor").text(array.condutor);
                    $("#lb_destino").text(array.destino);
                    $("#lb_n_passageiros").text(array.n_passageiros);
                    $("#lb_status").text(array.status_aprov);
                }
            }, 300);
        });
        $("#dt_fim").change(function(){
            setTimeout(function () {
                $('#tabe').addClass("hidden");
                $("#destino_igual").addClass("hidden");
                var data_retirada = $("#dt_inicio").val();
                var data_entrega = $("#dt_fim").val();
                var i_cidade_ibge = $('#i_cidade_ibge').select2('data').id;
                var hr_inicio = $("#hr_inicio").val();
                var hr_fim = $("#hr_fim").val();
                var campo = call_valor("veiculos/getAgendamentoData?data_retirada="+data_retirada+"&data_entrega="+data_entrega+"&i_cidade_ibge="+i_cidade_ibge);
                var array = JSON.parse(campo);
                if (array != null) {
                    $('#tabe').removeClass("hidden");
                    $("#destino_igual").removeClass("hidden");
                    $("#lb_condutor").text(array.condutor);
                    $("#lb_destino").text(array.destino);
                    $("#lb_n_passageiros").text(array.n_passageiros);
                    $("#lb_status").text(array.status_aprov);
                }
            }, 300);
        });

        $("#hr_inicio").blur(function(){
            setTimeout(function () {
                $('#tabe').addClass("hidden");
                $("#destino_igual").addClass("hidden");
                var data_retirada = $("#dt_inicio").val();
                var data_entrega = $("#dt_fim").val();
                var i_cidade_ibge = $('#i_cidade_ibge').select2('data').id;
                var hr_inicio = $("#hr_inicio").val();
                var hr_fim = $("#hr_fim").val();
                var campo = call_valor("veiculos/getAgendamentoData?data_retirada="+data_retirada+"&data_entrega="+data_entrega+"&i_cidade_ibge="+i_cidade_ibge);
                var array = JSON.parse(campo);
                if (array != null) {
                    $('#tabe').removeClass("hidden");
                    $("#destino_igual").removeClass("hidden");
                    $("#lb_condutor").text(array.condutor);
                    $("#lb_destino").text(array.destino);
                    $("#lb_n_passageiros").text(array.n_passageiros);
                    $("#lb_status").text(array.status_aprov);
                }
            }, 300);
        });

        $("#hr_fim").blur(function(){
            setTimeout(function () {
                $('#tabe').addClass("hidden");
                $("#destino_igual").addClass("hidden");
                var data_retirada = $("#dt_inicio").val();
                var data_entrega = $("#dt_fim").val();
                var i_cidade_ibge = $('#i_cidade_ibge').select2('data').id;
                var hr_inicio = $("#hr_inicio").val();
                var hr_fim = $("#hr_fim").val();
                var campo = call_valor("veiculos/getAgendamentoData?data_retirada="+data_retirada+"&data_entrega="+data_entrega+"&i_cidade_ibge="+i_cidade_ibge);
                var array = JSON.parse(campo);
                if (array != null) {
                    $('#tabe').removeClass("hidden");
                    $("#destino_igual").removeClass("hidden");
                    $("#lb_condutor").text(array.condutor);
                    $("#lb_destino").text(array.destino);
                    $("#lb_n_passageiros").text(array.n_passageiros);
                    $("#lb_status").text(array.status_aprov);
                }
            }, 300);
        });


        $('#i_cidade_ibge').on("select2-blur", function (e) {
            $('#tabe').addClass("hidden");
            $("#destino_igual").addClass("hidden");
            setTimeout(function () {
                $('#tabe').addClass("hidden");
                $("#destino_igual").addClass("hidden");
                var data_retirada = $("#dt_inicio").val();
                var data_entrega = $("#dt_fim").val();
                var hr_inicio = $("#hr_inicio").val();
                var hr_fim = $("#hr_fim").val();
                var i_cidade_ibge = $('#i_cidade_ibge').select2('data').id;
                var campo = call_valor("veiculos/getAgendamentoData?data_retirada="+data_retirada+"&data_entrega="+data_entrega+"&i_cidade_ibge="+i_cidade_ibge);
                var array = JSON.parse(campo);
                if (array != null) {
                    $('#tabe').removeClass("hidden");
                    $("#destino_igual").removeClass("hidden");
                    $("#lb_condutor").text(array.condutor);
                    $("#lb_destino").text(array.destino);
                    $("#lb_n_passageiros").text(array.n_passageiros);
                    $("#lb_status").text(array.status_aprov);
                }
            }, 300);
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

    function getDetalhes(i_alocacao)
    {
        var retorno = $.base64.decode(call_valor('veiculos/getDetalhes?i_alocacao='+i_alocacao));
        $('#conteudo-detalhes').html(retorno);
    }
</script>
<div>
    <div class="row"><?php $this->load->view("interface_titulo", $titulo_interface)?></div>
    <div class="row">
         <button data-toggle='modal' data-target='#modal-agendamento' class='btn btn-default btn-sm'>
            <span class='glyphicon glyphicon-pencil'></span> Novo Agendamento
        </button>
    </div>
    <div class="row">
        <div class="tabbable tabs-left tabbable-bordered">
            <table id="tabela_agendamentos" class="table table-hover table-striped table_satc">
                <thead>
                  <tr>
                      <th class="text-center" width="5%"></th>
                      <th class="text-center" width="20%">Ve&iacute;culo</th>
                      <th class="text-center" width="10%">Data retirada</th>
                      <th class="text-center" width="10%">Data Entrega</th>
                      <th class="text-center" width="20%">Cidade</th>
                      <th width="20%">Motivo</th>
                      <th width="20%">Nº Passageiros</th>
                      <th class="text-center" width="10%">Situa&ccedil;&atilde;o</th>
                  </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#tabela_agendamentos').dataTable({
            "data": <?php echo $json_veiculos; ?>,
            "aoColumnDefs":
            [
                {
                    "sClass": "text-center",
                    "aTargets": [0,6,7]
                },
                {
                    "sClass": "text-center bold",
                    "aTargets": [2,3]
                },
                {
                    "aTargets": [7],
                    "mRender": function (data, type, full) {
                        var label = '';
                        if (full[8] == 'A') {
                            label = "<p class='font-icone'><i class='glyphicon glyphicon-ok-sign verde'></i></p>Aprovado";
                        } else if (full[8] == 'R') {
                            label = "<p class='font-icone'><i class='glyphicon glyphicon-remove vermelho'></i></p>Reprovado";
                        } else if (full[8] == 'D'){
                            label = "<p class='font-icone'><i class='glyphicon glyphicon-minus-sign amarelo'></i></p>Aguardando Ve&iacute;culo";
                        } else {
                            label = "<p class='font-icone'><i class='glyphicon glyphicon-minus-sign amarelo'></i></p>Aguardando Aprovação";
                        }
                        return label;
                    }
                }
            ],
            "fnCreatedRow":
            function(nRow, full, iDataIndex)
            {
                if (full[8] == 'R' || full[9] != null) {
                    nRow.title = full[9]==null||full[9]==" "?'Sem motivo.':'Motivo reprovação: '+ full[9];
                }
            }
        });
    });
</script>
<div class="modal fade" id="modal-detalhes" tabindex="-1" role="dialog" aria-labelledby="detalhes" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="detalhes">Informa&ccedil;&otilde;es</h4>
            </div>
            <div class="modal-body" id="conteudo-detalhes"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-agendamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Agendar Ve&iacute;culo</h4>
            </div>
            <div class="modal-body">
                <?php
                    echo satc_form_open(
                        'form_salvar_locacao',
                        'veiculos/setAgendamento',
                        'veiculos/index',
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
                                CHN n&atilde;o habilitada.<br><small></small>
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
                                <input type="text" name="dt_inicio" id="dt_inicio" maxlength="10" class="form-control input-sm mask_date" placeholder="Data" valida="1">
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
                                <input type="text" name="dt_fim" id="dt_fim" maxlength="10" class="form-control input-sm mask_date" placeholder="Data" valida="1">
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
                                <input type="hidden" name="i_cidade_ibge" id="i_cidade_ibge" select2>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="form-group">
                            <label for="i_unidade" class="col-sm-4 control-label">Unidade: </label>
                            <div class="col-sm-7">
                                <input type="hidden" name="i_unidade" id="i_unidade" select2>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row hidden" id="tabe" name="tabe">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped table-responsive">
                                    <thead style="background: #d3d3d3;">
                                        <th>Condutor</th>
                                        <th>Destino</th>
                                        <th class="text-center">N&ordm; Passageiros</th>
                                        <th class="text-center">Status</th>
                                    </thead>
                                    <tbody>
                                        <td><label id="lb_condutor" name="lb_condutor"></label></td>
                                        <td><label id="lb_destino" name="lb_destino"></label></td>
                                        <td class="text-center"><label id="lb_n_passageiros" name="lb_n_passageiros"></label></td>
                                        <td class="text-center"><label id="lb_status" name="lb_status"></label></td>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden" id="destino_igual" name="destino_igual">
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <div class="alert alert-warning alert-dismissible text-center" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Existe outra solicita&ccedil;&atilde;o de ve&iacute;culo para a mesma data e local.
                                </div>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
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