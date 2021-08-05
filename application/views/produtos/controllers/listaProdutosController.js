app.controller('listaProdutosController',function($scope, $http, $filter, $location){
    var getProdutosAjax = function() {
        $scope.listaProdutos = [];
        $http({
            url: 'getlistaProdutosAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaProdutos = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getProdutosAjax();

    $scope.editarProduto = function(id) {
        location.href='cadProdutos?id='+id;
    }

    $scope.listaTiposProdutos = function() {
        location.href='listaTiposProdutos';
    }

    $scope.cadProdutos = function() {
        location.href='cadProdutos';
    }
});