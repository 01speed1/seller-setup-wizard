<?php
remove_filter('template_redirect','redirect_canonical');
/**
 * DrFuri Core functions and definitions
 *
 * @package Martfury
 */


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function martfury_setup() {
	// Sets the content width in pixels, based on the theme's design and stylesheet.
	$GLOBALS['content_width'] = apply_filters( 'martfury_content_width', 840 );

	// Make theme available for translation.
	load_theme_textdomain( 'martfury', get_template_directory() . '/lang' );

	// Theme supports
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'audio', 'gallery', 'video', 'quote', 'link' ) );
	add_theme_support(
		'html5', array(
			'comment-list',
			'search-form',
			'comment-form',
			'gallery',
		)
	);

	add_editor_style( 'css/editor-style.css' );

	// Register theme nav menu
	$nav_menu = array(
		'primary'         => esc_html__( 'Primary Menu', 'martfury' ),
		'mobile'          => esc_html__( 'Mobile Menu', 'martfury' ),
		'shop_department' => esc_html__( 'Shop By Department Menu', 'martfury' ),
		'user_logged'     => esc_html__( 'User Logged Menu', 'martfury' ),
	);
	if ( martfury_has_vendor() ) {
		$nav_menu['vendor_logged'] = esc_html__( 'Vendor Logged Menu', 'martfury' );
	}
	register_nav_menus( $nav_menu );

	add_image_size( 'martfury-blog-grid', 380, 300, true );
	add_image_size( 'martfury-blog-list', 790, 510, true );
	add_image_size( 'martfury-blog-masonry', 370, 588, false );

	global $martfury_woocommerce;
	$martfury_woocommerce = new Martfury_WooCommerce;

	global $martfury_dokan;
	$martfury_dokan = new Martfury_Dokan;

	global $martfury_wcvendors;
	$martfury_wcvendors = new Martfury_WCVendors;

	global $martfury_dcvendors;
	$martfury_dcvendors = new Martfury_DCVendors;

}

add_action( 'after_setup_theme', 'martfury_setup', 100 );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function martfury_register_sidebar() {
	// Register primary sidebar
	$sidebars = array(
		'blog-sidebar'    => esc_html__( 'Blog Sidebar', 'martfury' ),
		'topbar-left'     => esc_html__( 'Topbar Left', 'martfury' ),
		'topbar-right'    => esc_html__( 'Topbar Right', 'martfury' ),
		'topbar-mobile'   => esc_html__( 'Topbar on Mobile', 'martfury' ),
		'header-bar'      => esc_html__( 'Header Bar', 'martfury' ),
		'post-sidebar'    => esc_html__( 'Single Post Sidebar', 'martfury' ),
		'page-sidebar'    => esc_html__( 'Page Sidebar', 'martfury' ),
		'catalog-sidebar' => esc_html__( 'Catalog Sidebar', 'martfury' ),
		'product-sidebar' => esc_html__( 'Single Product Sidebar', 'martfury' ),
		'footer-links'    => esc_html__( 'Footer Links', 'martfury' ),
	);

	if ( class_exists( 'WC_Vendors' ) || class_exists( 'WCMp' ) ) {
		$sidebars['vendor_sidebar'] = esc_html( 'Vendor Sidebar', 'martfury' );
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 6; $i++ ) {
		$sidebars["footer-sidebar-$i"] = esc_html__( 'Footer', 'martfury' ) . " $i";
	}

	$custom_sidebar = martfury_get_option( 'custom_product_cat_sidebars' );
	if ( $custom_sidebar ) {
		foreach ( $custom_sidebar as $sidebar ) {
			$title                                = $sidebar['title'];
			$sidebars[ sanitize_title( $title ) ] = $title;
		}
	}

	// Register sidebars
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	unregister_widget( 'WC_Widget_Layered_Nav' );
	unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
	unregister_widget( 'WC_Widget_Rating_Filter' );

}

add_action( 'widgets_init', 'martfury_register_sidebar' );

/**
 * Load theme
 */

// customizer hooks
require get_template_directory() . '/inc/backend/customizer.php';

// Widgets
require get_template_directory() . '/inc/widgets/widgets.php';

// layout
require get_template_directory() . '/inc/functions/layout.php';

require get_template_directory() . '/inc/functions/entry.php';

// Woocommerce
require get_template_directory() . '/inc/frontend/woocommerce.php';

// Vendor
require get_template_directory() . '/inc/frontend/dokan.php';
require get_template_directory() . '/inc/frontend/wc_vendors.php';
require get_template_directory() . '/inc/frontend/dc_vendors.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/backend/plugins.php';
	require get_template_directory() . '/inc/backend/meta-boxes.php';
	require get_template_directory() . '/inc/backend/product-cat.php';
	require get_template_directory() . '/inc/backend/product-meta-box-data.php';
	require get_template_directory() . '/inc/backend/woocommerce.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu.php';
} else {
	// Frontend functions and shortcodes
	require get_template_directory() . '/inc/functions/media.php';
	require get_template_directory() . '/inc/functions/nav.php';
	require get_template_directory() . '/inc/functions/header.php';
	require get_template_directory() . '/inc/functions/breadcrumbs.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu-walker.php';
	require get_template_directory() . '/inc/functions/comments.php';
	require get_template_directory() . '/inc/functions/footer.php';

	// Frontend hooks
	require get_template_directory() . '/inc/frontend/layout.php';
	require get_template_directory() . '/inc/frontend/header.php';
	require get_template_directory() . '/inc/frontend/nav.php';
	require get_template_directory() . '/inc/frontend/entry.php';
	require get_template_directory() . '/inc/frontend/footer.php';
}


/** 
* @snippet       Show Custom Filter @ WooCommerce Products Admin
* @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055 
* @sourcecode    https://businessbloomer.com/?p=78136
* @author        Rodolfo Melogli 
* @compatible    WooCommerce 3.4.4 
*/
 
add_filter( 'woocommerce_product_filters', 'bbloomer_filter_by_custom_taxonomy_dashboard_products' );
 
function bbloomer_filter_by_custom_taxonomy_dashboard_products( $output ) {
   
  global $wp_query;
 
  $output .= wc_product_dropdown_categories( array(
    'show_option_none' => 'Filter by product tag',
    'taxonomy' => 'product_tag',
    'name' => 'product_tag',
    'selected' => isset( $wp_query->query_vars['product_tag'] ) ? $wp_query->query_vars['product_tag'] : '',
  ) );
   
  return $output;
}



/**
 * Custom EndPoint to get Dokan Vendor Info with Order_id
 * @author Oscar Guzman
 *
 */

 // function to get Vendor Info
function custom_dokan_vendor_info_by_order_id( $data ){
	# code...
	$vendor_id = dokan_get_seller_id_by_order( $data['id'] );
	$vendor_user = dokan_get_store_info( $vendor_id );

	$vuser = get_user_by('id', $vendor_id);

	$res = array(
	'id' => $vendor_id,
	'store_name' => $vendor_user['store_name'],
	'street_1' => $vendor_user['address']['street_1'],
	'city' => $vendor_user['address']['city'],
	'phone' => $vendor_user['phone'],
	'email' => $vuser->user_email,
	'name' => $vuser->display_name
	 );
	return $res;

}


//add api URL
function custom_vendor_info_route (){
	# code...
	register_rest_route( 'custom/v1', '/vendor/order/(?P<id>\d+)', array(
		'methods'	=> 'GET',
		'callback' 	=> 'custom_dokan_vendor_info_by_order_id',
		'args' 		=>  array(
			'id'	=> array(
				'validate_callback' => function ($param, $req, $key) {
					# code...
					return is_numeric( $param );
				}
			)
		)
 	));
}

//add api endpoint 
add_action( 'rest_api_init', 'custom_vendor_info_route' );


