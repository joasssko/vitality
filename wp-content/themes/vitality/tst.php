<?php 
		require_once('lib/nusoap.php');
		$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_cambia_contrasenia?wsdl', true);
		$result = $client->call('Execute', array("Contratante_rut" => '12378491' , 'Contrasenia_old' => '872634872364' , 'Contrasenia' => '123456'));
		
		//$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resetea_contratante?wsdl', true);
		//$result = $client->call('Execute', array("Contratante_rut" => '12378491'));
		

?>

<pre><?php var_dump($result)?></pre>