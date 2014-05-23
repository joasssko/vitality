<?php get_template_part('mobile/header')?>

<div class="separator"></div>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
			<h2><?php echo $post->post_title;?></h2>
			<?php echo apply_filters('the_content' , $post->post_content)?>
				
			
			</div>
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>