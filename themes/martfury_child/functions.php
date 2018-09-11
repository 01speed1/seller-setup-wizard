<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'ionicons','eleganticons','bootstrap' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION


/** 
* @snippet       Show Custom Filter @ WooCommerce Products Admin
* @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055 
* @sourcecode    https://businessbloomer.com/?p=78136
* @author        Rodolfo Melogli 
* @compatible    WooCommerce 3.4.4 
*/
 
#add_filter( 'woocommerce_product_filters', 'bbloomer_filter_by_custom_taxonomy_dashboard_products' );
 
#if ( ! function_exists( 'bbloomer_filter_by_custom_taxonomy_dashboard_products' ) ) {
# function bbloomer_filter_by_custom_taxonomy_dashboard_products( $output ) {
#  
#   global $wp_query;
# 
#   $output .= wc_product_dropdown_categories( array(
#     'show_option_none' => 'Filter by product tag',
#     'taxonomy' => 'product_tag',
#     'name' => 'product_tag',
#     'selected' => isset( $wp_query->query_vars['product_tag'] ) ? $wp_query->query_vars['product_tag'] : '',
#   ) );
#    
#   return $output;
# }
#}


/**
 * Custom EndPoint to get Dokan Vendor Info with Order_id
 * @author Oscar Guzman - Procibernetica
 *
 */

/*  // function to get Vendor Info
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
 */

/* //add api URL
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
} */

/* //add api endpoint 
add_action( 'rest_api_init', 'custom_vendor_info_route' );
 */

/**
 * Custom EndPoint to get new Dokan Vendors
 * @author Oscar Guzman - Procibernetica
 *
 */

//1. fn para consultar ultimos uarios creado a partir de una fecha  
function custom_wp_users(){
	# code...
	$args = array(
		'number' => 5,
		'orderby' => 'registered',
		'order' => 'DESC',
		'role' => 'seller' );

	$users =  get_users($args);

	$sellers = array();
	foreach ($users as $user) {

		$vendor_user = dokan_get_store_info( $user->id );
		$tempUser = array();

		$tempUser = array(
		'id' => $user->id,
		'store_name' => $vendor_user['store_name'],
		'street_1' => $vendor_user['address']['street_1'],
		'city' => $vendor_user['address']['city'],
		'phone' => $vendor_user['phone'],
		'email' => $user->user_email,
		'name' => $user->display_name
		);

		$sellers[] = $tempUser;
		
	}
	
	return $sellers;
}

//2. add api URL
function custom_wp_users_route(){
	# code...
	register_rest_route( 'custom/v1', '/users', array(
		'methods'	=> 'GET',
		'callback' 	=> 'custom_wp_users'
 	));
}

//3. add api endpoint 
add_action( 'rest_api_init', 'custom_wp_users_route');

//************************TESTING*************************** */

//funcion de prueba, si ves esto por fa borrarolo :3 
function custom_wp_meta_user($data){
	# code...
	/* $args = array(
		'number' => 100,
		'orderby' => 'registered',
		'order' => 'DESC',
		//'role' => 'seller' 
	); */

	//get_user_meta
	$user =  get_user_meta($data['id']);
	return $user;
}

function custom_wp_users_all_route(){
	# code...
	register_rest_route( 'custom/v1', '/meta-user/(?P<id>\d+)', array(
		'methods'	=> 'GET',
		'callback' 	=> 'custom_wp_meta_user',
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

add_action( 'rest_api_init', 'custom_wp_users_all_route');


/**END testin**************** */