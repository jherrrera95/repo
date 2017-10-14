var app = angular.module('profileApp',[], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    });


app.controller("userController", function($scope){
	$scope.message = 'hello';
});

app.controller("publicacionesController",function($scope,$http){
	$scope.titulo;
	$scope.descripcion;
	$scope.publicaciones;
	$scope.tiposPublicaciones; 
	$scope.tipo_publicacion_id;

	$scope.initializePublicaciones = function(){
		$http.get('/getTipoPublicaciones').then(function(r){
			$scope.tiposPublicaciones=r.data;
		});

		$http.get('/getPublicaciones').then(function(r){ 
			$scope.publicaciones=r.data;
		});
	}

})

app.controller("accountsController", function($scope,$http){
	$scope.servidor;
	$scope.ctrl_cuentas = [];
	$scope.id=0;
	$scope.accountTypes=[];

	$scope.initializeAccounts = function(){
		$http.get('/getAccounts').then(function(r){
			$scope.accountTypes=r.data;
			console.log(r.data);
		});

		$http.get('/getSavedAccounts').then(function(r){ 
			$scope.ctrl_cuentas=r.data;
			console.log(r.data);
		});
	}

	$scope.guardar = function(){
		//$scope.ctrl_cuentas.push({id:$scope.id, servidor:$scope.servidor});
		$http.post('/saveAccount', {account_type_id:$scope.servidor})
        .then(function (data, status, headers, config) {
        	console.log(data); 
        	$scope.ctrl_cuentas=data.data;
        },
        function (data, status, header, config) {
            
        });
		
		//console.log($scope.ctrl_cuentas);
	}

	$scope.borrar = function(){

	}

	$scope.actualizar = function(){

	}
});