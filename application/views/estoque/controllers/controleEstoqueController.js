app.controller('controleEstoqueController',function($scope, $http, $filter, $location){

    var getProdutosEstoque = function() {
        $scope.listaProdutos = [];
        $http({
            url: 'getProdutosEstoqueAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaProdutos = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getProdutosEstoque();

    $scope.listaMovimentacoes = function(id_produto) {
        location.href = "listaMovimentacoes?id="+id_produto;
    }
});