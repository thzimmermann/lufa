<script type="text/javascript">
    $(function(){
        $("#i_usuario_multa").select2({
            minimumInputLength: 3,
            ajax: {
                url: "getColaboradorAjax",
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
    });
</script>
<style type="text/css">
    .mR-10 {margin-right: 10px;}
    .sizeIcon {font-size: 18px;vertical-align: sub;}
    .linkBut {background-color: #ececec;width: 35%;border: solid 1px #d8d8d8;padding: 12px 20px;-webkit-transition: 0.3s;transition: 0.3s;}
    .linkBut:hover {background-color: rgb(220, 220, 220);text-decoration: none;color: #000;}
</style>
<div class="modal fade" id="modalEditMulta" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{detalhesMulta.veiculo}}</h4>
            </div>
            <div class="modal-body">
                <form novalidate name="form_multa" id="form_multa">
                    <div class="alert alert-danger" ng-show="mensagem_erro">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{mensagem_erro}}
                    </div>
                    <div id="detalhes_corpo">
                        <table class="table table-condensed table-bordered table-striped">
                            <thead>
                                <tr >
                                    <td class="text-left"><b>Ve&iacute;culo:</b></td>
                                    <td colspan="3" ng-bind="detalhesMulta.veiculo"></td>
                                </tr>
                                <tr >
                                    <td class="text-left"><b>Cidade:</b></td>
                                    <td colspan="3" ng-bind="detalhesMulta.cidade"></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="text-left"><b>C&oacute;d. Infra&ccedil;&atilde;o: </b></td>
                                    <td width="25%" ng-bind="detalhesMulta.cod_infracao"></td>
                                    <td width="25%" class="text-left"><b>Data da Multa:</b></td>
                                    <td width="25%" >{{detalhesMulta.dt_multa}}  {{detalhesMulta.hr_multa}}</td>
                                </tr>
                                <tr >
                                    <td width="25%" class="text-left"><b>Gravidade: </b></td>
                                    <td width="25%" ng-bind="detalhesMulta.gravidade"></td>
                                    <td width="25%" class="text-left"><b>Pontos: </b></td>
                                    <td width="25%" ng-bind="detalhesMulta.pontos"></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="detalhes_corpo" ng-show="condutores.condutor_diaanterior.nome_condutor || condutores.condutor_dia.nome_condutor || condutores.condutor_diaapos.nome_condutor">
                        <table class="table table-condensed table-bordered table-striped">
                            <thead style="background-color: darkgray;">
                                <tr>
                                    <td class="text-center" colspan="3"><b>Condutores</b></td>
                                </tr>
                                <tr>
                                    <td class="text-center" width="33%" ng-show="condutores.condutor_diaanterior.dia"><b>{{condutores.condutor_diaanterior.dia}}</b></td>
                                    <td class="text-center" width="33%" ng-show="condutores.condutor_dia.dia"><b>{{condutores.condutor_dia.dia}}</b></td>
                                    <td class="text-center" width="33%" ng-show="condutores.condutor_diaapos.dia"><b>{{condutores.condutor_diaapos.dia}}</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" style="background-color: #d3d3d3;" ng-show="condutores.condutor_diaanterior.dia">{{condutores.condutor_diaanterior.nome_condutor}}</td>
                                    <td class="text-center" style="background-color: #d3d3d3;" ng-show="condutores.condutor_dia.dia">{{condutores.condutor_dia.nome_condutor}}</td>
                                    <td class="text-center" style="background-color: #d3d3d3;" ng-show="condutores.condutor_diaapos.dia">{{condutores.condutor_diaapos.nome_condutor}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="i_usuario_multa">Condutor:</label>
                            <input ng-model="detalhesMulta.i_usuario_multa" ng-click="chnUsuario()" select2 type="hidden" name="i_usuario_multa" id="i_usuario_multa">
                        </div>
                    </div>
                    <br>
                    <div class="row" ng-show="chn_imagem">
                        <div class="col-sm-12">
                            <a href="{{chn_imagem}}" target="_blank" class="linkBut"><span class="glyphicon glyphicon-picture sizeIcon mR-10"></span><b>Imagem da CNH</b></a>
                        </div>
                    </div>
                    <br ng-show="chn_imagem">
                    <div class="row text-left">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="status">Status da multa:</label>
                                <select name="status" id="status"
                                    class="form-control input-sm form-control"
                                    ng-model="detalhesMulta.status">
                                    <option value="A">Selecione uma op&ccedil;&atilde;o</option>
                                    <option value="V">Pendente com usuario</option>
                                    <!-- V = VERIFICANDO F = FECHADO -->
                                    <option value="F">Assinado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row text-left" ng-show="detalhesMulta.status=='F'">
                        <div class="col-sm-12">
                            <button id="pickfiles_itens_mult" type="button" class="btn btn-primary btn-sm">
                                <span class="glyphicon glyphicon-file"></span>
                                Anexar Assinatura
                            </button>
                            <br>
                            <small>(Tamanho maxímo de 10mb)</small>
                            <input type="text" class="hide" name="anexo_multa" id="anexo_multa">
                            <br><br>
                            <div class="row">
                                <ul id="upload_multa" ng-required="true"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary" id="salvar_multa" name="salvar_multa" ng-disabled="form_multa.$invalid">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>