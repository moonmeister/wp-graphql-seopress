<?php // phpcs:ignore

/**
 * Plugin Name:     WPGraphQL for SEOPress
 * Plugin URI:      https://github.com/moonmeister/wp-graphql-seopress
 * Description:     A WPGraphQL Extension that adds support for SEOPress
 * Author:          Alex Moon
 * Author URI:      https://moonmeister.net
 * Text Domain:     wp-graphql-seopres
 * Domain Path:     /languages
 * Version:         1.2.0
 * Requires PHP:    7.0
 * License:         GPL-3
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package wp-graphql-seopress
 */

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
				'description' => __( 'The SEOPress schema data', 'wp-graphql' ),
				'fields'      => array(
					'canonicalUrl'         => array(
						'type'        => 'String',
						'description' => 'The preferred URL for the page.',
					),
					'metaTitle'            => array(
						'type'        => 'String',
						'description' => 'The preferred title for the page.',
					),
					'metaDesc'             => array(
						'type'        => 'String',
						'description' => 'The preferred description for the page.',
					),
					'metaRobotsNoindex'    => array(
						'type'        => 'String',
						'description' => 'Should robots skip indexing this page. Returns `yes` if Noindex',
					),
					'metaRobotsNofollow'   => array(
						'type'        => 'String',
						'description' => 'Should robots skip following linked pages. Returns `yes` if Noindex',
					),
					'opengraphTitle'       => array(
						'type'        => 'String',
						'description' => 'The preferred OpenGraph title for the page.',
					),
					'opengraphDescription' => array(
						'type'        => 'String',
						'description' => 'The preferred OpenGraph description for the page.',
					),
					'opengraphImage'       => array(
						'type'        => 'MediaItem',
						'description' => 'The preferred OpenGraph image for the page.',
					),
					'twitterTitle'         => array(
						'type'        => 'String',
						'description' => 'The preferred Twitter title for the page.',
					),
					'twitterDescription'   => array(
						'type'        => 'String',
						'description' => 'The preferred Twitter description for the page.',
					),
					'twitterImage'         => array(
						'type'        => 'MediaItem',
						'description' => 'The preferred Twitter image for the page.',
					),
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
									'opengraphImage'       => $context->get_loader( 'post' )->load_deferred( get_post_meta( $post->ID, '_seopress_social_fb_img', true ) ),
									'twitterTitle'         => trim( get_post_meta( $post->ID, '_seopress_social_twitter_title', true ) ),
									'twitterDescription'   => trim( get_post_meta( $post->ID, '_seopress_social_twitter_desc', true ) ),
									'twitterImage'         => $context->get_loader( 'post' )->load_deferred( get_post_meta( $post->ID, '_seopress_social_twitter_img', true ) ),
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
									'opengraphImage'       => $context->get_loader( 'post' )->load_deferred( get_term_meta( $term->term_id, '_seopress_social_fb_img', true ), $context ),
									'twitterTitle'         => trim( get_term_meta( $term->term_id, '_seopress_social_twitter_title', true ) ),
									'twitterDescription'   => trim( get_term_meta( $term->term_id, '_seopress_social_twitter_desc', true ) ),
									'twitterImage'         => $context->get_loader( 'post' )->load_deferred( get_term_meta( $term->term_id, '_seopress_social_twitter_img', true ), $context ),
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
