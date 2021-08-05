app.controller('cadComprasController',function($scope, $http, $filter, $location){
    $scope.dados = {};
    $scope.itensCompras = {};
    $scope.itensComprasArray = [];

    $scope.calculaValorTotal = function() {
        if (($scope.itensCompras.qtde > 0) && ($scope.itensCompras.valor_uni > 0)) {
            $scope.itensCompras.valor_total = $scope.itensCompras.qtde*$scope.itensCompras.valor_uni;
        }
    }

    $scope.voltar = function() {
        location.href='listaCompras';
    }

    $scope.salvarItem = function() {
        if ($scope.itensCompras.id_produto && $scope.itensCompras.qtde && $scope.itensCompras.valor_uni && $scope.itensCompras.valor_total) {
            $http({
            url: '../produtos/getProduto?id='+$scope.itensCompras.id_produto,
            method: 'GET'
            }).then(function (retorno) {
                $scope.itensCompras.nome_produto = retorno.data.nome;
                $scope.itensComprasArray.push($scope.itensCompras);
                $scope.itensCompras = {};
                $("#id_produto").select2("data",{id: '',text: ''});
                $('#id_produto').select2('enable', true);
            },
            function (retorno) {
                swal("Item da compra", "Não foi possível adicionar!", "error")
            });
        } else {
            swal("Item da compra", "Não foi possível adicionar!", "error")
        }
    }

    $scope.submitCompras = function () {
        var mensagem = "";
        if (!$scope.dados.id_fornecedor) {
            mensagem = mensagem+"&#149;<span style='color:#bb3a3a'> Não foi identificado um fornecedor!</span><br>";
        }
        if (!$scope.dados.dt_compra) {
            mensagem = mensagem+"&#149;<span style='color:#bb3a3a'> Data de compra não preenchida!</span><br>";
        }
        if (!$scope.dados.nf) {
            mensagem = mensagem+"&#149;<span style='color:#bb3a3a'> Nº da NF não preenchida!</span><br>";
        }
        if ($scope.itensComprasArray.length == 0) {
            mensagem = mensagem+"&#149;<span style='color:#bb3a3a'> Compra sem itens adicionados!</span><br>"
        }
        if (mensagem != '') {
            var top = "<font>"+mensagem+"</font>";
            var elem = document.createElement("div");
            elem.innerHTML = top;
            swal({
                title: "Erro ao salvar compra",
                content: elem,
                icon: "error",
                button: "OK",
            });
            return false;
        }
        $scope.obj = {};
        $scope.obj.id_fornecedor = $scope.dados.id_fornecedor;
        $scope.obj.nf = $scope.dados.nf;
        $scope.obj.itens = [];
        $scope.obj.itens.push($scope.itensComprasArray);
        var mes = parseInt($scope.dados.dt_compra.getMonth())+1;
        $scope.dt_compra = $scope.dados.dt_compra.getFullYear()+'/'+mes+'/'+$scope.dados.dt_compra.getDate();
        $scope.obj.dt_compra = $scope.dt_compra;
        $.post('setSalvarCompra', {dados:$scope.obj}, function() {
            swal("Salvo com sucesso!", "", "success")
            .then((value) => {
              location.href='listaCompras';
            });
        });
    }
});
