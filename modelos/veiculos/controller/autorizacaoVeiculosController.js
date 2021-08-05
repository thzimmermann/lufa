var app = angular.module('appAutorizacao', ['ngSanitize','ui.utils.masks','angular.filter']);
app.controller('autorizacaoVeiculosController', ['$scope', '$http','$filter', function($scope, $http,$filter) {
    $scope.detalhes = 0;
    $scope.verObservacoesVeiculos = function() {
        $scope.detalhes = 1;
        $scope.listaVeiculoProblemas = [];
        $http({
            url: 'getlistaVeiculoProblemasAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaVeiculoProblemas = retorno.data;
            $scope.total = $scope.listaVeiculoProblemas.length;
            $scope.$watch("betweenDate", function(query) {
                $scope.total = $filter("filter")($scope.listaVeiculoProblemas, query).length;
            });
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.voltar = function(n) {
      if (n == 1) {
        $scope.getSolicitacoesUsuarios();
      }
      if (n == 2) {
        $scope.verObservacoesVeiculos();
      }
      $scope.detalhes = n;
    }

    $scope.detalhesCarro = function(i_patrimonio) {
        $scope.i_patrimonio = i_patrimonio;
        $scope.listaProblemasVeiculo = [];
        $http({
            url: 'getProblemasVeiculoAjax?i_patrimonio='+i_patrimonio,
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaProblemasVeiculo = retorno.data;
            $scope.placa = $scope.listaProblemasVeiculo[0].placa;
            $scope.veiculo = $scope.listaProblemasVeiculo[0].veiculo;
            $scope.total = $scope.listaProblemasVeiculo.length;
            $scope.$watch("betweenDate", function(query) {
                $scope.total = $filter("filter")($scope.listaProblemasVeiculo, query).length;
          });
        },
        function (retorno) {
          console.log('Error: '+retorno.status);
        });
        $scope.detalhes = 2;
    }

    $scope.getSolicitacoesUsuarios = function() {
        $scope.listaSolicitacoesUsuarios = [];
        $http({
            url: 'getlistaSolicitacoesUsuariosAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaSolicitacoesUsuarios = retorno.data;
            $scope.total = $scope.listaSolicitacoesUsuarios.length;
            $scope.$watch("betweenDate", function(query) {
                $scope.total = $filter("filter")($scope.listaSolicitacoesUsuarios, query).length;
            });
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    $scope.getSolicitacoesUsuarios();

    $scope.cadastroAutorizacao = function(listaSolicitacoes) {
        $scope.autorizacao = listaSolicitacoes;
        $("#modalAutorizacaoVeiculos").modal('show');
    }

    $scope.salvarAutorizacao = function(aut) {
        aut.nivel_combustivel = $('#nivel_combustivel').val();
        $.post('setSalvarAutorizacao', {autorizacao:aut}, function() {
            alert('Salvo com sucesso.');
            $('#problemas_veiculo').val('');
            $('#nivel_combustivel').val('');
            $("#modalAutorizacaoVeiculos").modal('hide');
            $scope.getSolicitacoesUsuarios();
        });
    }


    $scope.cadSolucaoProblema = function(listaVeiculos) {
        $scope.veiculo = listaVeiculos.placa+' - '+listaVeiculos.veiculo
        $scope.solucaoProblema = {i_autorizacao:listaVeiculos.i_autorizacao,i_patrimonio:listaVeiculos.i_patrimonio, problemas:listaVeiculos.problemas, solucao:listaVeiculos.solucao};
        $("#modalSolucaoProblemaVeiculos").modal('show');
    }

    $scope.salvarSolucao = function(solucaoProblema) {
        $.post('setSalvarSolucao', {solucaoProblema:solucaoProblema}, function() {
            alert('Salvo com sucesso.');
            $scope.detalhesCarro(solucaoProblema.i_patrimonio);
            $("#modalSolucaoProblemaVeiculos").modal('hide');
        });
    }

    $scope.imprimirPdfAutorizacao = function(listaSolicitacoes) {
        window.open("../relatorios/abre_relatorio?url=veiculos/imprimirAutorizacao?i_alocacao="+listaSolicitacoes.i_alocacao);
    }

    $scope.relatorioPDF = function(i_patrimonio) {
        console.log(i_patrimonio);
        window.open("../relatorios/abre_relatorio?url=veiculos/relatorioProblemasSolucoes?i_patrimonio="+i_patrimonio);
    }
}]);
app.filter('betweenDate', function($filter) {
   return function(collection, column, startDate, endDate) {
        var new_collection = [];
    if (angular.isDefined(startDate) && angular.isDefined(endDate)) {
       if (startDate != '' && endDate != '') {
          if (angular.isDefined(startDate)) {
             startDate = Date.parse($filter('date')(startDate, 'yyyy-MM-dd HH:mm:ss'));             
          }
          if (angular.isDefined(endDate)) {
             endDate = (Date.parse($filter('date')(endDate, 'yyyy-MM-dd HH:mm:ss')) + 86400000); // Soma um dia, pois compara as horas também ...
          }
          if (!isNaN(startDate) && !isNaN(endDate)) {
              angular.forEach(collection, function (value, index) {
              var obj = value[column];
              var currentDate = Date.parse($filter('date')(obj, 'yyyy-MM-dd HH:mm:ss'));
                 if ((currentDate >= startDate &&  endDate >= currentDate)) {
                    new_collection.push(value);
                 }
              });
          } else {
            new_collection = collection;
          }
      } else {
        new_collection = collection;
      }
      collection = new_collection;
    }
  return collection;
  };
});