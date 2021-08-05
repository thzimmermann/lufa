<div class="modal fade" id="modalSolucaoProblemaVeiculos" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4>Problema/Solução</h4>
            </div>
            <div class="modal-body">
                <form novalidate name="form_problema_solucao" id="form_problema_solucao">
                    <div class="alert alert-danger" ng-show="mensagem_erro">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="problemas_veiculo">Problema: </label>
                                <textarea name="problemas_veiculo" cols="40" rows="6" id="problemas_veiculo"
                                    maxlength="800" class="form-control input-sm" disabled
                                    ng-model="solucaoProblema.problemas">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="solucao_veiculo">Solu&ccedil;&atilde;o: </label>
                                <textarea name="solucao_veiculo" cols="40" rows="6" id="solucao_veiculo"
                                    maxlength="800" class="form-control input-sm"
                                    ng-model="solucaoProblema.solucao">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="salvar_manutencao" ng-click="salvarSolucao(solucaoProblema)">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>