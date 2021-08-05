app.controller('listaMovimentacoesController',function($scope, $http, $filter, $location){

    $scope.getParam = function(name) {
        const results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if(!results){
            return 0;
        }
        return results[1] || 0;
    }

    if ($scope.getParam('id') > 0) {
        $scope.id_produto = $scope.getParam('id');

        $scope.dadosProduto = {};
        $http({
            url: '../produtos/getProduto?id='+$scope.id_produto,
            method: 'GET'
        }).then(function (retorno) {
            $scope.dadosProduto = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });

        $scope.filtroAnos = [];
        $http({
            url: 'getAnoMovimentacoes/'+$scope.id_produto,
            method: 'GET'
        }).then(function (retorno) {
            $scope.filtroAnos = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });

        $scope.listaMovimentacoes = [];
        $http({
            url: 'getMovimentacoesProduto/'+$scope.id_produto,
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaMovimentacoes = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.voltar = function() {
        location.href='controleEstoque';
    }
});
