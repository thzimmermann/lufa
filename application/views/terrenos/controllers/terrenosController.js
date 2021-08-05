app.controller('terrenosController',function($scope, $http, $filter, $location){

    var getListaTerrenos = function() {
        $scope.listaTerrenos = [];
        $http({
            url: 'getListaTerrenosAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaTerrenos = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getListaTerrenos();

    /*$scope.editarFornecedor = function(id) {
        location.href = "cadFornecedor?id="+id;
    }*/

    $scope.cadTerreno = function() {
        /*location.href = "cadTerrenos";*/
    }
});