<?php get_template_part('mobile/header')?>
<?php 
$var = get_queried_object();
$tipo = $var->taxonomy;
$type = $var->slug;
$typoID = $var->term_id;
?>
<?php if(get_field('custom_background' , $tipo.'_'.$typoID)){?>
<style type="text/css">
	body{ background-image:url(<?php echo get_field('custom_background' , $tipo.'_'.$typoID)?>); background-position:top center; background-repeat:no-repeat}
	#header{ background-image:none;}
</style>
<?php }?>

<div class="separator"></div>

<div class="main">
	<div class="container">
		<div class="row">
			
			<div class="inside">
				
				
				<?php 
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$posts = get_posts(array('post_type' => 'post' , $tipo=>$type, 'orderby'   => 'menu_order', 'order' => 'ASC' , 'posts_per_page' => 11 , 'paged'=> $paged));
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
									echo do_shortcode('[video width="310" height="250" mp4="'.get_field('url_de_video' , $post->ID).'"][/video]');
								}elseif($tipodevideo=='youtube'){
									echo '<iframe width="100%" height="250" src="//www.youtube.com/embed/'.get_field('youtube' , $post->ID).'?rel=0" frameborder="0" allowfullscreen></iframe>';
								}elseif($tipodevideo=='vimeo'){
									echo '<iframe src="//player.vimeo.com/video/'.get_field('vimeo' , $post->ID).'?title=0&amp;byline=0&amp;portrait=0" width="100%" height="250" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
								}else{?>
									<a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_post_thumbnail($post->ID, 'noticias_destacada' , array('style' => 'width:100%; height:auto'))?></a>
								<?php }	
							}else{?>
								<a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_post_thumbnail($post->ID, 'noticias_destacada', array('style' => 'width:100%; height:auto'))?></a>
							<?php }?>
							<h3><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_title($post->ID)?></a></h3>
							<span class="fecha"><?php echo get_the_time('l d F, Y', $post->ID)?></span>
							<p><?php echo get_the_excerpt($post->ID)?><?php if($tipoPost[0]->slug == 'en-vivo' | $tipoPost[0]->slug == 'minuto-a-minuto'){ echo '<img src="'.get_bloginfo('template_directory').'/images/balon.gif" style="display:inline; margin-top:-5px" alt="" />';}?></p>
							<div class="morelink"><a href="<?php echo get_permalink($post->ID)?>">Ver más</a></div>
								
							<?php echo '</div>';
						}elseif($countposts == 4){
							
							
							$banners = get_posts(array('post_type' => 'banners' , 'posiciones' => 'mobile-middle' , 'posts_per_page' => '1'));
							if($banners){
								foreach($banners as $banner):
									
									$tipo = wp_get_post_terms( $banner->ID, 'tipoBanner');
									if($tipo[0]->slug == 'mobile-banner-rectangulo-mediano-300x250'){
										if(get_field('script' , $banner->ID)){
											echo '<div class="bannerMiddleMobile">';
											echo get_field('script' , $banner->ID);	
											echo '</div>';
										}
									}
																		
								endforeach;
							}
							
						
						
						}else{
						echo '<div class="post clear">'; ?>
						
						<?php $tipoPost = wp_get_post_terms( $post->ID, 'tipo');
						if($tipoPost[0]->slug == 'noticias'){
							$equipo = wp_get_post_terms( $post->ID, 'equipo');
							$link = get_term_link( $equipo[0]->slug, 'equipo' );
							if($countposts < 4){
							echo '<a href="'.$link.'" class="categoriapost">'.$equipo[0]->name.'</a>';
							}
						}else{
							$link = get_term_link( $tipoPost[0]->slug, 'tipo' );
							if($countposts < 4){
							echo '<a href="'.$link.'" class="categoriapost">'.$tipoPost[0]->name.'</a>';
							}
						};
						?>
						
                        <?php if($countposts < 4){?>
						<a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_post_thumbnail($post->ID, 'thumbnail' , array('class'=>'alignleft' , 'style' => 'width:95px; height:auto'))?></a>
                        <?php }?>
						<?php if($countposts < 4){?>
                            <h3><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_title($post->ID)?></a></h3>
                        <?php }else{?>
                            <h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_title($post->ID)?></a><?php if($tipoPost[0]->slug == 'en-vivo' | $tipoPost[0]->slug == 'minuto-a-minuto'){ echo '<img src="'.get_bloginfo('template_directory').'/images/balon.gif" style="display:inline; margin-top:-5px" alt="" />';}?></h4>
                        <?php }?>
						<span class="fecha"><?php echo get_the_time('l d F, Y', $post->ID)?>
                        
                        <?php if($tipoPost[0]->slug == 'noticias'){
							$equipo = wp_get_post_terms( $post->ID, 'equipo');
							$link = get_term_link( $equipo[0]->slug, 'equipo' );
							if($countposts > 3){
							echo ' | <a href="'.$link.'" >'.$equipo[0]->name.'</a>';
							}
						}else{
							$link = get_term_link( $tipoPost[0]->slug, 'tipo' );
							if($countposts > 3){
							echo ' | <a href="'.$link.'" >'.$tipoPost[0]->name.'</a>';
							}
						};?>
                        
                        </span>
						<?php if($countposts < 4){?>
						<p><?php echo get_the_excerpt($post->ID)?><?php if($tipoPost[0]->slug == 'en-vivo' | $tipoPost[0]->slug == 'minuto-a-minuto'){ echo '<img src="'.get_bloginfo('template_directory').'/images/balon.gif" style="display:inline; margin-top:-5px" alt="" />';}?></p>
						<?php }?>
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
						<img src="<?php bloginfo('template_directory')?>/images/404.jpg" alt="" width="100%" />
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
			
		</div>
	</div>
</div>

<?php get_template_part('mobile/footer')?>