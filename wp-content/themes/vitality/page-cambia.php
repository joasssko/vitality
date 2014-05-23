<?php
/*
Template Name: Cambia contraseña
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-cambia');

}else{?>


<?php 
	session_start();

	

	if (isset($_POST['user'])){
		require_once('lib/nusoap.php');
		$rut = $_POST['user'];
		$pass = $_POST['pass'];
		$passold = $_POST['passold'];
		$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_cambia_contrasenia?wsdl', true);
		$result = $client->call('Execute', array("Contratante_rut" => $rut , 'Contrasenia_old' => $passold , 'Contrasenia' => $pass));

		if ($result['Resultado'] == '0'){ ?>
			<div class="alert alert-success topalert">
				<div class="container">
					<div class="row"><h4>Su contraseña fue cambiada exitosamente</h4></div>
				</div>
			</div>
		<?php }else{?>
			<div class="alert alert-danger topalert">
				<div class="container">
					<div class="row"><h4>Hubo un error en los datos ingresados, favor intente nuevamente.</h4></div>
				</div>
			</div>
		<?php
		}

		
	}
?>

<?php get_header();?>

<div id="main">
	<div class="container">
		<form class="form-signin ingresar" role="form" method='post'>
			<h2 class="form-signin-heading">Cambiar contraseña</h2>
			<?php if(!$result['Resultado'] == '0'){;?>
				<div class="alert alert-danger">Contraseña erronea</div>
			<?php }?>
			<input name = "user" type="text" class="form-control" placeholder="Rut cliente" required="" autofocus="">
			<input name = "passold" type="password" class="form-control" placeholder="Contraseña anterior" required="" autofocus="" value="<?php echo $_GET['token']?>">
			<input name = "pass" type="password" class="form-control" placeholder="Contraseña nueva" required="">
			
			<button class="btn btn-sm btn-primary btn-block" type="submit">Cambiar contraseña</button>
		</form>
	</div>
</div>


<?php get_footer();?>

<?php }?>
