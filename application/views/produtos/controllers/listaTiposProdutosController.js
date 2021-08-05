app.controller('listaTiposProdutosController',function($scope, $http, $filter, $location){
    var getTiposProdutosAjax = function() {
        $scope.listaTipoProdutos = [];
        $http({
            url: 'getTiposProdutosAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaTipoProdutos = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getTiposProdutosAjax();

    $scope.editarTipoProduto = function(id) {
        location.href='cadTipoProdutos?id='+id;
    }

    $scope.cadTipoProdutos = function() {
        location.href='cadTipoProdutos';
    }

    $scope.voltar = function() {
        location.href='listaProdutos';
    }
});