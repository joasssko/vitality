<?php
/*
Template Name: Ultimo control
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-ultimo');

}else{?>

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


<?php get_header()?>

<div class="clear separator"></div>

<div id="main">
	<div class="container">
		<div class="row">

			<div class="column column-2-3">			
				<div class="col-md-10">
					<div class="row">
						<h3><?php echo $dataresult["Beneficiario_genera_nombres"];?></h3>
						<h5>Detalle del último control de enfermería realizado el <?php echo $result["Atencion_enf_fecha"]?></h5>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="row">
						<img src="<?php bloginfo('template_directory')?>/images/olm.png" alt=""  height="80" class="pull-right"/>
					</div>
				</div>
				
				<div class="separator clear border"></div>
				<div class="row">
					<div class="col-md-12">
						
						<span class="data"><span class="fa fa-home fa-fw"></span><?php echo $dataresult["Beneficiario_general_direccion"];?>&nbsp;</span>
						<span class="data"><span class="fa fa-envelope fa-fw"></span><?php echo $dataresult["Beneficiario_general_email"];?>&nbsp;</span>
						<span class="data"><span class="fa fa-phone fa-fw"></span><?php echo $dataresult["Beneficiario_general_telefono"];?>&nbsp;</span>
						<span class="data"><span class="fa fa-mobile fa-fw"></span><?php echo $dataresult["Beneficiario_general_celular"];?>&nbsp;</span>
						
						
						<div class="separator border clear"></div>	
						<h3>Sensación de bienestar</h3>
						<div class="insider">
							
							<?php $sensacion = $result["Atencion_enf_sensacion"];?>
							<?php $sensacion = 5?>
							<?php $left = ((100*$sensacion)/7)-5 ?>
							<?php if( $sensacion == 0) {$left = 0.5;};?>
							<span class="badge-bienestar" style="position:relative; left:<?php echo $left?>%; margin-top:5px">5<?php //echo $result["Atencion_enf_sensacion"];?></span>
							<div class="bar-bienestar gradient">
								<span class="badge" style="position:relative; left:0%;">1</span>
								<span class="badge pull-right" style="position:relative;">7</span>
							</div>
						</div>
						<div class="clear separator border"></div>
					</div>
				</div>
				
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
	
				  	<h3>Estado actual del paciente</h3>
					<p><?php echo $result["Atencion_enf_estado"];?></p>
					
					<h3>Observaciones enfermería</h3>
					<p><?php echo $result["Atencion_enf_observaciones"];?></p>
				 
					<div class="row">
					<?php if($ff == 2 || $ff == 3){?>
					<div class="col-md-6">
						<h3>Adherencia Tratamiento farmacológico</h3>
						<p><?php echo $result["Atencion_enf_adherencia_farma_comen"];?></p>
					</div>
					<?php }?>
					<?php if($fn == 2 || $fn == 3){?>
					<div class="col-md-6">
						<h3>Adherencia Tratamiento no farmacológico</h3>
						<p><?php echo $result["Atencion_enf_adherencia_no_farma_comen"];?></p>
					</div>
					<?php }?>
					</div>
					
					
				
				  
			
				
				
				
				
			</div>
			
			
			
			<div class="column column-1-3 column-last">
				<div class="sidebar">
					<?php get_template_part('sidebar')?>
				</div>
			</div>
			

			
			
			
			
		</div>
	</div>
</div>


<?php get_footer();?>

<?php }?>