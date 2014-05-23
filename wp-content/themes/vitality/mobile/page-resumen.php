<?php get_template_part('mobile/header')?>
<?php 
	$rut = $_SESSION['rut'];
	$beneficiario = $_COOKIE['beneficiario'];
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resumen_beneficiario?wsdl', true);
	$result = $client->call('Execute', array("Beneficiario_rut" => $beneficiario));
    setcookie('alertas', 'eliminapop' , 0 , '/');
?>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
			
				<h4><?php echo $result["Beneficiario_genera_nombres"];?></h4>
				<h5>Resumen del paciente</h5>
				<div class="separator border"></div>
				
				<img src="<?php echo $result["Beneficiario_foto"]?>" alt="" class="alignleft" width="60" />
				
				<span class="data"><span class="fa fa-home fa-fw"></span><?php echo $result["Beneficiario_general_direccion"];?></span>
				<span class="data"><span class="fa fa-envelope fa-fw"></span><a href="mailto:<?php echo $result["Beneficiario_general_email"];?>"><?php echo $result["Beneficiario_general_email"];?></a></span>
				<span class="data"><span class="fa fa-phone fa-fw"></span><a href="callto:<?php echo $result["Beneficiario_general_telefono"];?>"><?php echo $result["Beneficiario_general_telefono"];?></a></span>
				<span class="data"><span class="fa fa-mobile fa-fw"></span><a href="callto:<?php echo $result["Beneficiario_general_celular"];?>"><?php echo $result["Beneficiario_general_celular"];?></a>&nbsp;</span>
				
				<div class="clear separator border"></div>
				
				<h4>Sensación de bienestar</h4>
				<div class="insider">
					<?php $bienestar = $result["Beneficiario_sensacion"]["vitality_beneficiario_sensacion.vitality_beneficiario_sensacionItem"]?>
					<div id="sensaciondebienestar" style="height: 150px;"></div>
							<script type="text/javascript">
							new Morris.Line({
							  element: 'sensaciondebienestar',
							  data: [
							  	
								
								<?php foreach ($bienestar as $mes): $mess = substr($mes['mes'] , 0 , 7);?>
								{ mes: '<?php echo $mess?>', value: <?php echo $mes["sensacion"]?> },
								<?php endforeach?>
								
							  ],
							  xkey: 'mes',
							  ykeys: ['value'],
							  labels: ['Sensacion'],
							  lineColors: ['#adc608'],
							  goalLineColors: ['#84bad5'],
							  ymax: 7,
							  ymin: 1,
							  smooth: false,
							});
							</script>
					
				</div>
				<div class="separator border"></div>
				
						
				
				<div class="panel-group" id="accordion">
				  
				  
				  <div class="panel panel-success">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#indicaciones">
						  Indicaciones médicas
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#indicaciones" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="indicaciones" class="panel-collapse collapse in">
					  <div class="panel-body">
						<p><?php echo $result["Beneficiario_salud_indicaciones"];?></p>
					  </div>
					</div>
				  </div>
				  
				  
				  <div class="panel panel-success">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#tfarma">
						  Tratamiento farmacológico
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#tfarma" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="tfarma" class="panel-collapse collapse">
					  <div class="panel-body">
						<p><?php echo $result["Beneficiario_salud_tratamiento_farma"];?></p>
					  </div>
					</div>
				  </div>
				  
				  
				  
				  
				  <div class="panel panel-success">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#tnofarma">
						  Tratamiento No farmacológico
						</a>
						<a data-toggle="collapse" data-parent="#accordion" href="#tnofarma" class="pull-right collapsed">
							<span class="fa  fa-chevron-circle-down"></span>
						</a>
					  </h4>
					</div>
					<div id="tnofarma" class="panel-collapse collapse">
					  <div class="panel-body">
						<p><?php echo $result["Beneficiario_salud_tratamiento_no_farma"];?></p>
					  </div>
					</div>
				  </div>
				  
				  
				  
				</div>
				
				
				
			
			</div>
		</div>
	</div>
</div>

<?php /*alertas*/?>

<div class="modal fade bs-example-modal-sm alertas" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      	
	  	
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


<?php get_template_part('mobile/footer')?>