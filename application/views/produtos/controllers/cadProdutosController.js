app.controller('cadProdutosController',function($scope, $http, $filter, $location){
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
            url: 'getProduto?id='+id,
            method: 'GET'
        }).then(function (retorno) {
            $scope.dados = retorno.data;
            $("#id_tipo_produto").select2("data",{id: $scope.dados.id_tipo_produto,text: $scope.dados.tipo_produto});
            $('#id_tipo_produto').select2('enable', true);
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.submitProdutos = function() {
        $.post('setSalvarProdutos', {dados:$scope.dados}, function() {
            swal("Salvo com sucesso!", "", "success")
            .then((value) => {
              location.href='listaProdutos';
            });
        });
    }

    $scope.voltar = function() {
        location.href='listaProdutos';
    }
});