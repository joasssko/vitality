<?php if ( function_exists('add_theme_support') ) {
add_theme_support('post-thumbnails');
//add_image_size('mininoticias', 100, 75, true );
//add_image_size('noticias', 300, 210, true );
//add_image_size('noticias_destacada', 630, 443, true );
//add_image_size('portada_documento', 70, 100, true ); 

}
;?>
<?php 
/* Add support for wp_nav_menu() */
function register_my_menu() {
	register_nav_menu( 'primary', 'Menú principal');
	register_nav_menu( 'secondary', 'Menú Clientes');
	register_nav_menu( 'third', 'Mobile');
	register_nav_menu( 'fourth', 'Mobile Clientes');
}
add_action( 'init', 'register_my_menu' );
?>
<?php
function import_scripts() {
	wp_register_script('jquery', get_template_directory_uri() . '/js/jquery.js');
    wp_register_script('core', get_template_directory_uri() . '/js/core.js');
}

add_action('wp_print_scripts', 'import_scripts');
?>
<?php 
function call_scripts() {
	wp_register_script('jquery', get_template_directory_uri() . '/js/jquery.js');
    wp_register_script('core', get_template_directory_uri() . '/js/core.js');

    wp_enqueue_script('jquery');
    wp_enqueue_script('core');
}    
 
add_action('wp_enqueue_scripts', 'call_scripts');

?>
<?php
//Post type register
/* 
add_action('init', 'partidos_register');
function partidos_register() {
    $args = array(
        'label' => 'Partidos',
        'singular_label' => 'Partido',
        'public' => true,
		'menu_position' => 14, 
        '_builtin' => false,
        'capability_type' => 'post',
		'has_archive' => true,
        'hierarchical' => false,
        'rewrite' => array( 'slug' => 'partidos'),
        'supports' => array('title', 'revisions', 'excerpt' )
    );

    register_post_type('partidos', $args);
    flush_rewrite_rules();
}
*/

add_action('init', 'banners_register');
function banners_register() {
    $args = array(
        'label' => 'Banners',
        'singular_label' => 'Banner',
        'public' => true,
		'menu_position' => 20, 
        '_builtin' => false,
        'capability_type' => 'page',
		'has_archive' => false,
        'hierarchical' => false,
        'rewrite' => array( 'slug' => 'banners'),
        'supports' => array('title',  'thumbnail' , 'revisions' , 'author')
    );

    register_post_type('banners', $args);
    flush_rewrite_rules();
}

register_taxonomy("posiciones", array('banners'), array("hierarchical" => true, "label" => "Posiciones", "singular_label" => "Posición", "rewrite" => true));
register_taxonomy("tipoBanner", array('banners'), array("hierarchical" => true, "label" => "Tipo Banner", "singular_label" => "Tipo", "rewrite" => true));
?>
<?php //register sidebars

/* register_sidebar(array(
  'name' => __( 'Home' ),
  'id' => 'home',
  'description' => __( 'Widgets en esta área se mostrarán en el home, utlice esta área para agregar la mini encuesta' ),
  'before_title' => '<h3>',
  'after_title' => '</h3>'
)); */

/*register_sidebar(array(
  'name' => __( 'Página interior' ),
  'id' => 'category',
  'description' => __( 'Widgets en esta área se mostrarán en las páginas interiores, utlice esta área para agregar el submenú' ),
  'before_title' => '<h2>',
  'after_title' => '</h2>'
));
*/

//add_filter('widget_text', 'do_shortcode');

?>
<?php 
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 Vistas";
    }
    return $count.' Vistas';
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
?>
<?php 
function my_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_bloginfo('template_directory').'/images/logo.png) !important; }
    </style>';
}

add_action('login_head', 'my_custom_login_logo');?>