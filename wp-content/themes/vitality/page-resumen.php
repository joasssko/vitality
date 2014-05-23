<?php
/*
Template Name: Resumen del paciente
*/?>
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
	$result = $client->call('Execute', array("Beneficiario_rut" => $beneficiario));

    setcookie('alertas', 'eliminapop' , 0 , '/');
?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-resumen');

}else{?>

<?php get_header()?>

<div class="clear separator"></div>

<div id="main">
	<div class="container">
		<div class="row">

			<div class="column column-2-3">
				
				
				
				<div class="col-md-10">
					<div class="row">
						<h3><?php echo $result["Beneficiario_genera_nombres"];?></h3>
						<h5>Resumen del paciente</h5>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="row">
						<a href="#" data-toggle="modal" data-target="#foto"><img src="<?php bloginfo('template_directory')?>/images/olm.png" alt=""  height="80" class="pull-right"/></a>
						
						<!-- Modal -->
						<div class="modal fade" id="foto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel"><?php echo $result["Beneficiario_genera_nombres"];?></h4>
							  </div>
							  <div class="modal-body" style="text-align:center">
								<img src="<?php bloginfo('template_directory')?>/images/olm.png" alt="" width="300" />
							  </div>
							  <div class="modal-footer">
								<button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cerrar</button>
							  </div>
							</div>
						  </div>
						</div>
						
					</div>
				</div>
				
				<div class="separator clear border"></div>
				<div class="row">
					<div class="col-md-12">
						<span class="data"><span class="fa fa-home fa-fw"></span><?php echo $result["Beneficiario_general_direccion"];?>&nbsp;</span>
						<span class="data"><span class="fa fa-envelope fa-fw"></span><?php //echo $result["Beneficiario_general_email"];?>dameva@fa.kemail.com&nbsp;</span>
						<span class="data"><span class="fa fa-phone fa-fw"></span><?php echo $result["Beneficiario_general_telefono"];?>&nbsp;</span>
						<span class="data"><span class="fa fa-mobile fa-fw"></span><?php echo $result["Beneficiario_general_celular"];?>&nbsp;</span>
					</div>
				</div>		
				<div class="clear separator border"></div>
				
				<h5>Sensación de bienestar</h5>
				<div class="insider">
					
					<?php $bienestar = $result["Beneficiario_sensacion"]["vitality_beneficiario_sensacion.vitality_beneficiario_sensacionItem"]?>
					<?php //$bienestar = 6?>
					<?php //var_dump($bienestar)?>
					
					<div id="sensaciondebienestar" style="height: 180px;"></div>
					<script type="text/javascript">
					new Morris.Line({
					  element: 'sensaciondebienestar',
					  data: [
						
						<?php foreach ($bienestar as $mes):
						$mess = substr($mes['mes'] , 0 , 7);?>
						{ mes: '<?php echo $mess?>', value: <?php echo $mes["sensacion"]?> },
						<?php endforeach?>
						
					  ],
					  xkey: 'mes',
					  ykeys: ['value'],
					  labels: ['Sensacion'],
					  ymax: 7,
					  ymin: 1,
					  lineColors: ['#adc608', '#84bad5'],
					  goalLineColors: ['#84bad5'],
					  smooth: false,
					  goals: [4.0, 7.0],
					  axes: true
					});
					</script>
					<?php ?>
					
				</div>						
						
					<div class="clear separator"></div>
					<h3>Indicaciones médicas</h3>
					<p><?php echo $result["Beneficiario_salud_indicaciones"];?></p>
					<div class="separator"></div>
					<div class="row">
						<div class="col-md-6">
							<h3>Tratamiento farmacológico</h3>
							<p><?php echo $result["Beneficiario_salud_tratamiento_farma"];?></p>
						</div>
						
						<div class="col-md-6">
							<h3>Tratamiento no farmacológico</h3>
							<p><?php echo $result["Beneficiario_salud_tratamiento_no_farma"];?></p>
						</div>
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
<?php /*alertas*/?>
<div class="modal fade bs-example-modal-sm alertas" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      	
	  	<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Recordatorios</h4>
		</div>
		<div class="cont">
		<h2>Estimado(a):</h2>

		<p>Le recordamos que se acercan los siguientes controles médicos y/o exámenes, Recuerde que debe asistir con al menos 15 minutos de anticipación para evitar posibles retrasos.</p>			
		
		<?php $alertas = $result["Beneficiario_alertas"]["vitality_alertaporpaciente.vitality_alertaporpacienteItem"]?>	
		<?php foreach($alertas as $alerta):?>
			<h5><span class="label label-info"><?php echo $alerta["alerta_fecha"]?></span> <?php echo $alerta["alerta_descripcion"]?></h5>
		<?php endforeach?>
	  	</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
    </div>
  </div>
</div>

<?php if(!$_COOKIE['alertas'] == 'eliminapop'){?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.alertas').modal('show')
});
</script>
<?php }?>
<?php get_footer();?>

<?php }?>
