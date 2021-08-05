<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>lufa</title>
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.js"></script> -->
    <?php
      echo link_tag('assets/css/style.css');
      echo link_tag('node_modules/bootstrap/dist/css/bootstrap.min.css');
      echo link_js('node_modules/jquery/jquery.min.js');
      echo link_js('node_modules/bootstrap/dist/js/bootstrap.min.js');
      echo link_js('node_modules/angular/angular.min.js');
      echo link_js('node_modules/angular-locale-pt-br/angular-locale_pt-br.js');
      echo link_js('node_modules/angular-dir-pagination/dirPagination.js');
      echo link_js('node_modules/angular-br-filters.min.js');
      echo link_js('node_modules/chart.min.js');
      echo link_js('node_modules/angular-charts/angular-chart.js');
      echo link_tag('assets/css/ebro_select2.css');
      echo link_tag('assets/css/select2.css');
      echo link_js('node_modules/select2.min.js');
      echo link_js('node_modules/sweetalert.min.js');
      echo link_tag('node_modules/angularjs-datepicker/src/css/angular-datepicker.css');
      echo link_js('node_modules/angularjs-datepicker/src/js/angular-datepicker.js');
      /*echo link_js('node_modules/angular-sanitize.min.js');*/
      echo link_js('node_modules/masks.js');
      echo link_ng('app.js?v=1');
    ?>
    <style type="text/css">
      /*.container {
        margin-left: 45px !important;
      }*/
    </style>
  </head>
  <body>
    <main ng-app="loginApp">
       <?php
        $this->load->view('includes/sidebar');
        ?>
        <div class="container">
            <?php $this->load->view($page); ?>
        </div>
    </main>
  </body>
</html>