app.controller('cadPlantacaoController',function($scope, $http, $filter, $location){

    $scope.dados = {};
    $scope.dados.status = 'S';

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
            url: 'getPlantacao?id='+id,
            method: 'GET'
        }).then(function (retorno) {
            $scope.dados = retorno.data;
            $scope.dados.dt_plantacao = $scope.dados.data_plantacao;
            $scope.dados.dt_estimativa_colheita = $scope.dados.data_estimativa_colheita;
            $("#id_cultivo").select2("data",{id: $scope.dados.id_cultivo,text: $scope.dados.item_cultivo});
            $('#id_cultivo').select2('enable', true);
            $("#id_terreno").select2("data",{id: $scope.dados.id_terreno,text: $scope.dados.desc_terreno});
            $('#id_terreno').select2('enable', true);
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.submitPlantacao = function() {
        $.post('setSalvarPlantacao', {dados:$scope.dados}, function() {
            swal("Salvo com sucesso!", "", "success")
            .then((value) => {
              location.href='listaPlantacao';
            });
        });
    }

    $scope.voltar = function() {
        location.href='listaPlantacao';
    }
});