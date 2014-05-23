<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/404');

}else{?>
<?php get_header()?>


<div class="separator"></div>

<div class="main">
	<div class="container">
		<div class="row">
			
			<div class="post 404">
				<img src="<?php bloginfo('template_directory')?>/images/404.jpg" alt="" width="100%" />
				<h1>Lo sentímos</h1>
				<p>La página que buscas no existe o fue movida a otro lugar, puedes acceder al resumen del paciente desde <a href="<?php echo get_page_link(15)?>">acá</a></p>
			</div>
						
		</div>
	</div>
</div>




<?php get_footer()?>
<?php }?>