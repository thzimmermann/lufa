app.controller('loginController',function($scope,$http, $location){
    $scope.login = {};
    $scope.dados = {};
	$scope.setAutenticacao = function() {
        $scope.login = $scope.dados;
        $scope.login.senha = btoa($scope.login.senha);
        $http({
            url: 'setAutenticacao',
            method: 'POST',
            data: $scope.login
        }).then(function (retorno) {
            if (retorno.data == 1) {
                swal({title:"Autenticado com sucesso!", icon:"success",button: "Acessar"}).then((value) => {
                  location.href='../plantacao/gerenciarPlantacao';
                });

            } else {
                var elem = document.createElement("div");
                elem.innerHTML = "<font>&#129300 Usuário e/ou senha incorretos</font>";
                swal({
                    title: "Autenticação falhou!",
                    content: elem,
                    icon: "warning",
                    button: "Tente novamente",
                });
            }

        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
	}
});