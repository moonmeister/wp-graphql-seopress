<?php

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

		$SEOPressObject = array(
			'description' => __( 'The SEOPress schema data', 'wp-graphql' ),
			'fields'      => array(
				'canonicalUrl'              => array( 'type' => 'String', 'description' => 'The preferred URL for the page.' ),
				'metaTitle'                 => array( 'type' => 'String', 'description' => 'The preferred title for the page.' ),
				'metaDesc'                  => array( 'type' => 'String', 'description' => 'The preferred description for the page.' ),
				'metaRobotsNoindex'         => array( 'type' => 'String', 'description' => 'Should robots skip indexing this page. Returns `yes` if Noindex' ),
				'metaRobotsNofollow'        => array( 'type' => 'String', 'description' => 'Should robots skip following linked pages. Returns `yes` if Noindex' ),
				'opengraphTitle'            => array( 'type' => 'String', 'description' => 'The preferred OpenGraph title for the page.' ),
				'opengraphDescription'      => array( 'type' => 'String', 'description' => 'The preferred OpenGraph description for the page.' ),
				'opengraphImage'            => array( 'type' => 'MediaItem', 'description' => 'The preferred OpenGraph image for the page.' ),
				'twitterTitle'              => array( 'type' => 'String', 'description' => 'The preferred Twitter title for the page.' ),
				'twitterDescription'        => array( 'type' => 'String', 'description' => 'The preferred Twitter description for the page.' ),
				'twitterImage'              => array( 'type' => 'MediaItem', 'description' => 'The preferred Twitter image for the page.' ),
				'metaRobotsOdp'             => array( 'type' => 'Boolean', 'description' => 'Should meta robot tag `noodp` be included' ),
				'metaRobotsImageIndex'      => array( 'type' => 'Boolean', 'description' => 'Should meta robot tag `noimageindex` be included' ),
				'metaRobotsArchive'         => array( 'type' => 'Boolean', 'description' => 'Should meta robot tag `noarchive` be included' ),
				'metaRobotsSnippet'         => array( 'type' => 'Boolean', 'description' => 'Should meta robot tag `nosnippet` be included' ),
				'metaRobotsPrimaryCategory' => array( 'type' => 'Int', 'description' => 'The primary category ID, returns `0` if no category.' ),
				'metaRobotsBreadcrumbs'     => array( 'type' => 'String', 'description' => 'Custom breadcrumbs.' ),
				'redirectionsEnabled'       => array( 'type' => 'Boolean', 'description' => 'Does a redirection exist for this.' ),
				'redirectionsType'          => array( 'type' => 'String', 'description' => 'The type HTTP status code of the redirection.' ),
				'redirectionsURL'           => array( 'type' => 'String', 'description' => 'The URL that redirects here.' ),
				'metaNewsDisabled'          => array( 'type' => 'Boolean', 'description' => 'Should robots skip indexing this news.' ),
				'metaVideoDisabled'         => array( 'type' => 'Boolean', 'description' => 'Should robots skip indexing this video.' ),
				'metaVideo'                 => array( 'type' => 'String', 'description' => 'Array of Videos.' ),
				'targetKeywords'            => array( 'type' => 'String', 'description' => 'Target keywords separated by commas.' ),
			),
		);
		if ( get_option( 'seopress_pro_license_status' ) == 'valid' ) {
			$SEOPressObject['fields']['proSchemas'] = array( 'type' => 'String', 'description' => 'Array of Schemas' );
			$SEOPressObject['fields']['proSchemasManual'] = array( 'type' => 'String', 'description' => 'Array of Schemas' );
		}

		register_graphql_object_type( 'SEOPress', $SEOPressObject );

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
									'canonicalUrl'              => trim( get_post_meta( $post->ID, '_seopress_robots_canonical', true ) ),
									'metaTitle'                 => trim( get_post_meta( $post->ID, '_seopress_titles_title', true ) ),
									'metaDesc'                  => trim( get_post_meta( $post->ID, '_seopress_titles_desc', true ) ),
									'metaRobotsNoindex'         => trim( get_post_meta( $post->ID, '_seopress_robots_index', true ) ),
									'metaRobotsNofollow'        => trim( get_post_meta( $post->ID, '_seopress_robots_follow', true ) ),
									'opengraphTitle'            => trim( get_post_meta( $post->ID, '_seopress_social_fb_title', true ) ),
									'opengraphDescription'      => trim( get_post_meta( $post->ID, '_seopress_social_fb_desc', true ) ),
									'opengraphImage'            => $context->get_loader( 'post' )->load_deferred( get_post_meta( $post->ID, '_seopress_social_fb_img', true ) ),
									'twitterTitle'              => trim( get_post_meta( $post->ID, '_seopress_social_twitter_title', true ) ),
									'twitterDescription'        => trim( get_post_meta( $post->ID, '_seopress_social_twitter_desc', true ) ),
									'twitterImage'              => $context->get_loader( 'post' )->load_deferred( get_post_meta( $post->ID, '_seopress_social_twitter_img', true ) ),
									'metaRobotsOdp'             => trim( get_post_meta( $post->ID, '_seopress_robots_odp', true ) ) == 'yes',
									'metaRobotsImageIndex'      => trim( get_post_meta( $post->ID, '_seopress_robots_imageindex', true ) ) == 'yes',
									'metaRobotsArchive'         => trim( get_post_meta( $post->ID, '_seopress_robots_archive', true ) ) == 'yes',
									'metaRobotsSnippet'         => trim( get_post_meta( $post->ID, '_seopress_robots_snippet', true ) ) == 'yes',
									'metaRobotsPrimaryCategory' => !filter_var(get_post_meta( $post->ID, '_seopress_robots_primary_cat', true ), FILTER_VALIDATE_INT) ? 0 : get_post_meta( $post->ID, '_seopress_robots_primary_cat', true ),
									'metaRobotsBreadcrumbs'     => get_post_meta( $post->ID, '_seopress_robots_breadcrumbs', true ),
									'redirectionsEnabled'       => get_post_meta( $post->ID, '_seopress_redirections_enabled', true ) == 'yes',
									'redirectionsType'          => get_post_meta( $post->ID, '_seopress_redirections_type', true ),
									'redirectionsURL'           => get_post_meta( $post->ID, '_seopress_redirections_value', true ),
									'metaNewsDisabled'          => get_post_meta( $post->ID, '_seopress_news_disabled', true ) == 'yes',
									'metaVideoDisabled'         => get_post_meta( $post->ID, '_seopress_video_disabled', true ) == 'yes',
									'metaVideo'                 => json_encode( get_post_meta( $post->ID, '_seopress_video', true ) ),
									'targetKeywords'            => get_post_meta( $post->ID, '_seopress_analysis_target_kw', true ),
								);
								if ( get_option( 'seopress_pro_license_status' ) == 'valid' ) {
									$seo['proSchemas'] = json_encode( get_post_meta( $post->ID, '_seopress_pro_schemas', true ) );
									$seo['proSchemasManual'] = json_encode( get_post_meta( $post->ID, '_seopress_pro_schemas_manual', true ) );
								}

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
									'metaTitle'                 => trim( get_term_meta( $term->term_id, '_seopress_titles_title', true ) ),
									'canonicalUrl'              => trim( get_term_meta( $term->term_id, '_seopress_robots_canonical', true ) ),
									'metaDesc'                  => trim( get_term_meta( $term->term_id, '_seopress_titles_desc', true ) ),
									'metaRobotsNoindex'         => trim( get_term_meta( $term->term_id, '_seopress_robots_index', true ) ),
									'metaRobotsNofollow'        => trim( get_term_meta( $term->term_id, '_seopress_robots_follow', true ) ),
									'opengraphTitle'            => trim( get_term_meta( $term->term_id, '_seopress_social_fb_title', true ) ),
									'opengraphDescription'      => trim( get_term_meta( $term->term_id, '_seopress_social_fb_desc', true ) ),
									'opengraphImage'            => $context->get_loader( 'post' )->load_deferred( get_term_meta( $term->term_id, '_seopress_social_fb_img', true ), $context ),
									'twitterTitle'              => trim( get_term_meta( $term->term_id, '_seopress_social_twitter_title', true ) ),
									'twitterDescription'        => trim( get_term_meta( $term->term_id, '_seopress_social_twitter_desc', true ) ),
									'twitterImage'              => $context->get_loader( 'post' )->load_deferred( get_term_meta( $term->term_id, '_seopress_social_twitter_img', true ), $context ),
									'metaRobotsOdp'             => trim( get_post_meta( $term->ID, '_seopress_robots_odp', true ) ) == 'yes',
									'metaRobotsImageIndex'      => trim( get_post_meta( $term->ID, '_seopress_robots_imageindex', true ) ) == 'yes',
									'metaRobotsArchive'         => trim( get_post_meta( $term->ID, '_seopress_robots_archive', true ) ) == 'yes',
									'metaRobotsSnippet'         => trim( get_post_meta( $term->ID, '_seopress_robots_snippet', true ) ) == 'yes',
									'metaRobotsPrimaryCategory' => !filter_var(get_post_meta( $term->ID, '_seopress_robots_primary_cat', true ), FILTER_VALIDATE_INT) ? 0 : get_post_meta( $term->ID, '_seopress_robots_primary_cat', true ),
									'metaRobotsBreadcrumbs'     => get_post_meta( $term->ID, '_seopress_robots_breadcrumbs', true ),
									'redirectionsEnabled'       => get_post_meta( $term->ID, '_seopress_redirections_enabled', true ) == 'yes',
									'redirectionsType'          => get_post_meta( $term->ID, '_seopress_redirections_type', true ),
									'redirectionsURL'           => get_post_meta( $term->ID, '_seopress_redirections_value', true ),
									'metaNewsDisabled'          => get_post_meta( $term->ID, '_seopress_news_disabled', true ) == 'yes',
									'metaVideoDisabled'         => get_post_meta( $term->ID, '_seopress_video_disabled', true ) == 'yes',
									'metaVideo'                 => json_encode( get_post_meta( $term->ID, '_seopress_video', true ) ),
									'targetKeywords'            => get_post_meta( $term->ID, '_seopress_analysis_target_kw', true ),
								);
								if ( get_option( 'seopress_pro_license_status' ) == 'valid' ) {
									$seo['proSchemas'] = json_encode( get_post_meta( $term->ID, '_seopress_pro_schemas', true ) );
									$seo['proSchemasManual'] = json_encode( get_post_meta( $term->ID, '_seopress_pro_schemas_manual', true ) );
								}

								return ! empty( $seo ) ? $seo : null;
							},
						)
					);
				endif;
			}
		}
	}
);

// https://developer.wordpress.org/reference/functions/register_setting/
// function seopress_in_graphql( $args, $defaults, $option_group, $option_name ) {
// if ( strpos( $option_name, 'seopress_' ) !== false ) {
// $new_args                        = $args();
// $new_args['show_in_graphql']     = true;
// $new_args['graphql_single_name'] = "seoPress{$option_name}";
// $new_args['graphql_plural_name'] = "seoPress{$option_name}s";
// }
// return $args;
// }

// add_filter('register_setting_args', 'seopress_in_graphql', 10, 4);
// add_action('graphql_init', 'seopress_options');
