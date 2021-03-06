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
<?php get_template_part('mobile/header')?>

<div class="separator"></div>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
			
				<h4><?php echo $dataresult["Beneficiario_genera_nombres"];?></h4>
				<h5>Control de enfermería</h5>
				<div class="separator border"></div>
				<img src="<?php echo $dataresult["Beneficiario_foto"]?>" alt="" class="alignleft" width="60" />
				
				<span class="data"><span class="fa fa-home fa-fw"></span><?php echo $dataresult["Beneficiario_general_direccion"];?>&nbsp;</span>
				<span class="data"><span class="fa fa-envelope fa-fw"></span><a href="mailto:<?php echo $dataresult["Beneficiario_general_email"];?>"><?php echo $dataresult["Beneficiario_general_email"];?>&nbsp;</a></span>
				<span class="data"><span class="fa fa-phone fa-fw"></span><a href="callto:<?php echo $dataresult["Beneficiario_general_telefono"];?>"><?php echo $dataresult["Beneficiario_general_telefono"];?></a>&nbsp;</span>
				<span class="data"><span class="fa fa-mobile fa-fw"></span><a href="callto:<?php echo $dataresult["Beneficiario_general_celular"];?>"><?php echo $dataresult["Beneficiario_general_celular"];?></a>&nbsp;</span>
				
				<div class="separator border clear"></div>
				<h4>El paciente tiene <span class="badge" style="position:relative; top:-3px"><?php echo count($result["Atenciones_enfermeria"]["vitality_atencionenfermeria.vitality_atencionenfermeriaItem"])?></span> atencion(es):</h4>
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
					  <th width="100">Fecha</th>
					  <th>Nombre enfermera</th>
					  <th width="30"><span class="fa fa-eye"></span></th>
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
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>