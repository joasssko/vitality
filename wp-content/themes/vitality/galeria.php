<div id="galeria" class="widget">
	<h4>Galería</h4>
	<div class="inner">
	
		<!--<img src="http://placehold.it/278&text=Galeria" alt=""> -->
		
		
		<ul id="galeriaslider">
			<?php 
			wp_reset_query();
			query_posts(array('posts_per_page' => 5 , 'post__not_in' => $ids, 'tipo' => 'galerias'));
			while (have_posts()) : the_post();
			?>
			<li><a href="<?php the_permalink()?>"><?php the_post_thumbnail('noticias' , array('title' => '<h5>'.get_the_title().'</h5>' ))?></a></li>			
			<?php endwhile;?>
		</ul>
		
		<style type="text/css">
			#galeriaslider li img{ width:278px !important; height:194px !important}
			/*#galeriaslider .bx-caption{ bottom: 10px;}*/
		</style>
		
	</div>
</div>