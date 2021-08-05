var app = angular.module('appVeiculosCombustivel', ['ngSanitize','ui.utils.masks','chart.js']);
app.controller('combustiveisController', ['$scope', '$http','$filter', function($scope, $http,$filter) {
    $scope.listarCombustiveis = [];
    $scope.detalhes = '0';
    $scope.interno = 'N';
    $scope.mensagem = '';
    $scope.dt_inicial = '';
    $scope.dt_final = '';

    $scope.getlistarCombustiveis = function () {
        $http({
            url: 'getlistarCombustiveisAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.listarCombustiveis = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    $scope.getlistarCombustiveis();

    $scope.cadCombustivel = function() {
        $scope.form_combustiveis.$setPristine();
        $("#i_unidade").select2("data",{id: '',text: ''});
        $('#i_unidade').select2('enable', true);
        $("#i_patrimonio").select2("data",{id: '',text: ''});
        $('#i_patrimonio').select2('enable', true);
        $('#anexo_cupom_fiscal').val('');
        $('#upload_').html('');
        $('#anexo_xml').val('');
        $('#filelist').html('');
        $('#uploadxml_').html('');
        $scope.combustiveis = {};
        $scope.combustiveis.combustivel = 'G';
        $scope.mensagem = '';
        $("#modalCadCombustivel").modal('show');
    }

    $scope.submitcombustiveis = function () {
        if ($scope.form_combustiveis.$valid) {
            /*if ($("#anexo_cupom_fiscal").val()=='') {
                alert('É necessário anexar um cupom fiscal');
                return false;
            }*/
            $scope.combustiveis.status = 'S';
            $scope.combustiveis.interno = $scope.interno;
            $scope.combustiveis.arquivo = $("#anexo_cupom_fiscal").val();
            $scope.combustiveis.i_unidade = $('#i_unidade').select2('data').id;
            $scope.combustiveis.i_patrimonio = $('#i_patrimonio').select2('data').id;
            $.post('setSalvarCombustivel', {combustivel:$scope.combustiveis}, function() {
                alert('Salvo com sucesso.');
                $scope.form_combustiveis.$setPristine();
                $('#anexo_cupom_fiscal').val('');
                $('#upload_').html('');
                $('#anexo_xml').val('');
                $('#uploadxml_').html('');
                $('#filelist').html('');
                $scope.dt_inicial = '';
                $scope.dt_final = '';
                $("#modalCadCombustivel").modal('hide');
                $scope.getTotaisCombustivel();
                $scope.getlistarCombustiveis();
            });
        } else {
            $scope.mensagem = 'É nescessário preencher todos os campos para salvar!';
        }
    }

    $scope.excluirCombustivel = function(combustivel,i_unidade) {
        if (confirm('Deseja realmente excluir?')) {
            $.post('excluirCombustivel', {i_combustivel:combustivel}, function() {
                alert('Excluido com sucesso.');
                $scope.getCombustiveisUnidade(i_unidade);
            });
        }
    }

    $scope.importarXml = function(arquivo) {
        $scope.xml = arquivo;
        $scope.dadosXml = {};
        $http({
            url: 'getXmlLeitura?arquivo='+$scope.xml,
            method: 'GET'
        }).then(function (retorno) {
            $scope.dadosXml = retorno.data;
            if ($scope.dadosXml != 1) {
                $scope.mensagem = '';
                $("#i_patrimonio").select2("data",{id: $scope.dadosXml.i_patrimonio,text: $scope.dadosXml.nome_veiculo});
                $('#i_patrimonio').select2('enable', true);
                $scope.combustiveis.arquivo = $scope.dadosXml.i_patrimonio;
                $scope.combustiveis.dt_abastecimento = $scope.dadosXml.dt_abastecimento;
                $scope.combustiveis.hr_abastecimento = $scope.dadosXml.hr_abastecimento;
                $scope.combustiveis.combustivel = $scope.dadosXml.combustivel;
                $scope.combustiveis.litros = $scope.dadosXml.litros;
                $scope.combustiveis.valor = $scope.dadosXml.valor;
                $scope.combustiveis.arquivo_xml = $scope.dadosXml.arquivo;
                $scope.interno = 'N';
            } else {
                $scope.mensagem = 'O Xml possui um CNPJ diferente da Satc';
            }
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.getCombustiveisUnidade = function (i_unidade) {
        $scope.combustivelUnidade = [];
        $http({
            url: 'getlistarCombustiveisUnidadeAjax?i_unidade='+i_unidade+'&dt_inicial='+$scope.dt_inicial+'&dt_final='+$scope.dt_final,
            method: 'GET'
        }).then(function (retorno) {
            $scope.combustivelUnidade = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.getTotaisCombustivel = function () {
        $scope.totalCombustivel = {};
        $http({
            url: 'getTotaisCombustivelAjax',
            method: 'GET'
        }).then(function (retorno) {
            $scope.totalCombustivel = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    $scope.getTotaisCombustivel();

    $scope.relatorioUnidade = function(listarCombustivel) {
        $scope.filtroCombU = '';
        $scope.getCombustiveisUnidade(listarCombustivel.i_unidade);
        $scope.detalhes = '1';
        $scope.unidade = listarCombustivel.nome_unidade;
        $scope.i_unidade = listarCombustivel.i_unidade;
        $scope.i_unidade_formatada = listarCombustivel.i_unidade_formatada;
    }

    $scope.voltar = function () {
        $scope.detalhes = '0';
        if (($scope.dt_inicial != '') && ($scope.dt_final != '')) {
            $scope.filtrarData($scope.dt_inicial, $scope.dt_final);
        } else {
            $scope.getlistarCombustiveis();
        }
    }

    $scope.getDownload = function (arquivo, xml = '') {
        redirect('VeiculosCombustivel/getCombustiveisDownload?arquivo='+arquivo+'&xml='+xml);
    }

    $scope.relatorioCombustivelUnidade = function(i_unidade) {
        window.open("../relatorios/abre_relatorio?url=VeiculosCombustivel/relatorioCombustivelUnidade?dados="+i_unidade+"^"+$scope.dt_inicial+"^"+$scope.dt_final);
    }

    $scope.relatorioCombustivel = function() {
        if ($scope.dt_inicial != '' && $scope.dt_final != '') {
            window.open("../relatorios/abre_relatorio?url=VeiculosCombustivel/relatorioCombustivel?dados="+$scope.dt_inicial+"^"+$scope.dt_final);
        } else {
            window.open("../relatorios/abre_relatorio?url=VeiculosCombustivel/relatorioCombustivel");
        }
    }

    $scope.filtrarData = function(dt_inicial, dt_final) {
        $scope.listarCombustiveis = [];
        $http({
            url: 'getListaCombustiveisFiltro?dt_inicial='+dt_inicial+'&dt_final='+dt_final,
            method: 'GET'
        }).then(function (retorno) {
            $scope.listarCombustiveis = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });

        $scope.totalCombustivel = {};
        $http({
            url: 'getTotaisCombustivelAjax?dt_inicial='+dt_inicial+'&dt_final='+dt_final,
            method: 'GET'
        }).then(function (retorno) {
            $scope.totalCombustivel = retorno.data;
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.graficoConsumo = function() {
        var obj = [];
        $scope.opcaoGrafico = '';
        $scope.labelCombustivelArea = [];
        $scope.dataCombustivelArea = {gasolina:[], diesel:[], etanol:[]};

        angular.copy($scope.listarCombustiveis, obj);
        angular.forEach(obj, function(value, key) {
            $scope.labelCombustivelArea.push(value['i_unidade_formatada']);
            $scope.dataCombustivelArea.gasolina.push(value['litros_g']);
            $scope.dataCombustivelArea.diesel.push(value['litros_d']);
            $scope.dataCombustivelArea.etanol.push(value['litros_a']);
        });
        $('#modalGraficoConsumo').modal('show');
    }
}]);