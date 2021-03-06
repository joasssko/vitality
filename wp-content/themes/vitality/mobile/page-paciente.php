<?php 
	$rut = $_SESSION['rut'];
	$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_beneficiarios_por_contratante?wsdl', true);
	$result = $client->call('Execute', array("Contratante_rut" => $rut));
	
	if(isset($_COOKIE['alertas'])) {
		unset($_COOKIE['alertas']);
		setcookie('alertas', '', time()-3600 , '/');
	}
?>
<?php get_template_part('mobile/header')?>
<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
			
			<h3>Seleccione el Paciente que desea monitorear</h3>
			<h5>Seleccione uno de los beneficiarios asociados para ver el resumen del paciente:</h5>
			
			
			<div class="clear separator"></div>
			

			  
			  <?php  $afiliados = $result["Beneficiarios"]["vitality_beneficiario"];
			  foreach( $afiliados as $afiliado):?>
			  <div class="">
			  	<div class="col-xs-4">
					<a href="<?php echo get_page_link('15')?>" onclick="jQuery.cookie('beneficiario' , '<?php echo $afiliado["beneficiario_rut"]?>' , { 'path' : '/' })" class="thumbnail">
					  <img data-src="<?php echo $afiliado["beneficiario_foto"]?>" src="<?php echo $afiliado["beneficiario_foto"]?>" width="100%" >
					</a>
				</div>
				<div class="col-xs-8">
					<h5><?php echo $afiliado["beneficiario_nombres"]?></h5>
					<a href="<?php echo get_page_link('15')?>" onclick="jQuery.cookie('beneficiario' , '<?php echo $afiliado["beneficiario_rut"]?>' , { 'path' : '/' })" class="btn btn-block btn-xs btn-success">Ver resumen del paciente</a>
			 	</div>
				<div class="clear"></div>
			  </div>
			  <?php endforeach?>
			  

			<div class="clear separator"></div>
			
				
			
			</div>
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>