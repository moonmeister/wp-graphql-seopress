<?php

/**
 * Plugin Name:     WPGraphQL for SEOPress
 * Plugin URI:      https://github.com/moonmeister/wp-graphql-seopress
 * Description:     A WPGraphQL Extension that adds support for SEOPress
 * Author:          Alex Moon
 * Author URI:      https://moonmeister.net
 * Text Domain:     wp-graphql-seopres
 * Domain Path:     /languages
 * Version:         1.1.1
 * Requires PHP:    7.0
 * License:         GPL-3
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package wp-graphql-seopress
 */

namespace WPGraphQL\Extensions\SEOPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}


use WPGraphQL\AppContext;

use WPGraphQL\Data\DataSource;

add_action(
	'graphql_register_types',
	function () {
		$post_types = \WPGraphQL::get_allowed_post_types();
		$taxonomies = \WPGraphQL::get_allowed_taxonomies();

		register_graphql_object_type(
			'SEOPress',
			array(
				'fields' => array(
					'canonicalUrl'         => array( 'type' => 'String' ),
					'metaTitle'            => array( 'type' => 'String' ),
					'metaDesc'             => array( 'type' => 'String' ),
					'metaRobotsNoindex'    => array( 'type' => 'String' ),
					'metaRobotsNofollow'   => array( 'type' => 'String' ),
					'opengraphTitle'       => array( 'type' => 'String' ),
					'opengraphDescription' => array( 'type' => 'String' ),
					'opengraphImage'       => array( 'type' => 'MediaItem' ),
					'twitterTitle'         => array( 'type' => 'String' ),
					'twitterDescription'   => array( 'type' => 'String' ),
					'twitterImage'         => array( 'type' => 'MediaItem' ),
				),
			)
		);

		if ( ! empty( $post_types ) && is_array( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				$post_type_object = get_post_type_object( $post_type );

				if ( isset( $post_type_object->graphql_single_name ) ) :
					register_graphql_field(
						$post_type_object->graphql_single_name,
						'seo',
						array(
							'type'        => 'SEOPress',
							'description' => __( "The SEOPress data of the {$post_type_object->graphql_single_name}", 'wp-graphql' ),
							'resolve'     => function ( $post, array $args, AppContext $context ) {

								// Base array.
								$seo = array();

								// Get data.
								$seo = array(
									'canonicalUrl'         => trim( get_post_meta( $post->ID, '_seopress_robots_canonical', true ) ),
									'metaTitle'            => trim( get_post_meta( $post->ID, '_seopress_titles_title', true ) ),
									'metaDesc'             => trim( get_post_meta( $post->ID, '_seopress_titles_desc', true ) ),
									'metaRobotsNoindex'    => trim( get_post_meta( $post->ID, '_seopress_robots_index', true ) ),
									'metaRobotsNofollow'   => trim( get_post_meta( $post->ID, '_seopress_robots_follow', true ) ),
									'opengraphTitle'       => trim( get_post_meta( $post->ID, '_seopress_social_fb_title', true ) ),
									'opengraphDescription' => trim( get_post_meta( $post->ID, '_seopress_social_fb_desc', true ) ),
									'opengraphImage'       => DataSource::resolve_post_object( get_post_meta( $post->ID, '_seopress_social_fb_img', true ), $context ),
									'twitterTitle'         => trim( get_post_meta( $post->ID, '_seopress_social_twitter_title', true ) ),
									'twitterDescription'   => trim( get_post_meta( $post->ID, '_seopress_social_twitter_desc', true ) ),
									'twitterImage'         => DataSource::resolve_post_object( get_post_meta( $post->ID, '_seopress_social_twitter_img', true ), $context ),
								);

								return ! empty( $seo ) ? $seo : null;
							},
						)
					);
				endif;
			}
		}

		if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
			foreach ( $taxonomies as $tax ) {

				$taxonomy = get_taxonomy( $tax );

				if ( isset( $taxonomy->graphql_single_name ) ) :
					register_graphql_field(
						$taxonomy->graphql_single_name,
						'seo',
						array(
							'type'        => 'SEOPress',
							'description' => __( "The SEOPress data of the {$taxonomy->label} taxonomy.", 'wp-graphql' ),
							'resolve'     => function ( $term, array $args, AppContext $context ) {

								// Get data.
								$seo = array(
									'metaTitle'            => trim( get_term_meta( $term->term_id, '_seopress_titles_title', true ) ),
									'canonicalUrl'         => trim( get_term_meta( $term->term_id, '_seopress_robots_canonical', true ) ),
									'metaDesc'             => trim( get_term_meta( $term->term_id, '_seopress_titles_desc', true ) ),
									'metaRobotsNoindex'    => trim( get_term_meta( $term->term_id, '_seopress_robots_index', true ) ),
									'metaRobotsNofollow'   => trim( get_term_meta( $term->term_id, '_seopress_robots_follow', true ) ),
									'opengraphTitle'       => trim( get_term_meta( $term->term_id, '_seopress_social_fb_title', true ) ),
									'opengraphDescription' => trim( get_term_meta( $term->term_id, '_seopress_social_fb_desc', true ) ),
									'opengraphImage'       => DataSource::resolve_term_object( get_term_meta( $term->term_id, '_seopress_social_fb_img', true ), $context ),
									'twitterTitle'         => trim( get_term_meta( $term->term_id, '_seopress_social_twitter_title', true ) ),
									'twitterDescription'   => trim( get_term_meta( $term->term_id, '_seopress_social_twitter_desc', true ) ),
									'twitterImage'         => DataSource::resolve_term_object( get_term_meta( $term->term_id, '_seopress_social_twitter_img', true ), $context ),
								);

								return ! empty( $seo ) ? $seo : null;
							},
						)
					);
				endif;
			}
		}
	}
);




// adding settings pages to WPGraphQL
add_action(
	'graphql_register_types',
	function() {
		register_graphql_field(
			'RootQuery',
			'seoPressSettings',
			array(
				'type'    => 'SEOPressSettings',
				'resolve' => function() {
					return true;
				},
			)
		);

		register_graphql_object_type(
			'SEOPressSettings',
			array(
				'description' => 'All SEOPress admin settings pages.',
				'fields'      => array(
					'titlesMetas' => array(
						'type'    => 'SEOPressSettings_TitlesMetas',
						'resolve' => function() {
							return get_option( 'seopress_titles_option_name' );
						},
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TitlesMetas',
			array(
				'description' => __( 'Manage all your titles & metas for post types, taxonomies, archives...', 'wpgraphql_seopress' ),
				'fields'      => array(
					'seperator'       => array(
						'type'        => 'String',
						'description' => 'Use this separator with %%sep%% in your title and meta description.',
						'resolve'     => function( $options ) {
							$result = $options['seopress_titles_sep'];
							return isset( $result ) ? $result : null;
						},
					),
					'home'            => array(
						'type'        => 'SEOPressSettings_TitlesMetas_Home',
						'description' => 'Customize your title & meta description for homepage',
						'resolve'     => function ( $data ) {
							return $data;
						},
					),
					'singlePostTypes' => array(
						'type'        => 'SEOPressSettings_TitlesMetas_SinglePostTypes',
						'description' => 'Customize your titles & metas for Single Custom Post Types',
						'resolve'     => function ( $data ) {
							return $data;
						},
					),
					'archives'        => array(
						'type'        => 'SEOPressSettings_TitlesMetas_Archives',
						'description' => 'Customize your metas for all archives',
						'resolve'     => function ( $data ) {
							return $data;
						},
					),
					'taxonomies'      => array(
						'type'        => 'SEOPressSettings_TitlesMetas_Taxonomies',
						'description' => 'Customize your metas for all taxonomies archives',
						'resolve'     => function ( $data ) {
							return $data;
						},
					),
					'advanced'        => array(
						'type'        => 'SEOPressSettings_TitlesMetas_Advanced',
						'description' => 'Customize your metas for all pages',
						'resolve'     => function ( $data ) {
							return $data;
						},
					),

				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TitlesMetas_Home',
			array(
				'fields' => array(
					'title'       => array(
						'type'        => 'String',
						'description' => 'meta title for homepage.',
						'resolve'     => function( $options ) {
							$result = $options['seopress_titles_home_site_title'];
							return isset( $result ) ? $result : null;
						},
					),
					'description' => array(
						'type'        => 'String',
						'description' => 'meta description for homepage.',
						'resolve'     => function( $options ) {
							$result = $options['seopress_titles_home_site_desc'];
							return isset( $result ) ? $result : null;
						},
					),
				),
			)
		);
	},
	10
);
