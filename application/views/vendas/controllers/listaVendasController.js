app.controller('listaVendasController',function($scope, $http, $filter, $location){
    $scope.listaVendas = [];
    $scope.itensCompras = [];
    $scope.tela = 1; // 1 = lista vendas, 2 = lista itens da venda

    var getlistaVendasAjax = function() {
        $scope.listaVendas = [];
        $http({
            url: 'getlistaVendasAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaVendas = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getlistaVendasAjax();

    $scope.cadVendas = function() {
        location.href = "cadVendas";
    }

    $scope.verItensVendas = function(venda) {
        $scope.itensVendas = [];
        $scope.venda = venda;
        $http({
            url: 'getlistaItensVendasAjax?id_venda='+venda.id_compra,
            method: 'GET'
        }).then(function (retorno) {
            $scope.tela = 2;
            $scope.itensVendas = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

});