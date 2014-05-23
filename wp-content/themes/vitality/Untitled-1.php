<?php 

$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_cambia_contrasenia?wsdl', true);
		$result = $client->call('Execute', array("Contratante_rut" => $_SESSION['rut'] , 'Contrasenia_old' => $passold , 'Contrasenia' => $pass));
		
		var_dump($result);

?>