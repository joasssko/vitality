<?php
/*
Template Name: Seleccion de paciente
*/?>
<?php 
	session_start();
	if (!isset($_SESSION['rut'])){
		header('Location: /log-in/');
		 exit();	
	}
	$rut = $_SESSION['rut'];
	require_once('lib/nusoap.php');
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_beneficiarios_por_contratante?wsdl', true);
	$result = $client->call('Execute', array("Contratante_rut" => $rut));
	
	if(isset($_COOKIE['alertas'])) {
		unset($_COOKIE['alertas']);
		setcookie('alertas', '', time()-3600 , '/');
	}
?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-paciente');

}else{?>


<?php get_header()?>

<div class="separator"></div>

<div id="main" class="seleccionbeneficiario">
	<div class="container">
		<div class="row">
						
			<div class="col-md-12">
				<h3>Seleccione el Paciente que desea monitorear</h3>
				<h5>Seleccione uno de los beneficiarios asociados para ver el resumen del paciente:</h5>
			</div>
			
			<div class="clear separator"></div>
			  <?php  $afiliados = $result["Beneficiarios"]["vitality_beneficiario"];
			  $acount= 0;
			  foreach( $afiliados as $afiliado):
			  $acount++;
			  ?>
			  <div class="col-md-3">
				<a href="<?php echo get_page_link('15')?>" onclick="jQuery.cookie('beneficiario' , '<?php echo $afiliado["beneficiario_rut"]?>' , { 'path' : '/' })" class="thumbnail">
				<?php if( $acount == 1){?>
				  <img data-src="<?php echo $afiliado["beneficiario_foto"]?>" src="<?php bloginfo('template_directory')?>/images/olw.png" width="100%" >
				 <?php }else{?>
				  <img data-src="<?php echo $afiliado["beneficiario_foto"]?>" src="<?php bloginfo('template_directory')?>/images/olm.png" width="100%" >
				 <?php }?>
				</a>
				<h5><?php echo $afiliado["beneficiario_nombres"]?></h5>
				<a href="<?php echo get_page_link('15')?>" onclick="jQuery.cookie('beneficiario' , '<?php echo $afiliado["beneficiario_rut"]?>' , { 'path' : '/' })" class="btn btn-block btn-large btn-success">Ver resumen del paciente</a>
			  </div>
			  <?php endforeach?>

			<div class="clear separator"></div>
		</div>
	</div>
</div>


<?php get_footer();?>

<?php }?>