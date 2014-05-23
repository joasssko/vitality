<?php
$var = get_queried_object();
$tipo = $var->taxonomy;
$modu = $var->taxonomy;
$type = $var->slug;
$typeID = $var->term_id;
wp_reset_query();

$banners = get_posts(array('post_type' => 'banners' , 'posiciones' => 'interior' , 'posts_per_page' => '1'));
$count = 0;
foreach($banners as $banner):
	$count++;
		if(get_field('script' , $banner->ID)){;
				echo get_field('script' , $banner->ID);
				echo '<div class="separator"></div>';
		}else{
			echo get_the_post_thumbnail($banner->ID);	
	}
endforeach;
?>