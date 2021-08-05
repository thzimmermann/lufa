app.controller('cadUtilizacoesController',function($scope, $http, $filter, $location){

    $scope.dados = {};
    $scope.dados.status = 'S';
    $scope.qtde_max = 0;
    $scope.submitUtilizacao = function() {
        var mes = parseInt($scope.dados.dt_utilizacao.getMonth())+1;
        $scope.dt_utilizacao = $scope.dados.dt_utilizacao.getFullYear()+'/'+mes+'/'+$scope.dados.dt_utilizacao.getDate();
        $scope.dados.dt_utilizacao = $scope.dt_utilizacao;

        $.post('setSalvarUtilizacao', {dados:$scope.dados}, function() {
            swal("Salvo com sucesso!", "", "success")
            .then((value) => {
              location.href='cadUtilizacoes';
            });
        });
    }

    $scope.changeProduto = function() {
        $http({
            url: '../estoque/getEstoqueProduto/'+$scope.dados.id_produto,
            method: 'GET'
        }).then(function (retorno) {
            $scope.qtde_max = retorno.data.qtde;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    /*$scope.voltar = function() {
        location.href='lista';
    }*/
});