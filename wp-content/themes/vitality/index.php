<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/index');

}else{?>

<?php get_header()?>

<div class="beneficios">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<img src="<?php bloginfo('template_directory')?>/images/santamaria.jpg" alt="" width="150px" class="img-circle" />
				<h4>Clínica con gran trayectoria</h4>
			</div>
			<div class="col-md-3">
				<img src="<?php bloginfo('template_directory')?>/images/docs.jpg" alt="" width="150px" class="img-circle" />
				<h4>Enfermeras altamente preparadas</h4>
			</div>
			<div class="col-md-3">
				<img src="<?php bloginfo('template_directory')?>/images/devices.jpg" alt="" width="150px" class="img-circle" />
				<h4>Tecnología y conectividad</h4>
			</div>
			<div class="col-md-3">
				<img src="<?php bloginfo('template_directory')?>/images/rescate.jpg" alt="" width="150px" class="img-circle" />
				<h4>Líderes en rescate médico</h4>
			</div>
			<div class="clear"></div>
		</div>
	</div>
				
</div>

<div id="main">
	<div class="container">
		<div class="row">
			<div class="clear separator"></div>
			<div class="col-md-4"><blockquote class="blockquote-reverse"><h2 style="text-transform:none; font-weight:300; font-style:italic">Porque queremos que vivas tranquilo y ellos puedan disfrutar por muchos años más.</h2></blockquote></div>
			<div class="col-md-8"><h3>Modelo de control de salud ciclo vital</h3><p>Los tiempos han cambiado y nuestros padres también. Hoy quieren vivir mejor que nadie su vida y disfrutar cada minuto por muchos años más, por eso en Help hemos desarrollado este nuevo plan que te ayudará a mantener la buena salud de tus padres y te mantendrá siempre informado para tu total tranquilidad.</p>	
<p>Con Vitality de Help, una ENFERMERA, junto a todo el equipo experto de Help, acompañarán y monitorearán periódicamente la salud y bienestar de tus padres. Y en caso de una emergencia y urgencia se le enviará un móvil al lugar del incidente.	</p></div>
			<div class="clear"></div>	
		</div>
	</div>
</div>


<?php get_footer()?>

<?php }?>