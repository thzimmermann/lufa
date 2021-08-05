app.controller('gerenciadorPlantacaoController',function($scope, $http, $filter, $location){
    $scope.colors = ['#4564FF', '#FFFA00', '#FF0000', '#00FF04', '#FF0074'];
    $scope.filtroPlantacoes = [];
    $http({
        url: '../plantacao/getPlantacaoGeral',
        method: 'GET'
    }).then(function (retorno) {
        $scope.filtroPlantacoes = retorno.data;
    },
    function (retorno) {
        console.log('Error: '+retorno.status);
    });

    $scope.listaFornecedoresRazao = [];
    $http({
        url: '../plantacao/getlistaFornecedoresRazaoAjax',
        method: 'GET'
    }).then(function (retorno) {
        $scope.listaFornecedoresRazao = retorno.data;
        $scope.label_fornecedores = [];
        $scope.data1_fornecedores = [];
        $scope.data2_fornecedores = [];
        for (var i = 0; i < $scope.listaFornecedoresRazao.length; i++) {
            $scope.label_fornecedores.push($scope.listaFornecedoresRazao[i]['nome']);
            $scope.data1_fornecedores.push($scope.listaFornecedoresRazao[i]['vl_total_compras']);
            $scope.data2_fornecedores.push($scope.listaFornecedoresRazao[i]['vl_total_vendas']);
        }
        $scope.data_fornecedores = [];
        $scope.data_fornecedores = [$scope.data1_fornecedores, $scope.data2_fornecedores];
        $scope.series_fornecedores = ['Compras', 'Vendas'];
        $scope.options_fornecedores = {"legend": {    "display": true,  "position": "right"}};
    },
    function (retorno) {
        console.log('Error: '+retorno.status);
    });


    $scope.listaProdutosEstoque = [];
    $http({
        url: '../estoque/getProdutosEstoqueAjax',
        method: 'GET'
    }).then(function (retorno) {
        $scope.listaProdutosEstoque = retorno.data;
        $scope.label_estoques = [];
        $scope.data_estoques = [];
        for (var i = 0; i < $scope.listaProdutosEstoque.length; i++) {
            $scope.label_estoques.push($scope.listaProdutosEstoque[i]['nome']);
            $scope.data_estoques.push($scope.listaProdutosEstoque[i]['estoque_atual']);
        }
        $scope.series_estoques = ['Quantidade em estoque'];
        $scope.options_estoques = {"legend": {    "display": true,  "position": "right"}};
    },
    function (retorno) {
        console.log('Error: '+retorno.status);
    });

    $scope.labels = ["Arroz - Lote 1(12/01/2019)", "Feijão - Lote 2(10/10/2019)", "Arroz - Lote 3(23/10/2019)", "Arroz - Lote 1(16/10/2019)", "Arroz - Lote 2(18/10/2019)"];
    $scope.data = [100.10 ,200.33, -175, 300, 504.8];



    $scope.labels1 = ["Arroz - Lote 1(12/01/2019)", "Feijão - Lote 2(10/10/2019)", "Arroz - Lote 3(23/10/2019)", "Arroz - Lote 1(16/10/2019)", "Arroz - Lote 2(18/10/2019)"];
    $scope.data1 = [1200 ,3500, 0, 8600, 8200];
});