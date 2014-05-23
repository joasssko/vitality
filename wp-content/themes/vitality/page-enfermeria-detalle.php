<?php
/*
Template Name: Detalle Atenciones enfermeria
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-detalle-enfermeria');

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
	$atencion = $_GET['atencion'];
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_atenciones_enfermeria_detalle?wsdl', true);
	$result = $client->call('Execute' , array('Beneficiario_rut'=> $beneficiario , 'Atencion_enf_id' => $atencion ));
?>

<?php 
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resumen_beneficiario?wsdl', true);
	$dataresult = $client->call('Execute', array('Beneficiario_rut' => $beneficiario));
?>



<?php get_header()?>

<div id="main">
	<div class="container">
		<div class="row">
				
				<div class="column column-2-3">
				<div class="separator"></div>
				
				
				<h3><?php echo $dataresult["Beneficiario_genera_nombres"];?></h3>
				<h5>Detalle atención enfermería realizada el <?php echo $result["Atencion_enf_fecha"]?> <button type="button" class="btn btn-info btn-xs pull-right" onclick="jQuery('.column-2-3').printThis({printContainer: true});">Imprimir resumen</button></h5>
				<div class="clear border separator"></div>
				<div class="row">
					<div class="col-md-12"><h3>Estado actual del paciente</h3>
						<p><?php echo $result["Atencion_enf_estado"];?></p>
					
						<div class="separator border clear"></div>	
						<h3>Sensación de bienestar</h3>
						<div class="insider">
							<?php $sensacion = $result["Atencion_enf_sensacion"];?>
							<?php $sensacion = 6;?>
							<?php $left = ((100*$sensacion)/7)-5 ?>
							<?php if( $sensacion == 0) {$left = 0.5;};?>
							<span class="badge-bienestar" style="position:relative; left:<?php echo $left?>%; margin-top:5px">6<?php //echo $result["Atencion_enf_sensacion"];?></span>
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
								
				  	<h3>Observaciones enfermería</h3>
					<p><?php echo $result["Atencion_enf_observaciones"];?></p>
					
				  	<h3>Inquietudes</h3>
					<p><?php echo $result["Atencion_enf_inquietudes"]?></p>

					<h3>Sugerencias</h3>
					<p><?php echo $result["Atencion_enf_sugerencias"];?></p>
					
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
					
				  <h3>Consejo de vida saludable</h3>
				  <p><?php echo $result["Atencion_enf_vida_salud"];?></p>
			</div>
			
			<div class="column column-1-3 column-last">
				<div class="sidebar">
					<div class="separator"></div>
					<?php get_template_part('sidebar')?>
				</div>
			</div>

		
		</div>
	</div>
</div>


<?php get_footer();?>

<?php }?>