app.controller('listaUsuariosController',function($scope, $http, $filter, $location){
    var getUsuariosAjax = function() {
        $scope.listaUsuarios = [];
        $http({
            url: 'getlistaUsuariosAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaUsuarios = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    getUsuariosAjax();

    $scope.editarUsuario = function(id) {
        location.href='cadUsuarios?id='+id;
    }

    $scope.cadUsuarios = function() {
        location.href='cadUsuarios';
    }
});