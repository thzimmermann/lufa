app.controller('cadFornecedorController',function($scope, $http, $filter, $location){

    $scope.dados = {};
    $scope.dados.tipo_empresa = 'J';

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
            url: 'getFornecedor?id='+id,
            method: 'GET'
        }).then(function (retorno) {
            $scope.dados = retorno.data;
            $("#id_cidade").select2("data",{id: $scope.dados.id_cidade,text: $scope.dados.cidade+' - '+$scope.dados.uf});
            $('#id_cidade').select2('enable', true);
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.submitFornecedor = function() {
        $.post('setSalvarFornecedor', {dados:$scope.dados}, function() {
            swal("Salvo com sucesso!", "", "success")
            .then((value) => {
              location.href='listaFornecedores';
            });
        });
    }

    $scope.voltar = function() {
        location.href='listaFornecedores';
    }
    /*$scope.submitMultas = function () {
        if ($scope.form_multas.$valid) {
            $scope.multas.i_cidade_ibge = $('#i_cidade_ibge').select2('data').id;
            $scope.multas.i_patrimonio = $('#i_patrimonio').select2('data').id;
            $.post('setSalvarMultas', {multas:$scope.multas}, function() {
                alert('Salvo com sucesso.');
                $scope.form_multas.$setPristine();
                $("#modalCadMultas").modal('hide');
                $scope.getlistarMultas();
            });
        } else {
            return false;
        }
    }*/
});