var app = angular.module('appVeiculosMultas', ['ngSanitize']);
app.controller('multasVeiculosController', ['$scope', '$scope', '$http','$filter', function($scope, $scope, $http,$filter) {
    $scope.listarMultas = [];
    $scope.getlistarMultas = function () {
        $http({
            url: 'getlistarMultasAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listarMultas = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    $scope.getlistarMultas();

    $scope.cadMultas = function() {
        $scope.form_multas.$setPristine();
        $("#i_cidade_ibge").select2("data",{id: '',text: ''});
        $('#i_cidade_ibge').select2('enable', true);
        $("#i_patrimonio").select2("data",{id: '',text: ''});
        $('#i_patrimonio').select2('enable', true);
        $scope.multas = {};
        $("#modalCadMultas").modal('show');
    }

    $scope.submitMultas = function () {
        if ($scope.form_multas.$valid) {
            $scope.multas.i_cidade_ibge = $('#i_cidade_ibge').select2('data').id;
            $scope.multas.i_patrimonio = $('#i_patrimonio').select2('data').id;
            $.post('setSalvarMultas', {multas:$scope.multas}, function() {
                alert('Salvo com sucesso.');
                $scope.form_multas.$setPristine();
                $("#modalCadMultas").modal('hide');
                $scope.getlistarMultas();
            });
        } else {
            return false;
        }
    }

    $scope.getDownload = function (arquivo) {
        redirect('veiculosMultas/getAnexosDownload?arquivo='+arquivo);
    }

}]);