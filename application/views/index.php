<?php $this->load->view("layouts/header"); ?>
<body>
<?php $this->load->view("layouts/nav"); ?> 

<div class="container" ng-app="systemApp">
	<div class="row" id="ventas" style="display:none;" ng-controller="ventasController">
		<div class="col-md-2"></div>
		<div class="col-md-10" ng-init="getVentas()">
	<button style="margin:5px;"onclick="$('#modal_venta').show();" ng-click="generarFolio()" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-plus"></span>
 Nueva venta</button>
			<table class="table table-striped table-hover table-bordered table-responsive scrolled_main">
				<thead>
					<th>Folio venta</th>
					<th>Clave cliente</th>
					<th>Nombre</th> 
					<th>Total</th>
					<th>Fecha</th>
					<th>Estatus</th>
				</thead> 
				<tbody>
					<tr ng-repeat="v in ventas">
						<td>{{v.folio}}</td>
						<td>{{v.clave_cliente}}</td>
						<td>{{v.nombre}}</td>
						<td>{{v.total_pagar}}</td>
						<td>{{v.fecha}}</td>
						<td>{{v.status}}</td>
					</tr>
				</tbody>
			</table>
		</div>
	<!-- Modal nueva venta -->
		<div class="modal" id="modal_venta">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" id="btn_cerrar_modal" class="close" ng-click="cancelarDirecto()" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		       	<h1 class="modal-title">Agregar venta</h1>
		      </div>
		      <div class="modal-body" style="overflow-y: auto;"> 
		      <div id="parte1">
		      			<div class="row">
		      					<div class="col-md-12" ng-init="generarFolio()"><span class="pull-right" style="color:green; font-weight:bold;">Folio venta: {{folio}}</span></div>
					      		<div class="col-md-6">
					      		<label for="cliente">Cliente</label>
						      	<select id="cmb_clientes" style="width:100%" ng-click="comboReturn()" ng-change="setClientData()" list="clientes" ng-model='cliente_id' ng-init="getClientes()"/>
								  	<option ng-repeat="c in clientes" data-id="{{c.id}}" data-nombre="{{c.nombre}}" data-apellido_p="{{c.apellido_p}}" data-apellido_m="{{c.apellido_m}}" data-status="{{c.status}}" data-clave="{{c.clave}}" data-rfc='{{c.rfc}}' data-obj='{{c}}' data-test='hola' value="{{c.id}}">{{c.value}}</option>
								</select> 
						      	</div>  
						      	<div class="col-md-6">
							      	<div class="col-md-12"><label for=""></label></div>
							      	<div class="col-md-12">
							      		<span>{{rfc}}</span>			      		
							      	</div>
						      	</div>
					      </div> 
					      <div class="row">
								<div class="col-md-6">
					      		<label for="articulo">Articulo</label>
						        <select type="text" id="cmb_articulos" ng-change="setArticleData()" style="width:100%" list="articulos" ng-model='articulo_id' ng-init="getArticulos()"/>
									<option ng-repeat="a in articulos" data-id="{{a.id}}" data-descripcion="{{a.descripcion}}" data-modelo="{{a.modelo}}" data-tasa_financiamiento="{{a.tasa_financiamiento}}" data-porcentaje_enganche="{{a.porcentaje_enganche}}" data-plazo_maximo="{{a.plazo_maximo}}" data-precio="{{a.precio}}" data-status="{{a.status}}" value="{{a.id}}">{{a.value}}</option>
								</select>
								</div>
						      	<div class="col-md-6">
						      		<div class="col-md-12"><label for=""></label></div>
						      		<div class="col-md-12">
						      			<button class="btn btn-primary" ng-click="addProduct()" style="margin:5px;"><span class="glyphicon glyphicon-plus"></button>
						      		</div>
						      	</div>
						      	
					      </div>
					      <div class="row">
					      		<table class="table table-responsive table-striped table-hover table-bordered table-condensed scrolled" >
									<thead class="thead-inverse">
										<th>Descripcion</th>
										<th>Modelo</th>
										<th>Cantidad</th> 
										<th>Precio</th>
										<th>Importe</th>
										<th></th>
									</thead> 
									<tbody>
										<tr ng-repeat="av in articulos_venta">
											<td>{{av.descripcion}}</td>
											<td>{{av.modelo}}</td>
											<td><input type="text" ng-blur="validarTexto($event,av)" ng-pattern="onlyNumbers" style="width:100%;" ng-keyup="changeRowPrice($event,av)" value="{{av.cantidad}}" class="form_control"></td>
											<td>{{av.precio}}</td>
											<td>{{av.importe}}</td>
											<td><center><button ng-click="removeSaleArticle($event,av)" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle 
					"></span></button></center></td>
										</tr>
									</tbody>
								</table>
					      	
					      </div>
					      <div class="row">
					      		<div class="col-md-4"></div>
					      		<div class="col-md-8">
					      			<div class="row">
					      				<div class="col-md-6"><span class="pull-right info"> Enganche: </span> </div>
					      				<div class="col-md-6"><span>{{venta_enganche}}</span></div>
					      			</div>
					      			<div class="row">
					      				<div class="col-md-6"><span class="pull-right info"> Bonificación enganche: </span></div>
					      				<div class="col-md-6"><span>{{venta_bonificacion}}</span></div>
					      			</div>
					      			<div class="row">
					      				<div class="col-md-6"><span class="pull-right info"> Total: </span> </div>
					      				<div class="col-md-6"><span>{{venta_total}}</span></div>
					      			</div>
					      		</div>
					      </div>
		      </div>
		      <div id="parte2" style="display:none;">
		      <div style="background-color: purple; color:white; height:auto; padding:10px;">
		      <center><h3>Abonos mensuales</h3></center>
		      </div>
		      		<div class="row">
		      			<div class="col-md-12">
		      			<form action="" id="form_abonos">
		      				<table class="table-bordered table-striped table-hover table-condensed table-responsive">
		      				<thead>
		      				
		      				</thead>
		      				<tbody>
		      					<tr ng-repeat="ab in abonos">
		      						<td>{{ab.n_abonos}} abonos de</td>
		      						<td>$ {{ab.cantidad_abono}}</td>
		      						<td>Total a pagar ${{ab.total_pagar}}</td>
		      						<td>Se ahorra ${{ab.ahorro}}</td>
		      						<td><center><input type="radio" value="{{ab.n_abonos}}" name="op_abono"></center></td>
		      					</tr>
		      				</tbody>
		      				</table>
		      			</form>
		      			</div>
		      		</div>
		      </div>
		      
		      </div>
		      <div class="modal-footer">
		        <button type="button" id="btn_cancelar" ng-click="cancelar()" class="btn btn-danger">Cancelar</button>
		        <button type="button" id="btn_siguiente" ng-click="nextStep()" class="btn btn-success" data-dismiss="modal">Siguiente</button>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
	
</div>
</body>
<?php $this->load->view("layouts/footer"); ?>
<script type="text/javascript">
$(document).ready(function(){
	selects();
	
});	

function selects(){
	$("#cmb_clientes").select2();
	$("#cmb_articulos").select2();
}

function showVentas(){
	$("#ventas").fadeIn();
}
</script>
</html>