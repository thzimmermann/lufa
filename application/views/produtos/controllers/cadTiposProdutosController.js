app.controller('cadTiposProdutosController',function($scope, $http, $filter, $location){
    $scope.dados = {};

    $scope.getParam = function( name ) {
        const results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if(!results){
            return 0;
        }
        return results[1] || 0;
    }

    if ($scope.getParam('id') > 0) {
        var id = $scope.getParam('id');
        $scope.dados = {};
        $http({
            url: 'getTipoProduto?id='+id,
            method: 'GET'
        }).then(function (retorno) {
            $scope.dados = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.submitTipoProdutos = function() {
        $.post('setSalvarTipoProdutos', {dados:$scope.dados}, function() {
            swal("Salvo com sucesso!", "", "success")
            .then((value) => {
              location.href='listaTiposProdutos';
            });
        });
    }

    $scope.voltar = function() {
        location.href='listaTiposProdutos';
    }
});