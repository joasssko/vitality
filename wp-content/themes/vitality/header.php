<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php if(is_home()){?>
	<title><?php wp_title();?></title>
<?php }else{?>
	<title><?php wp_title();?></title>
<?php }?>

<link rel="shortcut icon" href="<?php bloginfo('template_directory')?>/favicon.ico"/>
<link rel="icon" type="image/png" href="<?php bloginfo('template_directory')?>/favicon.png" />

<meta name="viewport" content="width=device-width, initial-scale=0.75;minimum-scale=1.0; maximum-scale=1.0">

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--stylesheets -->
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.0/css/jquery.dataTables.css">
<link rel="stylesheet" href="<?php bloginfo('template_directory')?>/bootstrap/bootstrap.min.css?ver=3.8.1">
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url')?>?ver=3.8.1" />

<link href='http://fonts.googleapis.com/css?family=lato:300,400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.min.css">

<!--Otros -->
<?php wp_head()?>

<?php if(is_page( array(21,19,17,45,47,23,15,25,67))){?>
<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>


<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>

<?php }?>




<!-- scripts -->
<?php call_scripts()?>
<script type="text/javascript" src="<?php bloginfo('template_directory')?>/bootstrap/bootstrap.min.js?ver=3.8.1"></script>

<?php if(is_page(array(13,25,98 , 105))){?>
<script type="text/javascript" src="<?php bloginfo('template_directory')?>/js/supersized.js"></script>
<script type="text/javascript">
			jQuery(function($){
				$.supersized({
					slideshow               :   1,		//Slideshow on/off
					autoplay				:	1,		//Slideshow starts playing automatically
					start_slide             :   1,		//Start slide (0 is random)
					random					: 	0,		//Randomize slide order (Ignores start slide)
					slide_interval          :   8000,	//Length between transitions
					transition              :   1, 		//0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed		:	300,	//Speed of transition
					new_window				:	1,		//Image links open in new window/tab
					pause_hover             :   0,		//Pause slideshow on hover
					keyboard_nav            :   0,		//Keyboard navigation on/off
					performance				:	1,		//0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
					image_protect			:	1,		//Disables image dragging and right click with Javascript
					image_path				:	'img/', //Default image path
					min_width		        :   0,		//Min width allowed (in pixels)
					min_height		        :   0,		//Min height allowed (in pixels)
					vertical_center         :   0,		//Vertically center background
					horizontal_center       :   1,		//Horizontally center background
					fit_portrait         	:   1,		//Portrait images will not exceed browser height
					fit_landscape			:   0,		//Landscape images will not exceed browser width
					navigation              :   1,		//Slideshow controls on/off
					thumbnail_navigation    :   0,		//Thumbnail navigation
					slide_counter           :   0,		//Display slide numbers
					slide_captions          :   0,		//Slide caption (Pull from "title" in slides array)
					slides 					:  	[		//Slideshow Images
														{image : '<?php bloginfo('template_directory')?>/images/login.jpg', title : '',},  
														 
												]
				}); 
		    });
</script><?php }?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1&appId=1443699349174785";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<meta property="fb:app_id" content="480883768704042" />

<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->

</head>
<body <?php body_class();?>>

<div class="logo">
	<div class="container">
		<div class="row"><div class="col-md-4"><a href="<?php bloginfo('url')?>" ><img src="<?php bloginfo('template_directory')?>/images/logo.png" alt="" width="100%" /></a></div>
		<div class="col-md-5 col-md-offset-3"><div class="row"><img src="<?php bloginfo('template_directory')?>/images/top.png" alt="" width="100%" /></div></div></div>
	</div>
</div>

<div id="header">
	<div class="container">
		<div class="row">
			<div class="clear"></div>
			
			<div class="nav col-md-7">	
				<?php wp_nav_menu( array( 'container' => 'none', 'menu_class' => 'clearfix nav' , 'theme_location' => 'primary' ) );?>
			</div>
			
			<?php if (!isset($_SESSION['rut'])){?>
				<div class="col-md-5">
					<a href="<?php echo get_page_link('13')?>" class="login pull-right"><span class="fa fa-lock" style="height:15px;"></span> Log In</a>
				</div>
			<?php }else{?>
				<div class="col-md-5 clientt">
					<li><a href="<?php echo get_page_link('25')?>" class="pull-right login"><span class="fa fa-group" style="height:15px;"></span> Selección de paciente</a></li>
					<li>
						<a href="<?php echo get_page_link('98')?>" class="pull-right login"><span class="fa fa-sign-out" style="height:15px;"></span> Log Out</a>
						<ul class="sub-menu">
							<li><a href="<?php echo get_page_link('108')?>"><span class="fa fa-key" style="height:15px;"></span> Cambiar contraseña</a></li>
						</ul>
					</li>
				</div>
			<?php }?>
			
		</div>	
	</div>
</div>

<?php if(is_page(array(13, 25 , 98, 105))){?>

<?php }elseif(is_home()){?>
<div id="imgfull">
	<img src="<?php bloginfo('template_directory')?>/images/homehead.jpg" alt="" width="100%" />
</div>
<?php }else{?>
<div id="imgfull">
	<img src="<?php bloginfo('template_directory')?>/images/head.jpg" alt="" width="100%" />
</div>
<?php }?>

<?php if( is_page( array(21,19,17,45,47,23,15,67))){?>
<div id="client-nav">
	<div class="container">
		<div class="row">
			<div class="cliente nav">
				<?php wp_nav_menu( array( 'container' => 'none', 'menu_class' => 'clearfix nav' , 'theme_location' => 'secondary' ) );?>
			</div>
		</div>
	</div>
</div>
<?php }?>