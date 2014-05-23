<?php get_template_part('mobile/header')?>
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

<div class="separator"></div>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
			
			
				<h5>Resumen de atenciones clínicas</h5>
				<div class="separator border"></div>
				<h3><?php echo $dataresult["Beneficiario_genera_nombres"];?></h3>
				<span class="data"><span class="fa fa-home fa-fw"></span><?php echo $dataresult["Beneficiario_general_direccion"];?></span>
				<div class="separator border"></div>
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
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>