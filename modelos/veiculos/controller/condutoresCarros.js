var app = angular.module('appCondutoresCarros', ['ngSanitize']);
app.controller('condutoresCarrosController', ['$scope', '$scope', '$http','$filter', function($scope, $scope, $http,$filter) {
    $scope.filtroCondCar = '';
    $scope.detalhes = '0';
    $scope.listaCondutorCarros = [];
    $scope.getCondutoresCarro = function() {
        $scope.listaCondutorCarros = [];
        $http({
            url: 'getCondutoresCarro',
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.listaCondutorCarros = retorno.data;
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    $scope.getCondutoresCarro();

    $scope.voltar = function() {
        $scope.detalhes = '0';
    }

    $scope.verAlocacoes = function(condutorCarro) {
        $scope.detalhes = '1';
        $scope.filtroAlocacoes = '';
        $scope.condutor = condutorCarro.nome_condutor;
        $scope.listaAlocacoes = [];
        $http({
            url: 'getAlocacoesCondutor?i_usuario='+condutorCarro.i_usuario,
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.listaAlocacoes = retorno.data;
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.verMotivo = function(justificativa) {
        $scope.justificativa = justificativa;
        $("#modalMotivoAlocacao").modal('show');
    }
}]);