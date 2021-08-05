<?php
echo link_tag('assets/css/styleSidebar.css');
$this->load->view('plantacao/modalCotacoes');
?>
<script type="text/javascript">
	$(document).ready(function () {
		var trigger = $('.hamburger'),
				overlay = $('.overlay'),
			 isClosed = false;

			trigger.click(function () {
				hamburger_cross();
			});

			function hamburger_cross() {

				if (isClosed == true) {
					overlay.hide();
					trigger.removeClass('is-open');
					trigger.addClass('is-closed');
					isClosed = false;
				} else {
					overlay.show();
					trigger.removeClass('is-closed');
					trigger.addClass('is-open');
					isClosed = true;
				}
		}

		$('[data-toggle="offcanvas"]').click(function () {
					$('#wrapper').toggleClass('toggled');
		});
	});
</script>
<div id="wrapper">
	<div class="overlay"></div>
	<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
			<ul class="nav sidebar-nav">
					<li class="sidebar-brand">
						<a href="../plantacao/gerenciarPlantacao">
							lufa
						</a>
					</li>
					<!-- <li>
						<a href="#" data-toggle="modal" data-target="#basicModal">Cotações em tempo real</a>
					</li> -->
					<li>
						<a href="../plantacao/listaPlantacao">Plantação</a>
					</li>
					<li>
						<a href="../estoque/controleEstoque">Controle de Estoque</a>
					</li>
					<li>
						<a href="../utilizacoes/cadUtilizacoes">Utilizações</a>
					</li>
					<li>
						<a href="../produtos/listaProdutos">Produtos</a>
					</li>
					<li>
						<a href="../compras/listaCompras">Compras</a>
					</li>
					<li>
						<a href="../vendas/listaVendas">Vendas</a>
					</li>
					<li>
						<a href="../fornecedores/listaFornecedores">Fornecedores</a>
					</li>
					<li>
						<a href="../terrenos/listaTerrenos">Terrenos(Lavoura)</a>
					</li>
					<li>
						<a href="../usuarios/listaUsuarios">Usuários</a>
					</li>
					<!-- <li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Works <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li class="dropdown-header">Dropdown heading</li>
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li> -->
			</ul>
	</nav>
	<div id="page-content-wrapper">
			<button type="button" class="hamburger is-closed" data-toggle="offcanvas">
					<span class="hamb-top"></span>
					<span class="hamb-middle"></span>
					<span class="hamb-bottom"></span>
			</button>
	</div>
</div>
