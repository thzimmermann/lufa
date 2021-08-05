app.controller('listaPlantacaoController',function($scope, $http, $filter, $location){

    var getPlantacoesAjax = function() {
        $scope.listaPlantacoes = [];
        $http({
            url: 'getlistaPlantacoesAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaPlantacoes = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getPlantacoesAjax();

    $scope.editarPlantacao = function(id) {
        location.href = "cadPlantacao?id="+id;
    }

    $scope.cadPlantacao = function() {
        location.href = "cadPlantacao";
    }
});