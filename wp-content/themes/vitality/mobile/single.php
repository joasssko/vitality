<?php get_template_part('mobile/header')?>

<div class="separator"></div>

<div class="main">
	<div class="container">
		<div class="row">
        <div class="inside">
			
			
				
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php setPostViews(get_the_ID());?>
				<div class="post">
					
						<span class="fecha izq"><?php echo get_the_time('l d F, Y', $post->ID)?></span>
						<ul class="fontSize der">
							<script language="javascript">
								function emailCurrentPage(){
									window.location.href="mailto:?subject="+document.title+"&body="+escape(window.location.href);
								}
							</script>
							<li><a href="javascript:emailCurrentPage()" class="glyphicon glyphicon-envelope"></a></li>
							<li><a href="javascript:window.print()" class="glyphicon glyphicon-print"></a></li>
							<li><a href="#galerias" class="glyphicon glyphicon-picture"></a></li>
                        	<li class="fontSizeSmall">A</li>
                        	<li class="fontSizeMedium">A</li>
                        	<li class="fontSizeLarge">A</li>
                        </ul>
					
					<div class="clear border"></div>
					<h1><?php the_title()?></h1>
					
					
					
					<?php $tipo = wp_get_post_terms( $post->ID, 'tipo');; ?>
										
					<?php if($tipo[0]->slug == 'en-vivo'){?>
					
					<?php }else{?>
						<?php the_post_thumbnail('noticias_destacada' , array('style' => 'width:100%; height:auto'))?>
					<?php }?>
					
					<p class="excerpt">
						<?php echo get_the_excerpt()?>
					</p>
					
					<?php if($tipo[0]->slug == 'en-vivo'){?>
					<?php 
					$banners = get_posts(array('post_type' => 'banners' , 'posiciones' => 'mobile-mxm' , 'posts_per_page' => '1'));
					if($banners){
						foreach($banners as $banner):
							
							$tipobanner = wp_get_post_terms( $banner->ID, 'tipoBanner');
							if($tipobanner[0]->slug == 'mobile-320x50'){
								if(get_field('script' , $banner->ID)){
									echo '<div class="bannerMiddleMobile">';
									echo get_field('script' , $banner->ID);	
									echo '</div>';
								}
							}															
						endforeach;
					}
					?>
					<?php }?>
					
					<?php if($tipo[0]->slug == 'en-vivo'){?>
						<?php echo get_field('url' , $post->ID)?>					
					<?php }else{?>
						<?php the_content()?>
						
						<?php if(get_field('tabla')){;?> 
						<div id="equipo" class="widget">
	
							<div class="inner">
							<div class="head-partido" style="text-align:center">
								<div class="esc_local"><img src="<?php echo get_field('esc_local')?>" alt="" width="40" /></div>
								<div class="esc_visita"><img src="<?php echo get_field('esc_visita')?>" alt="" width="40" /></div>
								<h3 style="display:inline-block; text-align:center; margin-top:5px;"><?php echo get_field('local'); echo ' '.get_field('goles_local'); echo ' - '; echo get_field('goles_visita'); echo ' '.get_field('visita') ?></h3>
							</div>
							<div class="clear"></div>
									<ul>  
										  <li class="equipos clearfix" style="border:none">
												<div class="head_b clearfix">
													<div class="equipo_name">Goles: <?php echo get_field('incidencias')?></div>							
												</div>
												<div class="head_b inv clearfix">
													<div class="equipo_name">Torneo: <?php echo get_field('torneo')?></div>							
												</div>
												
												<div class="head_b clearfix">
													<div class="equipo_name">Estadio: <?php echo get_field('estadio')?></div>							
												</div>
												
												<div class="head_b inv clearfix">
													<div class="equipo_name">Árbitro: <?php echo get_field('arbitro')?></div>							
												</div>
												<div class="head_b clearfix">
													<div class="equipo_name">
														<span><strong><?php echo get_field('local')?></strong></span>
														<span><?php echo get_field('plantel_local')?></span>
														<span>DT: <?php echo get_field('dt_local')?></span>
														<span><strong><?php echo get_field('visita')?></strong></span>
														<span><?php echo get_field('plantel_visita')?></span>
														<span>DT: <?php echo get_field('dt_visita')?></span>
													</div>							
												</div>
												<div class="clear"></div>
										  </li>
									</ul>						
							</div>
						</div>
					<?php }?>

						
						<div class="clear border"></div>
						
						<?php 
						$tipodevideo = get_field('tipo_de_video' , $post->ID);
						if($tipodevideo=='selfhosted'){
							if(get_field('url_del_video' , $post->ID)){
								echo do_shortcode('[video width="310" height="250" mp4="'.get_field('url_del_video' , $post->ID).'"][/video]');
							}
						}elseif($tipodevideo=='youtube'){
							if(get_field('youtube' , $post->ID)){
								echo '<iframe width="100%" height="250" src="//www.youtube.com/embed/'.get_field('youtube' , $post->ID).'?rel=0" frameborder="0" allowfullscreen></iframe>';
							}
						}elseif($tipodevideo=='vimeo'){
							if(get_field('vimeo' , $post->ID)){
								echo '<iframe src="//player.vimeo.com/video/'.get_field('vimeo' , $post->ID).'?title=0&amp;byline=0&amp;portrait=0" width="100%" height="250" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
							}
						}elseif($tipodevideo=='otros'){
							if(get_field('otros' , $post->ID)){
								echo get_field('otros' , $post->ID);
							}
						}else{?>
							<a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_post_thumbnail($post->ID, 'noticias_destacada' , array('style' => 'width:100%; height:auto'))?></a>
						<?php }?>
					
					<?php $galeria = get_posts(array( 'post_type'=>'attachment' , 'post_parent' => get_the_ID()));
					if($galeria){
						$total = count($galeria);
						if($total >= 2){?>
							<div class="clear border"></div>
							<h3 id="galerias">Galería de imágenes</h3>
							<div class="galerias">
							<?php foreach($galeria as $imagen):
								$full = wp_get_attachment_image_src($imagen->ID, 'full');
								echo '<a href="'.$full[0].'" rel="shadowbox[Lugares]" title="'.get_the_title($imagen->ID).'">';
								echo wp_get_attachment_image( $imagen->ID, 'thumbnail' );
								echo '</a>';
							endforeach;
							?>
							</div>
						<?php 
						}
					}
					?>
					<?php }?>
					<div class="clear"></div>
									
				</div>
				
				<?php if($tipo[0]->slug != 'en-vivo'){?>
				<?php 
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
				?>
				<?php }?>
				
				<div class="posted-on">
				Publicado en:
					<?php 
					$tipoPost = wp_get_post_terms( $post->ID, 'tipo');
					/* if($tipoPost[0]->slug == 'noticias'){
						$equipo = wp_get_post_terms( $post->ID, 'equipo');
						$link = get_term_link( $equipo[0]->slug, 'equipo' );
						echo '<a href="'.$link.'">'.$equipo[0]->name.'</a>';
					}else{ */
						$link = get_term_link( $tipoPost[0]->slug, 'tipo' );
						echo '<a href="'.$link.'">'.$tipoPost[0]->name.'</a>';
					/* }; */
					?>
				</div>
				<div class="posted-on">
				Aparece tambien en:&nbsp;
					<?php 
					$posttags = wp_get_post_terms( $post->ID, array('equipo','campeonato','tags'));
					if($posttags){
						foreach($posttags as $posttag):
							$link = get_term_link( $posttag->slug, $posttag->taxonomy );
							echo '<a href="'.$link.'">&raquo;&nbsp;'.$posttag->name.'</a>&nbsp;';
						endforeach;
					}
					?>
				</div>
				<?php 
				$posttags = wp_get_post_terms( $post->ID, array('jugadores'));
				if($posttags){
					echo '<div class="posted-on">Jugadores relacionados: ';
					foreach($posttags as $posttag):
						$link = get_term_link( $posttag->slug, $posttag->taxonomy );
						echo '<a href="'.$link.'">&raquo;&nbsp;'.$posttag->name.'</a>&nbsp;';
					endforeach;
					echo '</div>';
				}
				?>
													
				<div class="clear separator"></div>
					
				<div class="sharebox">
					<div class="column-1-3 column facebook">
						<div class="fb-like" data-href="<?php the_permalink()?>" data-width="300" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
					</div>
					<div class="column-1-3 column column-last twitter">
						<a href="https://twitter.com/intent/tweet?button_hashtag=PrensaFutbol&text=<?php echo get_the_title()?>"  class="twitter-hashtag-button" data-via="PrensaFutbol" data-url="<?php the_permalink()?>" data-lang="es" data-related="jasoncosta">Tweet #PrensaFutbol</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

						<div class="g-plusone" data-size="medium" data-href="<?php the_permalink()?>"></div>						
					</div>
				</div>
				
				<div class="clear"></div>
				
				<div class="relacionados">
					<h3>Artículos relacionados</h3>
					<ul>
						
						<?php  
						  
						$tags = wp_get_object_terms($post->ID , 'equipo' );  
						if ($tags) {  
							$tag_ids = array();  
							foreach($tags as $individual_tag) 
							$tag_ids[] = $individual_tag->term_id;  
							$args=array(  
								'tax_query' => array(
									array(
										'taxonomy'  => 'equipo',
										'field'     => 'id',
										'terms'     => $tag_ids,
										'operator'  => 'IN'
									) 
								),
								'post__not_in' => array($post->ID),  
								'posts_per_page'=>5,
								'caller_get_posts'=>1  
								);  
						
						$my_query = get_posts( $args );  				  	
						
						if($my_query){ 
							foreach($my_query as $relacionado):
								echo '<li>';
								echo '<a href="'.get_permalink($relacionado->ID).'">'.get_the_title($relacionado->ID).'</a>';
								echo '</li>';
							endforeach;
							}
						}
						?>  
						
					</ul>
				</div>
				
				<div class="separator"></div>
				
				<div class="comentarios">
					<div class="fb-comments" data-href="<?php the_permalink()?>" data-numposts="5" data-width="630" data-colorscheme="light"></div>
				</div>
			<?php endwhile; else: ?>
			Sorry, no posts matched your criteria.
			<?php endif; ?>
				<div class="posted-on backhome">
					<a href="<?php bloginfo('url')?>">Volver al Inicio</a>
				</div>	
			</div>
			
			</div>
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>