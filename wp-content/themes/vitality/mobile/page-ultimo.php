<?php 
	require_once('lib/nusoap.php');
	session_start();
	if (!isset($_SESSION['rut'])){
		header('Location: /log-in/');
		 exit();	
	}
	$rut = $_SESSION['rut'];
	$beneficiario = $_COOKIE['beneficiario'];
	
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resumen_beneficiario?wsdl', true);
	$dataresult = $client->call('Execute', array('Beneficiario_rut' => $beneficiario));
	 
	//obtiene listado de atenciones por beneficiario
	$beneficiario = $_COOKIE['beneficiario'];
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_atenciones_enfermeria?wsdl', true);
	$ateresult = $client->call('Execute', array("Beneficiario_rut" => $beneficiario));

	//obtiene el ultimo ID de control
	$atencionID = $ateresult["Atenciones_enfermeria"]["vitality_atencionenfermeria.vitality_atencionenfermeriaItem"][0]["atencion_enf_id"];
	//$atencionID = 4987;

	//obtiene detalle del ultimo control  
	$atencion = $_GET['atencion'];
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_atenciones_enfermeria_detalle?wsdl', true);
	$result = $client->call('Execute' , array('Beneficiario_rut' => $beneficiario , 'Atencion_enf_id' => $atencionID ));
?>


<?php get_template_part('mobile/header')?>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
			
				<h4><?php echo $dataresult["Beneficiario_genera_nombres"];?></h4>
				<h5>Detalle del último control de enfermería realizado el <?php echo $result["Atencion_enf_fecha"]?></h5>
				<div class="separator border"></div>
				<img src="<?php echo $dataresult["Beneficiario_foto"]?>" alt="" class="alignleft"  width="60" />
				
				<span class="data"><span class="fa fa-home fa-fw"></span><?php echo $dataresult["Beneficiario_general_direccion"];?>&nbsp;</span>
				<span class="data"><span class="fa fa-envelope fa-fw"></span><a href="mailto:<?php echo $dataresult["Beneficiario_general_email"];?>"><?php echo $dataresult["Beneficiario_general_email"];?></a>&nbsp;</span>
				<span class="data"><span class="fa fa-phone fa-fw"></span><a href="callto:<?php echo $dataresult["Beneficiario_general_telefono"];?>"><?php echo $dataresult["Beneficiario_general_telefono"];?></a>&nbsp;</span>
				<span class="data"><span class="fa fa-mobile fa-fw"></span><a href="callto:<?php echo $dataresult["Beneficiario_general_celular"];?>"><?php echo $dataresult["Beneficiario_general_celular"];?></a>&nbsp;</span>
				
				<div class="separator border clear"></div>
				
				<h4>Sensación de bienestar</h4>
				<div class="insider">
					<?php $sensacion = $result["Atencion_enf_sensacion"];?>
					<?php /* Atencion_enf_adherencia_farma: 1=Con Adherencia, 2= Con Reparos, 3=Sin Adherencia. */?>
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
				$color = 'success'; $ffr = 'fa-smile'; $fnr = 'fa-smile'; 
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
						<a data-toggle="collapse" data-parent="#accordion" href="#estado">
						  Estado actual del paciente
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#estado" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="estado" class="panel-collapse collapse in">
					  <div class="panel-body">
						<p><?php echo $result["Atencion_enf_estado"];?></p>
						
					  </div>
					</div>
				  </div>
				  
				  <div class="panel panel-success">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#observaciones">
						 	Observaciones enfermería
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#observaciones" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="observaciones" class="panel-collapse collapse">
					  <div class="panel-body">
						<p><?php echo $result["Atencion_enf_observaciones"];?></p>
					  </div>
					</div>
				  </div>		
				  				  
				  
				 <?php if($ff == 2 || $ff == 3){?>
				  <div class="panel panel-success">
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
				  <div class="panel panel-success">
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
						<p><?php echo $result["Atencion_enf_adherencia_farma_comen"];?></p>
					  </div>
					</div>
				  </div>
				  <?php }?>
				  
				  
					  
				</div>
				
				
				
			
			</div>
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>