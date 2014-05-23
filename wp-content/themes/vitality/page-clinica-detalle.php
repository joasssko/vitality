<?php
/*
Template Name: Detalles Atenciones Clínica
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-clinica');

}else{?>


<?php 
	require_once('lib/nusoap.php');
	$rut = "12378491";
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_atenciones_clinicas?wsdl', true);
	$result = $client->call('Execute', array("Corclirutcli" => $rut,"Clirut" => $rut));
?>


<?php get_header()?>


<div id="main">
	<div class="container">
		<div class="row">
			<h1><?php echo $post->post_title;?></h1>
			
			<h3>El paciente seleccionado tiene <?php echo count($result["Vitality_atenciones_clinicas"]["vitality_atencion_clinica"])?> atencion(es):</h3>

			
			<ul class="atenciones">
			<?php  $atenciones = $result["Vitality_atenciones_clinicas"]["vitality_atencion_clinica"];
			foreach( $atenciones as $atencion):?>
				<li>
					<h4><?php echo $atencion["atencion_cli_fecha"]?></h4>
					<h5><?php echo $atencion["atencion_cli_medico"]?></h5>
					<h5><?php echo $atencion["atencion_cli_especialidad"]?></h5>
					<div class="morelink"><a href="<?php echo get_page_link('45')?>/#<?php echo $atencion["atencion_cli_id"]?>">Ver atención enfermeria</a></div>
					<div class="separator border"></div>
				</li>
			<?php endforeach;?>
			</ul>
			
		</div>
	</div>
</div>

<?php get_footer();?>

<?php }?>