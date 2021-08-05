var app = angular.module('appAprovacaoEmergencial', ['ngSanitize']);
app.controller('aprovacaoEmergencialController', ['$scope', '$http','$filter', function($scope, $http,$filter) {
    $scope.filtroCondCar = '';
    $scope.detalhes = '0';
    $scope.listaVeiculos = [];
    $scope.aba = 'P';
    $scope.dt_inicial = '';
    $scope.dt_final = '';

    $scope.buscarVeiculos = function(i_alocacao, data_inicial, data_final) {
        $("#i_alocacao").val(i_alocacao);
        $scope.dados = {};
        $scope.dados.i_alocacao = i_alocacao;
        $scope.dados.status_aprov = 'A';
        $http({
            url: 'getListaVeiculosGrupo?data_inicial='+data_inicial+'&data_final='+data_final,
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaVeiculos = retorno.data;
            $("#veiculosSelect2").select2();
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.alterarAba = function(aba) {
        $scope.aba = aba;
    }

    $scope.alterarFiltro = function(item) {
        if ($scope.aba != 'P') {
            if (item.emergencial == 'S') {
                return item;
            }
        } else {
            return item;
        }
    }

    $scope.listarAgendamentos = function() {
        $scope.listaAgendamentos = [];
        $http({
            url: 'getVeiculosAgendadosAjax',
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.listaAgendamentos = retorno.data;
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.numerosAgendamentos = function() {
        $scope.numeroAgendamentos = [];
        $scope.statusA = 0;
        $scope.statusP = 0;
        $scope.statusR = 0;
        $http({
            url: 'getNumerosAgendamentos',
            method: 'GET'
        }).then(function (retorno) {
            if (retorno.status == 200) {
                $scope.numeroAgendamentos = retorno.data;
                for (var i = 0; i < $scope.numeroAgendamentos.length; i++) {
                    if ($scope.numeroAgendamentos[i].status_aprov == 'P') {
                        $scope.statusP = $scope.numeroAgendamentos[i].total;
                    }
                    if ($scope.numeroAgendamentos[i].emergencial == 'S') {
                        if ($scope.numeroAgendamentos[i].status_aprov == 'A') {
                            $scope.statusA = $scope.numeroAgendamentos[i].total;
                        }
                        if ($scope.numeroAgendamentos[i].status_aprov == 'R') {
                            $scope.statusR = $scope.numeroAgendamentos[i].total;
                        }
                    }
                }
            };
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }
    $scope.listarAgendamentos();
    $scope.numerosAgendamentos();

    $scope.voltar = function() {
        $scope.detalhes = '0';
    }

    $scope.salvarResposta = function(dados, i_patrimonio) {
        if (i_patrimonio == undefined) {
            dados.i_patrimonio = 1;
            dados.status_aprov = 'D';
            dados.emergencial = 'S';
            $.post('setAprovacaoVeiculos', {dados:dados}, function() {
                alert('Salvo com sucesso.');
                window.open('aprovacaoEmergencial','_self');
            });
        } else {
            dados.i_patrimonio = i_patrimonio;
            dados.emergencial = 'S';
            $.post('setAprovacaoVeiculos', {dados:dados}, function() {
                alert('Salvo com sucesso.');
                window.open('aprovacaoEmergencial','_self');
            });
        }
    }

    $scope.responderEmergencial = function(listaVeiculo) {
        $scope.buscarVeiculos(listaVeiculo.i_alocacao, listaVeiculo.dt_alocacao_ini, listaVeiculo.dt_alocacao_fim);
        $scope.listarAgendamentos();
        $scope.sumir = 'M';
        $("#modalRespondeEmergencial").modal('show');
    }

    $scope.escondeEsconde = function(sumir) {
        $scope.sumir = sumir;
    }

    $scope.filtrarData = function(dt_inicial, dt_final) {
        $scope.listaAgendamentos = [];
        $http({
            url: 'getVeiculosAgendadosAjax?dt_inicial='+dt_inicial+'&dt_final='+dt_final,
            method: 'GET'
        }).then(function (retorno) {
            $scope.listaAgendamentos = retorno.data;
            $scope.numeroAgendamentos = [];
            $scope.statusA = 0;
            $scope.statusP = 0;
            $scope.statusR = 0;
            $http({
                url: 'getNumerosAgendamentos?dt_inicial='+dt_inicial+'&dt_final='+dt_final,
                method: 'GET'
            }).then(function (retorno) {
                if (retorno.status == 200) {
                    $scope.numeroAgendamentos = retorno.data;
                    for (var i = 0; i < $scope.numeroAgendamentos.length; i++) {
                        if ($scope.numeroAgendamentos[i].status_aprov == 'P') {
                            $scope.statusP = $scope.numeroAgendamentos[i].total;
                        }
                        if ($scope.numeroAgendamentos[i].emergencial == 'S') {
                            if ($scope.numeroAgendamentos[i].status_aprov == 'A') {
                                $scope.statusA = $scope.numeroAgendamentos[i].total;
                            }
                            if ($scope.numeroAgendamentos[i].status_aprov == 'R') {
                                $scope.statusR = $scope.numeroAgendamentos[i].total;
                            }
                        }
                    }
                };
            },
            function (retorno) {
                console.log('Error: '+retorno.status);
            });
        },
        function (retorno) {
            console.log('Error: '+retorno.status);
        });
    }

    $scope.relatorioAprovacoesEmergenciais = function() {
        window.open("../relatorios/abre_relatorio?url=Veiculos/relatorioAprovacaoEmergencial?dados="+$scope.dt_inicial+"^"+$scope.dt_final);
    }
}]);