<?php 
	require_once('lib/nusoap.php');
	session_start();
	if (!isset($_SESSION['rut'])){
		header('Location: /log-in/');
		 exit();	
	}
	$rut = $_SESSION['rut'];
	$beneficiario = $_COOKIE['beneficiario'];
	$atencion = $_GET['atencion'];
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_atenciones_enfermeria_detalle?wsdl', true);
	$result = $client->call('Execute' , array('Beneficiario_rut'=> $beneficiario , 'Atencion_enf_id' => $atencion ));
?>

<?php 
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resumen_beneficiario?wsdl', true);
	$dataresult = $client->call('Execute', array('Beneficiario_rut' => $beneficiario));
?>

<?php get_template_part('mobile/header')?>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
				
				<h4><?php echo $dataresult["Beneficiario_genera_nombres"];?></h4>
				<h5>Detalle atención enfermería realizada el <?php echo $result["Atencion_enf_fecha"]?></h5>
				<div class="separator border"></div>
			
				<h4>Estado actual del paciente</h4>
				<?php echo $result["Atencion_enf_estado"];?>
				
				<div class="separator border"></div>	
				<h5>Sensación de bienestar</h5>
				<div class="insider">
							<?php $sensacion = $result["Atencion_enf_sensacion"];?>
							<?php $left = ((100*$sensacion)/7)-5 ?>
							<?php if( $sensacion == 0) {$left = 0.5;};?>
					<span class="badge-bienestar" style="position:relative; left:<?php echo $left?>%; margin-top:5px"><?php echo $result["Atencion_enf_sensacion"];?></span>
					<div class="bar-bienestar gradient">
						<span class="badge" style="position:relative; left:0%;">1</span>
						<span class="badge pull-right" style="position:relative;">7</span>
					</div>
					
				</div>
				<div class="separator border"></div>
				
				<?php 
							
				$ff = $result["Atencion_enf_adherencia_farma"];
				$fn = $result["Atencion_enf_adherencia_no_farma"];
				$color = 'success'; $ffr = 'fa-smile-o'; $fnr = 'fa-smile-o'; 
				if($ff == 1){$ffr = 'fa-smile-o'; $colorf = 'success';}elseif($ff == 2){$ffr = 'fa-meh-o'; $colorf = 'warning';}elseif($ff == 3){$ffr = 'fa-frown-o'; $colorf = 'danger';}
				if($fn == 1){$fnr = 'fa-smile-o'; $colorn = 'success';}elseif($fn == 2){$fnr = 'fa-meh-o'; $colorn = 'warning';}elseif($fn == 3){$fnr = 'fa-frown-o'; $colorn = 'danger';}
				
				?>
						
				<?php if($ff == 2 || $ff == 3){?>
				<div class="alert alert-farma alert-<?php echo $colorf?> alert-dismissable fade in">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <span class="fa <?php echo $ffr?>" style="font-size:18px;"></span> El paciente <strong>NO</strong> está siguiendo el tratamiento farmacológico.
				</div>
				<?php }?>
				
				<?php if($fn == 2 || $fn == 3){?>
				<div class="alert alert-nofarma alert-<?php echo $colorn?> alert-dismissable fade in">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <span class="fa <?php echo $fnr?>"></span> El paciente <strong>NO</strong> está siguiendo el tratamiento no farmacológico.
				</div>
				<?php }?>
				
				<div class="panel-group" id="accordion">
				  
				  <div class="panel panel-success">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#observaciones">
						  Observaciones Enfermería
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#observaciones" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="observaciones" class="panel-collapse collapse in">
					  <div class="panel-body">
						<p><?php echo $result["Atencion_enf_observaciones"];?></p>
					  </div>
					</div>
				  </div>
				  
				  <div class="panel panel-success">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#inquietudes">
						 	Inquietudes
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#inquietudes" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="inquietudes" class="panel-collapse collapse">
					  <div class="panel-body">
						<p><?php echo $result["Atencion_enf_inquietudes"];?></p>
					  </div>
					</div>
				  </div>		
									  
				  <div class="panel panel-success">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#sugerencias">
						  Sugerencias
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#sugerencias" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="sugerencias" class="panel-collapse collapse">
					  <div class="panel-body">
						<p><?php echo $result["Atencion_enf_sugerencias"]?></p>
					  </div>
					</div>
				  </div>
				  
				  <?php if($ff == 2 || $ff == 3){?>
				  <div class="panel panel-warning">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#adfarma">
						  Adherencia Tratamiento farmacológico
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#adfarma" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="adfarma" class="panel-collapse collapse">
					  <div class="panel-body">
						<p><?php echo $result["Atencion_enf_adherencia_farma_comen"];?></p>
					  </div>
					</div>
				  </div>
				  <?php }?>
				  
				  <?php if($fn == 2 || $fn == 3){?>
				  <div class="panel panel-warning">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#adnofarma">
						  Adherencia Tratamiento No farmacológico
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#adnofarma" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="adnofarma" class="panel-collapse collapse">
					  <div class="panel-body">
						<p><?php echo $result["Atencion_enf_adherencia_no_farma_comen"];?></p>
					  </div>
					</div>
				  </div>
				  <?php }?>
				  
				  <div class="panel panel-success">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#vida">
						 	Consejo de vida saludable
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#vida" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="vida" class="panel-collapse collapse">
					  <div class="panel-body">
						<p><?php echo $result["Atencion_enf_vida_salud"];?></p>
					  </div>
					</div>
				  </div>
				  
				  <div class="separator"></div>
				  		
				  
				  
				</div>				
								
					
			
			</div>
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>