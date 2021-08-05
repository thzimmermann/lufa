<style>
    .form-group-error a.select2-choice {
        border:1px solid #FF0000;
    }
</style>
<div class="modal fade" id="modalMotivoAlocacao" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Justificativa</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <textarea name="justificativa" cols="40" rows="3" id="justificativa"
                                maxlength="800" class="form-control input-sm  ng-pristine ng-valid ng-valid-maxlength ng-touched"
                                ng-model="justificativa" disabled>
                            </textarea>
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
</div>
