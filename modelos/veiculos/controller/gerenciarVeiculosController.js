var app = angular.module('appVeiculos', ['ngSanitize','ui.utils.masks','angular.filter','chart.js']);
app.directive('somentenumeros', function () {
    return {
      require: 'ngModel',
      restrict: 'A',
      link: function (scope, element, attr, ctrl) {
        function inputValue(val) {
          if (val) {
            var numeros = val.replace(/[^0-9]/g, '');
            if (numeros !== val) {
              ctrl.$setViewValue(numeros);
              ctrl.$render();
            }
            return parseInt(numeros,10);
          }
          return '';
        }
        ctrl.$parsers.push(inputValue);
      }
    };
});
app.directive('select2', function() {
  return {
    restrict: 'A',
    require: 'ngModel',
    link: function(scope, element, attr, ngModel) {
      $('#'+attr.id).select2({
        minimumInputLength: attr.minl,
        ajax: {
          url: attr.urlajax,
          dataType: 'json',
          data: function (term, page) {
            return {
                busca: term
            };
          },
          results: function (data, page) {
            var obj = data;
            return {
              results: obj
            };
          }
        }
      });
      element.on('change', function() {
        scope.$apply(function(){
          ngModel.$setViewValue($('#'+attr.id).val());
        });
      });
      ngModel.$render = function() {
        element.value = ngModel.$viewValue;
      }
    }
  }
});

app.controller('gerenciamentoVeiculosController', ['$scope','$http','$filter', function($scope, $http,$filter) {
    $scope.detalhes = '0';
    $scope.listaDocumentos = [];
    $scope.listaVeiculos = [];
    $scope.listaCondutorCarros = [];
    $scope.multas = {};
    $scope.formRelatorio = {i_veiculo : '' , i_unidade : '', dt_inicial : '', dt_final : ''};

    $scope.getListaVeiculos = function () {
        $http({
            url: 'getListaVeiculosAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaVeiculos = retorno.data;
            $scope.total = $scope.listaVeiculos.length;
            $scope.$watch("betweenDate", function(query) {
                $scope.total = $filter("filter")($scope.listaVeiculos, query).length;
            });
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.verSinistro = function (listaVeiculo) {
        //location.href('veiculos/')
    }

    $scope.cadDocumentos = function(i_patrimonio) {
        $scope.documentos = {};
        $scope.documentos.i_patrimonio = i_patrimonio;
        $("#modalCadDocumentos").modal('show');
    }

    $scope.setSalvarDocumentos = function () {
        if ($scope.form_documentos.$valid) {
            if ($("#anexo_documento").val()=='') {
                alert('É necessário anexar um documento');
                return false;
            }
            $scope.documentos.arquivo = $("#anexo_documento").val();
            $.post('setSalvarDocumentos', {documentos:$scope.documentos}, function() {
                alert('Salvo com sucesso.');
                $scope.form_documentos.$setPristine();
                $('#anexo_documento').val('');
                $('#upload_').html('');
                $("#modalCadDocumentos").modal('hide');
                $scope.getlistaDocumentos($scope.documentos.i_patrimonio);
            });
        } else {
            return false;
        }
    }

    $scope.getlistaDocumentos = function (i_patrimonio) {
        $scope.listaDocumentos = [];
        $http({
            url: 'getlistaDocumentosAjax?i_patrimonio='+i_patrimonio,
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.listaDocumentos = retorno.data;
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.listarDocumentos = function (listaVeiculo) {
        $scope.filtroDoc = '';
        $scope.getlistaDocumentos(listaVeiculo.i_patrimonio);
        $scope.i_patrimonio = listaVeiculo.i_patrimonio;
        $scope.modelo = listaVeiculo.marca_modelo;
        $scope.placa = listaVeiculo.placa;
        $scope.detalhes = '1';
    }

    $scope.getlistaUtilizacoes = function(i_patrimonio) {
        $scope.listaUtilizacoes = [];
        $http({
            url: 'getlistaUtilizacoesAjax?i_patrimonio='+i_patrimonio,
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.listaUtilizacoes = retorno.data;
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.listarUtilizacoes = function(listaVeiculo) {
        $scope.filtroUti = '';
        $scope.getlistaUtilizacoes(listaVeiculo.i_patrimonio);
        $scope.i_patrimonio = listaVeiculo.i_patrimonio;
        $scope.modelo = listaVeiculo.marca_modelo;
        $scope.placa = listaVeiculo.placa;
        $scope.detalhes = '5';
    }

    $scope.verMotivo = function(justificativa) {
        $scope.justificativa = justificativa;
        $("#modalMotivoAlocacao").modal('show');
    }

    $scope.multarPendentes = function(i_patrimonio) {
        $scope.listaMultas = [];
        $http({
            url: 'getlistaMultasAjax?i_patrimonio='+i_patrimonio,
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.listaMultas = retorno.data;
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.listarMultas = function (listaVeiculo) {
        $scope.filtroMult = '';
        $scope.multarPendentes(listaVeiculo.i_patrimonio);
        $scope.i_patrimonio = listaVeiculo.i_patrimonio;
        $scope.modelo = listaVeiculo.marca_modelo;
        $scope.placa = listaVeiculo.placa;
        $scope.detalhes = '2';
    }


    $scope.combustivelPerCarro = function(i_patrimonio) {
        $scope.listaCombustiveis = [];
        $http({
            url: 'getlistaCombustiveisAjax?i_patrimonio='+i_patrimonio,
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.listaCombustiveis = retorno.data;
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.listarCombustiveis = function (listaVeiculo) {
        $scope.combustivelPerCarro(listaVeiculo.i_patrimonio);
        $scope.i_patrimonio = listaVeiculo.i_patrimonio;
        $scope.modelo = listaVeiculo.marca_modelo;
        $scope.placa = listaVeiculo.placa;
        $scope.detalhes = '4';
    }

    $scope.editarMulta = function(listaMulta) {
        //$scope.form_multa.$setPristine();
        $scope.form_multa = {};
        $scope.form_multa.i_patrimonio = listaMulta.i_patrimonio;
        $scope.detalhesMulta = listaMulta;
        $scope.form_multa.i_multa = listaMulta.i_multa;

        $scope.condutores = [];
        $http({
            url: 'getCondutoresAnteriorAjax?i_patrimonio='+listaMulta.i_patrimonio+'&dt_multa='+listaMulta.dt_multa,
            method: 'GET'
        }).then(function (retorno) {
            $scope.condutores = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });

        if ($scope.detalhesMulta.i_usuario_multa != '') {
            $("#i_usuario_multa").select2("data",{id: listaMulta.i_usuario_multa,text: listaMulta.nome_usuario});
        } else {
            $("#i_usuario_multa").select2("data",{id: listaMulta.i_usuario_sugerido,text: listaMulta.nome_usuario_sugerido});
        }
        $scope.chnUsuario();
        $('#i_usuario_multa').select2('enable', true);
        $("#modalEditMulta").modal('show');
    }

    $scope.setSalvarMultas = function () {
        $scope.multas.i_multa = $scope.detalhesMulta.i_multa;
        $scope.mensagem_erro = '';
        $scope.multas.status = $scope.detalhesMulta.status;
        $scope.multas.i_patrimonio = $scope.detalhesMulta.i_patrimonio;

        if ($scope.multas.status == 'P') {
            if ($('#i_usuario_multa').select2('data').id == 0){
                $scope.mensagem_erro = 'Selecione um colaborador para mudar o status para Pendente.';
                return false;
            }
        }
        if ($scope.multas.status == 'F') {
            if ($('#i_usuario_multa').select2('data').id == 0){
                $scope.mensagem_erro = 'Selecione um colaborador para mudar o status para Fechado.';
                return false;
            }

            if ($("#anexo_multa").val()=='') {
                $scope.mensagem_erro = 'Faça um upload da Assinatura para mudar o status para Fechado.';
                return false;
            }
        }
        $scope.multas.arquivo = $("#anexo_multa").val();
        $scope.multas.i_usuario_multa = $('#i_usuario_multa').select2('data').id;
        $.post('setSalvarMultas', {multas:$scope.multas}, function() {
            alert('Salvo com sucesso.');
            $("#i_usuario_multa").select2("data",{id: '',text: ''});
            $('#anexo_multa').val('');
            $('#upload_multa').html('');
            $("#modalEditMulta").modal('hide');
            $scope.multas.dt_multa = $scope.detalhesMulta.dt_multa;
            $.post('setEnviarEmailMulta', {dados:$scope.multas}, function() {
                console.log('email enviado');
            });
            $scope.multarPendentes($scope.multas.i_patrimonio);
        });
    }

    $scope.getDownload = function (arquivo) {
        redirect('veiculos/getDocumentosDownload?arquivo='+arquivo);
    }
    $scope.getListaVeiculos();

    $scope.listarManutencoes = function (listaVeiculo) {
        $scope.filtroManu = '';
        $scope.getManutencoes(listaVeiculo.i_patrimonio);
        $scope.i_patrimonio = listaVeiculo.i_patrimonio;
        $scope.modelo = listaVeiculo.marca_modelo;
        $scope.placa = listaVeiculo.placa;
        $scope.detalhes = '3';
    }

    $scope.getManutencoes = function(i_patrimonio) {
        $scope.listaManutencoes = [];
        $http({
            url: 'getlistaManutencoesAjax?i_patrimonio='+i_patrimonio,
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaManutencoes = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.cadManutencao = function(i_patrimonio) {
        $scope.manutencoes = {};
        $scope.manutencoes.i_patrimonio = i_patrimonio;
        $scope.mensagem_erro = '';
        $("#modalCadManutencao").modal('show');
    }

    $scope.submitManutencao = function() {
        if ($scope.form_manutencoes.$valid) {
            /*if ($("#anexo_manutencao").val()=='') {
                $scope.mensagem_erro = 'Faça um upload de arquivo para Salvar.';
                return false;
            }*/
            $scope.manutencoes.arquivos = $("#anexo_manutencao").val();
            $.post('setSalvarManutencoes', {manutencoes:$scope.manutencoes}, function() {
                alert('Salvo com sucesso.');
                $scope.form_manutencoes.$setPristine();
                $('#anexo_manutencao').val('');
                $('#upload_manu_').html('');
                $("#modalCadManutencao").modal('hide');
                $scope.mensagem_erro = '';
                $scope.getManutencoes($scope.manutencoes.i_patrimonio);
            });
        } else {
            return false;
        }
    }

    $scope.getArquivosManutencao = function(modelo, i_manutencao) {
        redirect('veiculos/getlistaArquivosManutencao?i_manutencao='+i_manutencao+'&modelo='+modelo);
    }

    $scope.verDescricao = function (listaManutencao) {
        $scope.exibir = listaManutencao;
        $("#modaldescricaoManutencao").modal('show');
    }

    $scope.voltar = function () {
        $scope.detalhes = '0';
        $scope.getListaVeiculos();
    }

    $scope.chnUsuario = function () {
        $scope.i_usuario = $('#i_usuario_multa').select2('data').id;
        $http({
            url: 'imagemCNH?i_usuario='+$scope.i_usuario,
            method: 'GET'
        }).then(function (retorno) {
            $scope.chn_imagem = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.geraGraficoKmRodado = function() {
        $("#modalRelatorios").modal("hide");
        $http({
            url: 'graficoKmRodado?dados='+$scope.formRelatorio.i_unidade+'^'+$scope.formRelatorio.dt_inicial+'^'+$scope.formRelatorio.dt_final,
            method: 'GET'
        }).then(function (retorno) {
            $scope.graficoKmRodado = {};
            $scope.graficoKmRodado = retorno.data;
            $("#modalGraficoKmRodado").modal("show");
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    };

    $scope.relatorioKmRodado = function(tipo) {
        console.log(tipo);
        var filtro = '';
        if (tipo == 'V') {
            filtro = $scope.formRelatorio.i_veiculo;
        } else {
            filtro = $scope.formRelatorio.i_unidade;
        }
        window.open("../relatorios/abre_relatorio?url=Veiculos/relatorioKmRodado?dados="+tipo+"^"+filtro+"^"+$scope.formRelatorio.dt_inicial+"^"+$scope.formRelatorio.dt_final);
    }

    $scope.relatorioCombustivel = function() {
        window.open("../relatorios/abre_relatorio?url=Veiculos/relatorioCombustivelConsumo?dados="+$scope.formRelatorio.dt_inicial+"^"+$scope.formRelatorio.dt_final);
    }

    $scope.relatorioManutencao = function() {
        var tipo = '';
        if ($scope.formRelatorio.manPreventiva==true && $scope.formRelatorio.manCorretiva==true) {
            tipo = '';
        } else if ($scope.formRelatorio.manPreventiva==true) {
            tipo = 'P';
        } else if ($scope.formRelatorio.manCorretiva==true) {
            tipo = 'C';
        } else if ($scope.formRelatorio.geral==true){
            tipo = '';
        }

        window.open("../relatorios/abre_relatorio?url=Veiculos/relatorioManutencao?dados="+tipo+"^"+$scope.formRelatorio.dt_inicial+"^"+$scope.formRelatorio.dt_final+"^"+$scope.formRelatorio.i_veiculo);
    }

    $scope.relatorioCombustivelCarro = function(i_patrimonio) {
        window.open("../relatorios/abre_relatorio?url=VeiculosCombustivel/relatorioCombustivelCarro?i_patrimonio="+i_patrimonio);
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
             endDate = Date.parse($filter('date')(endDate, 'yyyy-MM-dd HH:mm:ss'));
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
