<?php
/*
Template Name: Atenciones Clínica
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
<?php 
	require_once('lib/nusoap.php');
	$rut = "12378491";
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resumen_beneficiario?wsdl', true);
	$dataresult = $client->call('Execute', array("Corclirutcli" => $rut,"Clirut" => $rut));
?>

<?php get_header()?>



<div id="main">
	<div class="container">
		<div class="row">
		
			<div class="column column-2-3">
				<div class="separator"></div>
				<h1><?php echo $post->post_title;?></h1>
				<div class="separator"></div>
			
				<h3><?php echo $dataresult["Beneficiario_genera_nombres"];?></h3>
				<span class="data"><span class="fa fa-home fa-fw"></span><?php echo $dataresult["Beneficiario_general_direccion"];?></span>
							
				<h4>El paciente tiene <span class="badge" style="position:relative; top:-3px"><?php echo count($result["Vitality_atenciones_clinicas"]["vitality_atencion_clinica"])?></span> atencion(es):</h4>
				<div class="separator"></div>	
				
				<table class="table table-hover table-striped">
				  <thead>
					<tr>
					  <th>Fecha</th>
					  <th>Medico tratante</th>
					  <th>Especialidad</th>
					  <th><span class="fa fa-eye"></span></th>
					</tr>
				  </thead>
				  <tbody>
				  	
					<?php  $atenciones = $result["Vitality_atenciones_clinicas"]["vitality_atencion_clinica"];
					foreach( $atenciones as $atencion):?>
					<tr>
					  <td><?php echo $atencion["atencion_cli_fecha"]?></td>
					  <td><?php echo $atencion["atencion_cli_medico"]?></td>
					  <td><?php echo $atencion["atencion_cli_especialidad"]?></td>
					  <td><a href="<?php echo get_page_link('45')?>/#<?php echo $atencion["atencion_cli_id"]?>"><span class="fa fa-eye"></span></a></td>
					</tr>
					<?php endforeach;?>
				  </tbody>
				</table>
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