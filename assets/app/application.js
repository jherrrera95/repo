var app = angular.module('systemApp',[]);

app.controller("ventasController",function($scope,$http){

	//globales
	$scope.plazos;
	$scope.tasa;
	$scope.porcentaje;
	$scope.ventas = [];  
	$scope.articulo_id;
	$scope.cliente_id;
	//Variables de combo
	$scope.articulos = [];
	$scope.clientes = [];
	//variables de la venta
	$scope.articulos_venta=[];
	$scope.abonos=[];
	$scope.abonoSeleccionado={};
	$scope.venta_enganche=0;
	$scope.venta_bonificacion=0;
	$scope.venta_total=0; 
	$scope.total_bruto=0;
	$scope.total_importe=0;
	$scope.precio_contado=0;
	$scope.folio="";
	//Datos de articulo y cliente actuales
	$scope.curr_client;
	$scope.curr_articulo;
	//Expresiones

	$scope.getVentas = function(){  
		$scope.ventas=[];
		$http.get(base_url+'index.php/ventas/get').then(function(r){
			$scope.ventas=r.data;
		});
	} 

	$scope.nextStep = function(){
		if(isNaN($("#cmb_clientes").val()) || $scope.articulos_venta.length==0){
			alert("Los datos ingresados no son correctos, favor de verificar");
		}else{
			if($("#parte1").css("display")=="none"){
				var op = $('#form_abonos input[name=op_abono]:checked').val();
				if(op!=undefined)
					$scope.guardar(op);
				else 
					alert("Debe seleccionar un plazo para realizar el pago de su compra.");
			}else{
				$scope.abonos = [];
				$("#parte1").css({"display":"none"});
				$("#parte2").css({"display":"block"});
				$scope.precio_contado=$scope.venta_total/(1+(($scope.tasa*$scope.plazos)/100));
				for(var i=3; i<=12; i+=3){
					$scope.abonos.push({
						n_abonos:i,
						total_pagar:($scope.precio_contado*(1+(($scope.tasa*i)/100))).toFixed(2),
						cantidad_abono:(($scope.precio_contado*(1+(($scope.tasa*i)/100)))/i).toFixed(2),
						ahorro:($scope.venta_total-$scope.precio_contado*(1+(($scope.tasa*i)/100))).toFixed(2)
					});
				}
			}
		}
	}

	$scope.addProduct = function(){
		if(isNaN($("#cmb_articulos").val()) || isNaN($("#cmb_clientes").val())){
			alert("Seleccione un cliente y un articulo");
		}
		else{
			if($scope.getExistencia($scope.curr_articulo.id)>0){
				//Setear datos de configuracion
				var existe=false;
				for(var i=0; i<$scope.articulos_venta.length; i++){
					if($scope.articulos_venta[i].id == $scope.curr_articulo.id){
						alert("El producto ya se encuentra agregado");
						existe=true; 
					}
				}
				if(!existe){
					$scope.plazos=parseInt($scope.curr_articulo.plazo_maximo);
					$scope.tasa=parseFloat($scope.curr_articulo.tasa_financiamiento); 
					$scope.porcentaje=parseFloat($scope.curr_articulo.porcentaje_enganche);
					$scope.total_bruto+=parseFloat($scope.curr_articulo.precio);
					var importe = $scope.curr_articulo.precio*(1+(($scope.tasa*$scope.plazos)/100));
					$scope.articulos_venta.push({
						tasa_financiamiento:$scope.curr_articulo.tasa_financiamiento,
						plazo_maximo:$scope.curr_articulo.plazo_maximo,
						porcentaje_enganche:$scope.curr_articulo.porcentaje_enganche,
						id:$scope.curr_articulo.id, 
						descripcion:$scope.curr_articulo.descripcion, 
						modelo:$scope.curr_articulo.modelo, 
						precio:importe.toFixed(2), 
						importe:importe.toFixed(2),
						cantidad:1
					});
					$scope.calcularTotales();
				}
			}
			else {  
				alert("El articulo no cuenta con existencia, favor de verificar.");
			}
		}
	}

	$scope.validarTexto=function($event,obj){
		if($event.currentTarget.value==0 || $event.currentTarget.value==""){
			event.currentTarget.value=1;
			obj.cantidad=1;
			$scope.changeRowPrice($event,obj);
		}
	}

	$scope.changeRowPrice = function($event,obj){
		if(isNaN($event.key)){
			//if($event.key=="Backspace") ke=0;
			$event.currentTarget.value = $event.currentTarget.value.replace($event.key,'');
			//if($event.currentTarget.value.trim()=="")$event.currentTarget.value=1;
		} 
		var existencia = $scope.getExistencia(obj.id);
		var cantidad = $event.currentTarget.value;
		if(cantidad>existencia){		
			$event.currentTarget.value=existencia;
			cantidad=existencia;
			var importe = obj.precio*cantidad;
			obj.importe=importe.toFixed(2); 
			obj.cantidad=existencia;
			alert("Existencia insuficiente para el producto: "+obj.descripcion+", existencia actual: "+existencia);
		}else{
			var importe = obj.precio*cantidad;
			obj.cantidad=cantidad;  
			obj.importe=importe.toFixed(2);
		}
		$scope.calcularTotales();
	}

	$scope.calcularTotales = function(){
			$scope.total_importe=0;
			for(var i=0; i<$scope.articulos_venta.length; i++){
				var obj = $scope.articulos_venta[i];
				$scope.total_importe += parseFloat(obj.importe);
			} 
			console.log($scope.porcentaje, $scope.tasa, $scope.plazos, $scope.total_importe);
			$scope.venta_enganche = (($scope.porcentaje/100)*$scope.total_importe).toFixed(2);
			$scope.venta_bonificacion = ($scope.venta_enganche*(($scope.tasa*$scope.plazos)/100)).toFixed(2);
			$scope.venta_total = ($scope.total_importe-$scope.venta_enganche-$scope.venta_bonificacion).toFixed(2);
			console.log($scope.total_importe);
	}

	$scope.removeSaleArticle = function($event,obj){
		if(confirm("Confirmar eliminación")){ 
			d = $event.currentTarget;
			var cantidad = angular.element(d).parent().parent().find("input").val();
			var index = $scope.articulos_venta.indexOf(obj);
			$scope.total_bruto-= cantidad*obj.precio; 
	  		$scope.articulos_venta.splice(index, 1);
	  		$scope.calcularTotales();
  		}  
	}

	$scope.getClientes = function(){ 
		$http.get(base_url+'index.php/clientes/combo').then(function(r){
			$scope.clientes=r.data;
		});
	}

	$scope.setClientData = function(){ 
		//try{
			var rf=$("#cmb_clientes").select2().find(":selected")[0].dataset;
			var actual=JSON.parse(rf.obj);			
			/*console.log(rf);
			$("#cmb_clientes").select2("destroy");
			$('#cmb_clientes option[value="' + rf.id + '"]').text(rf.clave+" - "+actual.value);
			$("#cmb_clientes").select2();*/
			$scope.curr_client=rf;
			$scope.rfc="RFC: "+rf.rfc;
		/*}catch(err){

		}*/
	}

	$scope.getExistencia = function($id){
		var exist = 0;
		$.ajax({
			url:base_url+'index.php/articulos/checarExistencia/'+$id,
			data:{},
			async:false,
			type:"get",
			success:function(r){
				exist = parseInt(r[0].existencia);
			}
		});
		return exist;
	}

	$scope.setArticleData = function(){
		try{
			var rf=$("#cmb_articulos").select2().find(":selected")[0].dataset;
			$scope.curr_articulo=rf;
		}catch(err){

		}
	}

	$scope.getArticulos = function(){ 
		$http.get(base_url+'index.php/articulos/combo').then(function(r){
			$scope.articulos=r.data; 
		});
	}

	$scope.guardar = function(pla){
		var venta = {}
		for(var i=0; i<$scope.abonos.length; i++){
			if($scope.abonos[i].n_abonos == pla){
				var obj = $scope.abonos[i];
				venta.cliente_id=$("#cmb_clientes").val();
				venta.plazo=pla;
				venta.abono=obj.cantidad_abono;
				venta.total_pagar=obj.total_pagar;
				venta.ahorro=obj.ahorro;
			}
		}

		$.ajax({
			url:base_url+'index.php/ventas/guardar',
			type:"post",
			data: {venta: venta,detalle: $scope.articulos_venta},
			success:function(){
				$scope.getVentas();
				alert("Su venta ha sido registrada correctamente");
				$scope.reset();
			}
		});
		
	}  

	$scope.generarFolio = function(){
		$http.get(base_url+'index.php/ventas/generarFolio').then(function(r){
			$scope.folio=r.data[0].folio;
		});
	}

	$scope.cancelar = function(){
		if($("#parte1").css("display")=="block"){
			$scope.reset();
		}
		if($("#parte1").css("display")=="none"){ 
			$("#parte1").css({"display":"block"});
			$("#parte2").css({"display":"none"});
		}
	}

	$scope.cancelarDirecto = function(){
		$scope.reset();
	}

	$scope.reset = function(){
		$scope.plazos;
		$scope.tasa;
		$scope.porcentaje;
		$scope.articulo_id;
		$scope.cliente_id;
		//Variables de combo
		//variables de la venta
		$scope.articulos_venta=[];
		$scope.abonos=[];
		$scope.abonoSeleccionado={};
		$scope.venta_enganche=0;
		$scope.venta_bonificacion=0;
		$scope.venta_total=0; 
		$scope.total_bruto=0;
		$scope.total_importe=0;
		$scope.precio_contado=0;
		//Datos de articulo y cliente actuales
		$scope.curr_client;
		$scope.curr_articulo;
		$("#modal_venta").hide();
		$("#parte1").css({"display":"block"});
		$("#parte2").css({"display":"none"});
	}
/*
	$("#cmb_clientes").on("select2:open",function(){
	    // On focus, always retain the full state name
	    $("#cmb_clientes").select2("destroy");
	    for(var i=0; i<$scope.clientes.length; i++){
			$('#cmb_clientes option[value="' + $scope.clientes[i].id + '"]').text($scope.clientes[i].value);
	    }
		$("#cmb_clientes").select2();
	});
	*/
});