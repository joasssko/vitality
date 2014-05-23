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
<?php get_template_part('mobile/header')?>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
			
				<h4><?php echo $dataresult["Beneficiario_genera_nombres"];?></h4>
				<h5>Exámenes realizados</h5>
				<div class="separator border"></div>
				<img src="<?php echo $dataresult["Beneficiario_foto"]?>" alt="" class="alignleft" width="60" />
				
				<span class="data"><span class="fa fa-home fa-fw"></span><?php echo $dataresult["Beneficiario_general_direccion"];?>&nbsp;</span>
				<span class="data"><span class="fa fa-envelope fa-fw"></span><a href="mailto:<?php echo $dataresult["Beneficiario_general_email"];?>"><?php echo $dataresult["Beneficiario_general_email"];?>&nbsp;</a></span>
				<span class="data"><span class="fa fa-phone fa-fw"></span><a href="callto:<?php echo $dataresult["Beneficiario_general_telefono"];?>"><?php echo $dataresult["Beneficiario_general_telefono"];?></a>&nbsp;</span>
				<span class="data"><span class="fa fa-mobile fa-fw"></span><a href="callto:<?php echo $dataresult["Beneficiario_general_celular"];?>"><?php echo $dataresult["Beneficiario_general_celular"];?></a>&nbsp;</span>
				
				<div class="separator border clear"></div>
				<h4>El paciente tiene <span class="badge" style="position:relative; top:-3px"><?php echo count($result["Vitality_examenes"]["vitality_examenes.vitality_examenesItem"])?></span> atencion(es):</h4>
				<div class="separator border"></div>
				
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
					  <th>Fecha</th>
					  <th>Exámen</th>
					  <th><span class="fa fa-eye"></span></th>
					  <th><span class="fa fa-print"></span></th>
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
					  
					</tr>
					<?php endforeach;?>
				  </tbody>
				</table>
			
			</div>
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>