var app = angular.module('loginApp', ['angularUtils.directives.dirPagination','idf.br-filters','ui.utils.masks', 'chart.js','720kb.datepicker']);

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

app.directive("formatDate", function(){
  return {
   require: 'ngModel',
    link: function(scope, elem, attr, modelCtrl) {
      modelCtrl.$formatters.push(function(modelValue){
        return new Date(modelValue);
      })
    }
  }
})
