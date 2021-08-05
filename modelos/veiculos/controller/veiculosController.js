 var app = angular.module('appVeiculos', ['ngSanitize']);
app.controller('gerenciamentoVeiculosController', ['$scope', '$scope', '$http','$filter', function($scope, $scope, $http,$filter) {
    $scope.documentos_detalhes = '0';
    $scope.listaDocumentos = [];
    $scope.listaVeiculos = [];
    $scope.getListaVeiculos = function () {
        $http({
            url: 'getListaVeiculosAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaVeiculos = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.cadDocumentos = function(i_patrimonio) {
        $scope.form_documentos.$setPristine();
        $scope.documentos = {};
        $scope.documentos.i_patrimonio = i_patrimonio;
        $("#modalCadDocumentos").modal('show');
    }

    $scope.setSalvarDocumentos = function () {
        if ($scope.form_documentos.$valid) {
            if ($("#anexo_documento").val()=='') {
                alert('É necessário anexar um documento');
                return false;
            }
            $scope.documentos.arquivo = $("#anexo_documento").val();
            $.post('setSalvarDocumentos', {documentos:$scope.documentos}, function() {
                alert('Salvo com sucesso.');
                $scope.form_documentos.$setPristine();
                $('#anexo_documento').val('');
                $('#upload_').html('');
                $("#modalCadDocumentos").modal('hide');
                $scope.getlistaDocumentos($scope.documentos.i_patrimonio);
            });
        } else {
            return false;
        }
    }

    $scope.getlistaDocumentos = function (i_patrimonio) {
        $scope.listaDocumentos = [];
        $http({
            url: 'getlistaDocumentosAjax?i_patrimonio='+i_patrimonio,
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.listaDocumentos = retorno.data;
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.listarDocumentos = function (listaVeiculo) {
        $scope.filtroDoc = '';
        $scope.getlistaDocumentos(listaVeiculo.i_patrimonio);
        $scope.i_patrimonio = listaVeiculo.i_patrimonio;
        $scope.modelo = listaVeiculo.marca_modelo;
        $scope.placa = listaVeiculo.placa;
        $scope.documentos_detalhes = '1';
    }

    $scope.getDownload = function (arquivo) {
        redirect('veiculos/getDocumentosDownload?arquivo='+arquivo);
    }
    $scope.getListaVeiculos();

    $scope.voltar = function () {
        $scope.documentos_detalhes = '0';
        $scope.getListaVeiculos();
    }
}]);

$(document).ready(function() {
    function changeValueData(element) {
        angular.element(element).triggerHandler('input');
    }
});