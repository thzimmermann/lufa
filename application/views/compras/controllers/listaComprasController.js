app.controller('listaComprasController',function($scope, $http, $filter, $location){
    $scope.listaCompras = [];
    $scope.itensCompras = [];
    $scope.tela = 1; // 1 = lista Compras, 2 = lista itens da compra

    var getlistaComprasAjax = function() {
        $scope.listaCompras = [];
        $http({
            url: 'getlistaComprasAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaCompras = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getlistaComprasAjax();

    $scope.cadCompras = function() {
        location.href = "cadCompras";
    }

    $scope.verItensCompras = function(compra) {
        console.log(compra.id_compra);
        $scope.itensCompras = [];
        $scope.compra = compra;
        $http({
            url: 'getlistaItensComprasAjax?id_compra='+compra.id_compra,
            method: 'GET'
        }).then(function (retorno) {
            $scope.tela = 2;
            $scope.itensCompras = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

});