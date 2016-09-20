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

/**
 * Loads the textdomain for the plugin.
 *
 * @since 1.0.0
 */
function pet_store_load_textdomain() {
	load_plugin_textdomain( 'pet-store', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'pet_store_load_textdomain' );

/**
 * Defines all admin content for the plugin.
 *
 * This includes the overall menu plus all contained post types and taxonomies.
 *
 * @since 1.0.0
 *
 * @param WPPTD\App $wpptd Class instance of the Post Types Definitely plugin.
 */
function pet_store_add_content( $wpptd ) {
	/* Help tab for the Pet Species taxonomy. */
	$pet_species_help = array(
		'tabs'      => array(
			'hierarchy' => array(
				'title'     => __( 'Hierarchy', 'pet-store' ),
				'content'   => '<p>' . __( 'Since you can specify pet species hierarchically, it makes sense to specify the overall species (i.e. Dog or Cat) as top-level species and then specify the respective races (i.e. Labrador or Chow-Chow) as sub-species.', 'pet-store' ) . '</p>',
			),
		),
		'sidebar'   => '<p>' . sprintf( __( 'For more information, please check out the <a href="%s">GitHub repository of the plugin</a>.', 'pet-store' ), 'https://github.com/felixarntz/pet-store' ),
	);

	/* Taxonomy definition of the Pet Species and Pet Properties taxonomies. */
	$taxonomies = array(
		'ps_pet_species'     => array(
			'title'              => _x( 'Pet Species', 'plural title', 'pet-store' ),
			'singular_title'     => _x( 'Pet Species', 'singular title', 'pet-store' ),
			'title_gender'       => _x( 'n', 'gender of the term "pet species"', 'pet-store' ),
			'public'             => true,
			'hierarchical'       => true,
			'show_tagcloud'      => false,
			'help'               => $pet_species_help,
			'list_help'          => $pet_species_help,
		),
		'ps_pet_properties'  => array(
			'title'              => __( 'Pet Properties', 'pet-store' ),
			'singular_title'     => __( 'Pet Property', 'pet-store' ),
			'title_gender'       => _x( 'n', 'gender of the term "pet property"', 'pet-store' ),
			'public'             => true,
			'hierarchical'       => false,
		),
	);

	/* Post type definition of the Pets and Customers post types. */
	$post_types = array(
		'ps_pet'                 => array(
			'title'                  => __( 'Pets', 'pet-store' ),
			'singular_title'         => __( 'Pet', 'pet-store' ),
			'title_gender'           => _x( 'n', 'gender of the term "pet"', 'pet-store' ),
			'public'                 => true,
			'hierarchical'           => false,
			'supports'               => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'             => $taxonomies,
			'table_columns'          => array(
				'taxonomy-ps_pet_species'=> array(
					'filterable'             => true,
				),
				'meta-date_of_birth'     => array(
					'sortable'               => true,
				),
				'meta-purchase_status'   => array(
					'filterable'             => true,
				),
				'date'                   => false,
			),
			'row_actions'            => array(),
			'metaboxes'              => array(
				'basic'                  => array(
					'title'                  => __( 'Basic Information', 'pet-store' ),
					'description'            => __( 'In this section you can specify basic data for the pet.', 'pet-store' ),
					'context'                => 'normal',
					'priority'               => 'high',
					'fields'                 => array(
						'gender'                 => array(
							'title'                  => __( 'Gender', 'pet-store' ),
							'type'                   => 'select',
							'options'                => array(
								'male'                   => __( 'Male', 'pet-store' ),
								'female'                 => __( 'Female', 'pet-store' ),
							),
							'required'               => true,
						),
						'date_of_birth'          => array(
							'title'                  => __( 'Date of Birth', 'pet-store' ),
							'description'            => __( 'Specify when this pet was born.', 'pet-store' ),
							'type'                   => 'date',
							'required'               => true,
							'max'                    => current_time( 'Ymd' ),
						),
					),
				),
				'store_info'             => array(
					'title'                  => __( 'Store Information', 'pet-store' ),
					'description'            => __( 'In this section you can enter store-related information for this pet.', 'pet-store' ),
					'context'                => 'side',
					'priority'               => 'high',
					'fields'                 => array(
						'purchase_status'        => array(
							'title'                  => __( 'Purchase Status', 'pet-store' ),
							'description'            => __( 'Specify whether this pet is available for sale, whether it is reserved for someone or whether it has been sold.', 'pet-store' ),
							'type'                   => 'select',
							'options'                => pet_store_get_purchase_statuses(),
						),
						'reserved_for'           => array(
							'title'                  => __( 'Reserved for Customer', 'pet-store' ),
							'description'            => __( 'If the pet is reserved for someone, select that person here.', 'pet-store' ),
							'type'                   => 'select',
							'options'                => array(
								'posts'                  => 'ps_customer',
							),
						),
					),
				),
			),
		),
		'ps_customer'            => array(
			'title'                  => __( 'Customers', 'pet-store' ),
			'singular_title'         => __( 'Customer', 'pet-store' ),
			'title_gender'           => _x( 'm', 'gender of the term "customer"', 'pet-store' ),
			'public'                 => false,
			'show_ui'                => true,
			'show_add_new_in_menu'   => false,
			'hierarchical'           => false,
			'supports'               => array( 'title' ),
			'table_columns'          => array(
				'meta-address'           => array(
					'sortable'               => true,
				),
				'meta-email_address'     => array(
					'sortable'               => true,
				),
				'date'                   => false,
			),
			'metaboxes'              => array(
				'basic'                  => array(
					'title'                  => __( 'Basic Information', 'pet-store' ),
					'description'            => __( 'In this section you can specify basic data for the customer.', 'pet-store' ),
					'context'                => 'normal',
					'priority'               => 'high',
					'fields'                 => array(
						'gender'                 => array(
							'title'                  => __( 'Gender', 'pet-store' ),
							'type'                   => 'select',
							'options'                => array(
								'male'                   => __( 'Male', 'pet-store' ),
								'female'                 => __( 'Female', 'pet-store' ),
							),
							'required'               => true,
						),
						'address'                => array(
							'title'                  => __( 'Address', 'pet-store' ),
							'type'                   => 'map',
							'store'                  => 'address',
						),
						'email_address'          => array(
							'title'                  => __( 'Email Address', 'pet-store' ),
							'type'                   => 'email',
						),
						'phone_number'           => array(
							'title'                  => __( 'Phone Number', 'pet-store' ),
							'type'                   => 'tel',
						),
					),
				),
			),
		),
	);

	/* Adds row actions to the pets list table to change the purchase status. */
	$purchase_statuses = pet_store_get_purchase_statuses();
	foreach ( $purchase_statuses as $value => $label ) {
		$post_types['ps_pet']['row_actions'][ 'set_status_to_' . $value ] = array(
			'title' => sprintf( __( 'Set status to %s', 'pet-store' ), $label ),
			'callback' => 'pet_store_set_purchase_status_to_' . $value,
		);
	}

	/* The overall menu definition which contains the post types. */
	$components = array(
		'pet_store_menu' => array(
			'label'          => __( 'Pet Store', 'pet-store' ),
			'icon'           => plugin_dir_url( __FILE__ ) . 'images/dog-paw.png',
			'position'       => 30,
			'post_types'     => $post_types,
		),
	);

	/* This call lets Post Types Definitely do its magic. */
	$wpptd->add_components( $components, 'pet_store' );
}
add_action( 'wpptd', 'pet_store_add_content', 10, 1 );

/**
 * Returns the possible purchase statuses for a pet.
 *
 * @since 1.0.0
 *
 * @return array The purchase statuses, as $value => $label pairs.
 */
function pet_store_get_purchase_statuses() {
	return array(
		'available' => __( 'Available', 'pet-store' ),
		'reserved'  => __( 'Reserved', 'pet-store' ),
		'sold'      => __( 'Sold', 'pet-store' ),
	);
}

/**
 * Sets the purchase status of a given pet to a specific value.
 *
 * @since 1.0.0
 *
 * @param int    $post_id ID of the pet to modify its status.
 * @param string $status  Either 'available', 'reserved' or 'sold'.
 * @return string|WP_Error Success messages on success, or error object on failure.
 */
function pet_store_set_purchase_status_to( $post_id, $status ) {
	$all_statuses = pet_store_get_purchase_statuses();
	$status_label = $all_statuses[ $status ];

	if ( update_post_meta( $post_id, 'purchase_status', $status ) ) {
		return sprintf( __( 'The status of %1$s was successfully set to %2$s.', 'pet-store' ), get_the_title( $post_id ), $status_label );
	}

	return new WP_Error( 'purchase-status-not-set', sprintf( __( 'The status of %1$s could not be set to %2$s.', 'pet-store' ), get_the_title( $post_id ), $status_label ) );
}

/**
 * Sets the purchase status of a given pet to 'available'.
 *
 * @since 1.0.0
 *
 * @param int $post_id ID of the pet to modify its status.
 * @return string|WP_Error Success messages on success, or error object on failure.
 */
function pet_store_set_purchase_status_to_available( $post_id ) {
	return pet_store_set_purchase_status_to( $post_id, 'available' );
}

/**
 * Sets the purchase status of a given pet to 'reserved'.
 *
 * @since 1.0.0
 *
 * @param int $post_id ID of the pet to modify its status.
 * @return string|WP_Error Success messages on success, or error object on failure.
 */
function pet_store_set_purchase_status_to_reserved( $post_id ) {
	return pet_store_set_purchase_status_to( $post_id, 'reserved' );
}

/**
 * Sets the purchase status of a given pet to 'sold'.
 *
 * @since 1.0.0
 *
 * @param int $post_id ID of the pet to modify its status.
 * @return string|WP_Error Success messages on success, or error object on failure.
 */
function pet_store_set_purchase_status_to_sold( $post_id ) {
	return pet_store_set_purchase_status_to( $post_id, 'sold' );
}
