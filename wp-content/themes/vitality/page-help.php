<?php
/*
Template Name: Atenciones HELP
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-help');

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
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_atenciones_help?wsdl', true);
	$result = $client->call('Execute', array('Beneficiario_rut' => $beneficiario));
?>
<?php 
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resumen_beneficiario?wsdl', true);
	$dataresult = $client->call('Execute', array('Beneficiario_rut' => $beneficiario));
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
						<h5>Atenciones HELP</h5>
					</div>
				</div>
				<div class="col-md-2">
					<div class="row">
						<img src="<?php echo $dataresult["Beneficiario_foto"]?>" alt=""  height="80" class="pull-right"/>
					</div>
				</div>
				<div class="separator clear border"></div>
				<h4>El paciente tiene <span class="badge" style="position:relative; top:-3px"><?php echo count($result["Atenciones_help"]["vitality_atencion_help.vitality_atencion_helpItem"])?></span> atencion(es):</h4>
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
					  <th width="80">Fecha</th>
					  <!--<th width="30" style="text-align:center">Tipo</th> -->
					  <th>Comentario</th>
					</tr>
				  </thead>
				  <tbody>
				  	
					<?php  $atenciones = $result["Atenciones_help"]["vitality_atencion_help.vitality_atencion_helpItem"];
					foreach( $atenciones as $atencion):?>
					<tr>
					  <td><?php echo $atencion["atencion_help_fecha"]?></td>
					  <!--<td style="text-align:center"><?php 
						$codigo = $atencion["atencion_help_codigo"];
						$color = '#27ae60'; 
						if($codigo == 'V'){$color = '27ae60';}elseif($codigo == 'A'){ $color = 'f1c40f';}elseif($codigo == 'R'){ $color = 'c0392b';}else{$color = '3498db';}?>
						<img src="http://placehold.it/20x20/<?php echo $color?>/<?php echo $color?>" alt="" /></td> -->
					 	<td><?php echo $atencion["atencion_help_comentario"]?></td>
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