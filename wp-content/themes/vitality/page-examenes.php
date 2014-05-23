<?php
/*
Template Name: Exámenes realizados
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-examenes');

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
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_examenes?wsdl', true);
	$result = $client->call('Execute', array("Beneficiario_rut" => $beneficiario));
?>
<?php 
	require_once('lib/nusoap.php');
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
						<h5>Exámenes realizados</h5>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="row">
						<img src="<?php echo $dataresult["Beneficiario_foto"]?>" alt=""  height="80" class="pull-right"/>
					</div>
				</div>
			
				<div class="separator clear border"></div>
							
				<h4>El paciente tiene <span class="badge" style="position:relative; top:-3px"><?php echo count($result["Vitality_examenes"]["vitality_examenes.vitality_examenesItem"])?></span> atencion(es):</h4>
				
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
					  <th width="120">Fecha</th>
					  <th>Exámen</th>
					  <th width="50"><span class="fa fa-eye"></span></th>
					  <th width="50"><span class="fa fa-print"></span></th>
					</tr>
				  </thead>
				  <tbody>
				  	
					<?php  $atenciones = $result["Vitality_examenes"]["vitality_examenes.vitality_examenesItem"];
					foreach( $atenciones as $atencion):?>
					
					<tr>
					  <td><?php echo $atencion["examen_fecha"]?></td>
					  <td><?php echo $atencion["examen_nombre"]?></td>
					  <td><a data-toggle="modal" data-target="#myModal-<?php echo $atencion["examen_id"]?>" href="#"><span class="fa fa-eye"></span></a></td>
					  <th><a href="#" onclick="jQuery('.exa-<?php echo $atencion["examen_id"]?>').printThis({printContainer: true});"><span class="fa fa-print"></span></a></th>
					  
					  	<!-- Modal -->
						<div class="modal fade" id="myModal-<?php echo $atencion["examen_id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">Exámen | <?php echo $atencion["examen_nombre"]?> <span class="label label-info"><?php echo $atencion["examen_fecha"]?></span></h4>
							  </div>
							  <div class="modal-body exa-<?php echo $atencion["examen_id"]?>">
								<img src="<?php echo $atencion["examen_url"]?>" alt="" width="100%" />
							  </div>
							  <div class="modal-footer">
								<button type="button" class="btn btn-info" onclick="jQuery('.exa-<?php echo $atencion["examen_id"]?>').printThis({printContainer: true});">Imprimir exámen</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
							  </div>
							</div>
						  </div>
						</div>
					  	<!--endmodal -->
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