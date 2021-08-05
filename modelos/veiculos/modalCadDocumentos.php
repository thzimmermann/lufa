<script type="text/javascript">
    function changeValueData(element) {
        angular.element(element).triggerHandler('input');
    }
</script>
<style>
    .form-group-error a.select2-choice {
        border:1px solid #FF0000;
    }
</style>
<div class="modal fade" id="modalCadDocumentos" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Gerenciar de Documentos</h4>
            </div>
            <div class="modal-body">
                <form novalidate name="form_documentos" id="form_documentos">
                    <div class="alert alert-danger" ng-if="form_documentos.$submitted && form_documentos.$error.required">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        &Eacute; necess&aacute;rio preencher a Data de Validade e/ou a Descrição.
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="dt_vencimento">Data de Validade:</label>
                                <div data-date-autoclose="true" data-date-format="dd/mm/yyyy" class="input-group date ebro_datepicker">
                                    <input type="text" name="dt_vencimento" ng-model="documentos.dt_vencimento" id="dt_vencimento" maxlength="" class="form-control input-sm mask_date form-control ng-pristine ng-untouched ng-valid-maxlength ng-valid ng-valid-required" onkeydown="changeValueData(this)" onkeyup="changeValueData(this)" ng-required="true">
                                    <span class="input-group-addon icon_addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="descricao" class="control-label">Descrição do Documento:</label>
                                <textarea name="descricao" cols="40" rows="3" id="descricao"
                                    maxlength="500" class="form-control input-sm  ng-pristine ng-valid ng-valid-maxlength ng-touched"
                                    ng-required="true" ng-model="documentos.descricao">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row text-left">
                        <div id="container_itens">
                            <div class="col-sm-12">
                                <button id="pickfiles_itens" type="button" class="btn btn-primary btn-sm">
                                    <span class="glyphicon glyphicon-file"></span>
                                    Anexar Documento
                                </button>
                                <br>
                                <small>
                                    (Tamanho maxímo de 10mb)
                                </small>
                                <input type="text" class="hide" name="anexo_documento" id="anexo_documento">
                                <br><br>
                                <div class="row">
                                    <ul id="upload_"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary" id="salvar" ng-disabled="form_documentos.$invalid">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
