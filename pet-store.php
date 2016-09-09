<?php
/*
Plugin Name: Pet Store
Plugin URI:  https://github.com/felixarntz/pet-store
Description: Adds a pet store to your WordPress website. Example plugin for the Post Types Definitely library.
Version:     1.0.0
Author:      Felix Arntz
Author URI:  https://leaves-and-love.net
License:     GNU General Public License v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: pet-store
Tags:        pet store, pets, post type, post types definitely
*/

defined( 'ABSPATH' ) || exit;

function pet_store_add_content( $wpptd ) {
	$taxonomies = array(
		'ps_pet_species'     => array(
			'title'              => _x( 'Pet Species', 'plural title', 'pet-store' ),
			'singular_title'     => _x( 'Pet Species', 'singular title', 'pet-store' ),
			'public'             => true,
			'hierarchical'       => true,
			'show_tagcloud'      => false,
		),
		'ps_pet_properties'  => array(
			'title'              => __( 'Pet Properties', 'pet-store' ),
			'singular_title'     => __( 'Pet Property', 'pet-store' ),
			'public'             => true,
			'hierarchical'       => false,
		),
	);

	$post_types = array(
		'ps_pet'             => array(
			'title'              => __( 'Pets', 'pet-store' ),
			'singular_title'     => __( 'Pet', 'pet-store' ),
			'public'             => true,
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'table_columns'      => array(),
			'row_actions'        => array(),
			'bulk_actions'       => array(),
			'metaboxes'          => array(
				'basic'              => array(
					'title'              => __( 'Basic Information', 'pet-store' ),
					'description'        => __( 'In this section you can specify basic data for the pet.', 'pet-store' ),
					'context'            => 'normal',
					'priority'           => 'high',
					'fields'             => array(),
				),
				'store_info'         => array(
					'title'              => __( 'Store Information', 'pet-store' ),
					'description'        => __( 'In this section you can enter store-related information for this pet.', 'pet-store' ),
					'context'            => 'side',
					'priority'           => 'high',
					'fields'             => array(),
				),
			),
			'taxonomies'         => $taxonomies,
		),
	);

	$components = array(
		'pet_store_menu' => array(
			'label'          => __( 'Pet Store', 'pet-store' ),
			'icon'           => plugin_dir_url( __FILE__ ) . 'images/dog-paw.png',
			'position'       => 30,
			'post_types'     => $post_types,
		),
	);

	$wpptd->add_components( $components, 'pet_store' );
}
add_action( 'wpptd', 'pet_store_add_content', 10, 1 );

function pet_store_load_textdomain() {
	load_plugin_textdomain( 'pet-store', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'pet_store_load_textdomain' );
