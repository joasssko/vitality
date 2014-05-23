<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page');

}else{?>

<?php get_header()?>

<div id="main">
	<div class="container">
		<div class="row">
			<h1><?php echo $post->post_title;?></h1>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; else: ?>
Sorry, no posts matched your criteria.
<?php endif; ?>
		</div>
	</div>
</div>


<?php get_footer();?>

<?php }?>