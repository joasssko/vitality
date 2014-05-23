<?php
/*
Template Name: Atenciones enfermeria
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-enfermeria');

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
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_atenciones_enfermeria?wsdl', true);
	$result = $client->call('Execute', array("Beneficiario_rut" => $beneficiario));
?>
<?php 
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resumen_beneficiario?wsdl', true);
	$dataresult = $client->call('Execute', array("Beneficiario_rut" => $beneficiario));
?>

<?php get_header()?>



<div id="main">
	<div class="container">
		<div class="row">
			
			<div class="clear separator"></div>
		<div class="column column-2-3">
		
				<div class="col-md-10">
					<div class="row">
						<h3><?php echo $dataresult["Beneficiario_genera_nombres"];?></h3>
						<h5>Control de enfermería</h5>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="row">
						<img src="<?php echo $dataresult["Beneficiario_foto"]?>" alt=""  height="80" class="pull-right"/>
					</div>
				</div>
			
				<div class="separator clear border"></div>
							
				<h4>El paciente tiene <span class="badge" style="position:relative; top:-3px"><?php echo count($result["Atenciones_enfermeria"]["vitality_atencionenfermeria.vitality_atencionenfermeriaItem"])?></span> atencion(es):</h4>
				
				<div class="separator clear border"></div>		
				
				<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('#atenciones').DataTable({
							'language' : {
								'url' : '//cdn.datatables.net/plug-ins/e9421181788/i18n/Spanish.json',
							},
							"ordering": false,
						});
				});
				</script>
				
				<table class="table table-hover table-striped" id="atenciones">
				  <thead>
					<tr>
					  <th width="130">Fecha</th>
					  <th>Nombre enfermera</th>
					  <th width="50"><span class="fa fa-eye"></span></th>
					</tr>
				  </thead>
				  <tbody>
				  	
					<?php  $atenciones = $result["Atenciones_enfermeria"]["vitality_atencionenfermeria.vitality_atencionenfermeriaItem"];
					foreach( $atenciones as $atencion):?>
					<tr>
					  <td><?php echo $atencion["atencion_enf_fecha"]?></td>
					  <td><?php echo $atencion["atencion_enf_enfermera"]?></td>
					  <td><a href="<?php echo get_page_link('45')?>?atencion=<?php echo $atencion["atencion_enf_id"]?>"><span class="fa fa-eye"></span></a></td>
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