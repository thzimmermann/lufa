app.controller('listaFornecedoresController',function($scope, $http, $filter, $location){

    var getFornecedoresAjax = function() {
        $scope.listaFornecedores = [];
        $http({
            url: 'getlistaFornecedoresAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaFornecedores = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getFornecedoresAjax();

    $scope.editarFornecedor = function(id) {
        location.href = "cadFornecedor?id="+id;
    }

    $scope.cadFornecedor = function() {
        location.href = "cadFornecedor";
    }
});