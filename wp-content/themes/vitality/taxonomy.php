<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/taxonomy');

}else{?>

<?php get_header()?>
<?php 
$var = get_queried_object();
$tipo = $var->taxonomy;
$type = $var->slug;
$typoID = $var->term_id;
/* 
$termchildren = get_terms( $tipo, array('child_of' => $typoID));
array_push($termchildren , $tipo);
var_dump( $termchildren);

 */?>



<?php if(get_field('custom_background' , $tipo.'_'.$typoID)){?>
<style type="text/css">
	body{ background-image:url(<?php echo get_field('custom_background' , $tipo.'_'.$typoID)?>); background-position:top center; background-repeat:no-repeat; background-attachment:fixed;}
	#footer-menu {border-top: none;}
	#header{ background-image:none;}
</style>
<?php }?>
<!--<div class="separator"></div> -->



<div class="container">
	<div class="row"><h1 class="jugador-title"><?php echo $var->name?></h1></div>
</div>


<div class="main">
	<div class="container">
		<div class="row">
			
			<div id="content" class="column column-2-3">
				
				
				<?php 
				
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$posts = get_posts(array('post_type' => 'post', $tipo=>$type, 'orderby'   => 'menu_order', 'order' => 'ASC' , 'posts_per_page' => 11 , 'paged'=> $paged));
				
				//var_dump($posts);
				
				if($posts){
					
					$countposts = 0;
					foreach($posts as $post):
					$countposts++;	
					$lastcolumn = '';	
						if($countposts == 1){
							echo '<div class="post">';
							$tipoPost = wp_get_post_terms( $post->ID, 'tipo');
							if($tipoPost[0]->slug == 'noticias'){
								$equipo = wp_get_post_terms( $post->ID, 'equipo');
								$link = get_term_link( $equipo[0]->slug, 'equipo' );
								echo '<a href="'.$link.'" class="categoriapost">'.$equipo[0]->name.'</a>';
							}else{
								$link = get_term_link( $tipoPost[0]->slug, 'tipo' );
								echo '<a href="'.$link.'" class="categoriapost">'.$tipoPost[0]->name.'</a>';
							};
							if($post->post_type=='prensa-futbol-tv'){
								$tipodevideo = get_field('tipo_de_video' , $post->ID);
								//echo $tipodevideo;
								if($tipodevideo=='selfhosted'){
									echo do_shortcode('[video width="630" height="443" mp4="'.get_field('url_de_video' , $post->ID).'"][/video]');
								}elseif($tipodevideo=='youtube'){
									echo '<iframe width="630" height="443" src="//www.youtube.com/embed/'.get_field('youtube' , $post->ID).'?rel=0" frameborder="0" allowfullscreen></iframe>';
								}elseif($tipodevideo=='vimeo'){
									echo '<iframe src="//player.vimeo.com/video/'.get_field('vimeo' , $post->ID).'?title=0&amp;byline=0&amp;portrait=0" width="630" height="443" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
								}else{?>
									<a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_post_thumbnail($post->ID, 'noticias_destacada')?></a>
								<?php }	
							}else{?>
								<a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_post_thumbnail($post->ID, 'noticias_destacada')?></a>
							<?php }?>
							<h3><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_title($post->ID)?></a></h3>
							<span class="fecha"><?php echo get_the_time('l d F, Y', $post->ID)?></span>
							<p><?php echo get_the_excerpt($post->ID)?><?php if($tipoPost[0]->slug == 'en-vivo' | $tipoPost[0]->slug == 'minuto-a-minuto'){ echo '<img src="'.get_bloginfo('template_directory').'/images/balon.gif" style="display:inline; margin-top:-5px" alt="" />';}?></p>
							<div class="morelink"><a href="<?php echo get_permalink($post->ID)?>">Ver más</a></div>
								
							<?php echo '</div>';
						}else{
					
					
					
						
						
						if($countposts %2 == 1){ $lastcolumn = 'column-last';};
						echo '<div class="column column-1-3 post '.$lastcolumn.'">'; ?>
						
						<?php $tipoPost = wp_get_post_terms( $post->ID, 'tipo');
						if($tipoPost[0]->slug == 'noticias'){
							$equipo = wp_get_post_terms( $post->ID, 'equipo');
							$link = get_term_link( $equipo[0]->slug, 'equipo' );
							echo '<a href="'.$link.'" class="categoriapost">'.$equipo[0]->name.'</a>';
						}else{
							$link = get_term_link( $tipoPost[0]->slug, 'tipo' );
							echo '<a href="'.$link.'" class="categoriapost">'.$tipoPost[0]->name.'</a>';
						};
						?>
						
						<a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_post_thumbnail($post->ID, 'noticias')?></a>
						<h3><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_title($post->ID)?></a></h3>
						<span class="fecha"><?php echo get_the_time('l d F, Y', $post->ID)?></span>
						<p><?php echo get_the_excerpt($post->ID)?><?php if($tipoPost[0]->slug == 'en-vivo' | $tipoPost[0]->slug == 'minuto-a-minuto'){ echo '<img src="'.get_bloginfo('template_directory').'/images/balon.gif" style="display:inline; margin-top:-5px" alt="" />';}?></p>
						<div class="morelink"><a href="<?php echo get_permalink($post->ID)?>">Ver más</a></div>
						<?php echo '</div>';
						
						}
					endforeach;	
					echo '<div class="clear separator"></div>';
					if(function_exists('wp_paginate')) {
						wp_paginate();
					} 
					echo '<div class="clear separator"></div>';
				}else{?>
					<div class="post 404">
						<img src="<?php bloginfo('template_directory')?>/images/404.jpg" alt="" width="630" />
						<h1>Lo sentímos</h1>
						<p>Si Caszely se perdió un penal, que a nosotros se nos pierda una noticia es nada</p>
						<p>Puedes intentar buscando nuevamente lo que necesitas desde acá:</p>
						
						<div class="search">
							<form method="get" id="searchform" action="<?php bloginfo('url')?>">
								<label class="hidden" for="s"></label>
								<span class="glyphicon glyphicon-search"></span>
								<input type="text" placeholder="Buscar..." value="" name="s" id="s">
								<!--<input type="submit" id="searchsubmit" value=""> -->
							</form>
						</div>
					</div>
				<?php }?>
				
				
			</div>
			
			<div id="sidebar" class="column column-1-3 column-last">
				
				<?php get_template_part('sidebar')?>
				
			</div>
			
		</div>
	</div>
</div>




<?php get_footer()?>
<?php }?>