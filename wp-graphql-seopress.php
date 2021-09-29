<?php // phpcs:ignore

/**
 * Plugin Name:     WPGraphQL for SEOPress
 * Plugin URI:      https://github.com/moonmeister/wp-graphql-seopress
 * Description:     A WPGraphQL Extension that adds support for SEOPress
 * Author:          Alex Moon
 * Author URI:      https://moonmeister.net
 * Text Domain:     wp-graphql-seopres
 * Domain Path:     /languages
 * Version:         2.0
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

		$has_pro_license = get_option( 'seopress_pro_license_status' ) === 'valid';

		$seopress_object = array(
			'description' => __( 'The SEOPress schema data', 'wp-graphql' ),
			'fields'      => array(
				'canonicalUrl'              => array(
					'type'        => 'String',
					'description' => 'The preferred URL for the page.',
				),
				'metaTitle'                 => array(
					'type'        => 'String',
					'description' => 'The preferred title for the page.',
				),
				'metaDesc'                  => array(
					'type'        => 'String',
					'description' => 'The preferred description for the page.',
				),
				'metaRobotsNoindex'         => array(
					'type'        => 'Boolean',
					'description' => 'Should robots skip indexing this page.',
				),
				'metaRobotsNofollow'        => array(
					'type'        => 'Boolean',
					'description' => 'Should robots skip following linked pages.',
				),
				'opengraphTitle'            => array(
					'type'        => 'String',
					'description' => 'The preferred OpenGraph title for the page.',
				),
				'opengraphDescription'      => array(
					'type'        => 'String',
					'description' => 'The preferred OpenGraph description for the page.',
				),
				'opengraphImage'            => array(
					'type'        => 'MediaItem',
					'description' => 'The preferred OpenGraph image for the page.',
				),
				'twitterTitle'              => array(
					'type'        => 'String',
					'description' => 'The preferred Twitter title for the page.',
				),
				'twitterDescription'        => array(
					'type'        => 'String',
					'description' => 'The preferred Twitter description for the page.',
				),
				'twitterImage'              => array(
					'type'        => 'MediaItem',
					'description' => 'The preferred Twitter image for the page.',
				),
				'metaRobotsOdp'             => array(
					'type'        => 'Boolean',
					'description' => 'Should meta robot tag `noodp` be included',
				),
				'metaRobotsImageIndex'      => array(
					'type'        => 'Boolean',
					'description' => 'Should meta robot tag `noimageindex` be included',
				),
				'metaRobotsArchive'         => array(
					'type'        => 'Boolean',
					'description' => 'Should meta robot tag `noarchive` be included',
				),
				'metaRobotsSnippet'         => array(
					'type'        => 'Boolean',
					'description' => 'Should meta robot tag `nosnippet` be included',
				),
				'metaRobotsPrimaryCategory' => array(
					'type'        => 'Int',
					'description' => 'The primary category ID, returns `0` if no category.',
				),
				'metaRobotsBreadcrumbs'     => array(
					'type'        => 'String',
					'description' => 'Custom breadcrumbs.',
				),
				'redirectionsEnabled'       => array(
					'type'        => 'Boolean',
					'description' => 'Does a redirection exist for this.',
				),
				'redirectionsType'          => array(
					'type'        => 'String',
					'description' => 'The type HTTP status code of the redirection.',
				),
				'redirectionsURL'           => array(
					'type'        => 'String',
					'description' => 'The URL that redirects here.',
				),
				'metaNewsDisabled'          => array(
					'type'        => 'Boolean',
					'description' => 'Should robots skip indexing this news.',
				),
				'metaVideoDisabled'         => array(
					'type'        => 'Boolean',
					'description' => 'Should robots skip indexing this video.',
				),
				'metaVideo'                 => array(
					'type'        => 'String',
					'description' => 'Array of Videos.',
				),
				'targetKeywords'            => array(
					'type'        => 'String',
					'description' => 'Target keywords separated by commas.',
				),
				'has_pro_license'           => array(
					'type'        => 'Boolean',
					'description' => 'Whether or not the site has a pro license.',
				),
			),
		);
		if ( $has_pro_license ) {
			$seopress_object['fields']['proSchemas']       = array(
				'type'        => 'String',
				'description' => 'Array of Schemas',
			);
			$seopress_object['fields']['proSchemasManual'] = array(
				'type'        => 'String',
				'description' => 'Array of Schemas',
			);
		}

		register_graphql_object_type( 'SEOPress', $seopress_object );

		// Titles & Metas Settings
		register_graphql_object_type(
			'SEOPressSettings_TitleDescription',
			array(
				'description' => 'Title and Description Format.',
				'fields'      => array(
					'title'       => array(
						'type'        => 'String',
						'description' => 'The default title format.',
					),
					'description' => array(
						'type'        => 'String',
						'description' => 'The default title format.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TitleDescriptionNoindex',
			array(
				'description' => 'Title and Description Format.',
				'fields'      => array(
					'title'       => array(
						'type'        => 'String',
						'description' => 'The default title format.',
					),
					'description' => array(
						'type'        => 'String',
						'description' => 'The default title format.',
					),
					'noindex'     => array(
						'type'        => 'Boolean',
						'description' => 'Noindex tag should be included.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_SingleTitles',
			array(
				'description' => 'Title and description format for posts and pages.',
				'fields'      => array(
					'post' => array(
						'type' => 'SEOPressSettings_TitleDescription',
					),
					'page' => array(
						'type' => 'SEOPressSettings_TitleDescription',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TaxTitles',
			array(
				'description' => 'Title and description format and whether should be marked as noindex for categories and taxonomies.',
				'fields'      => array(
					'category' => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindex',
					),
					'post_tag' => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindex',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TitlesMetas',
			array(
				'description' => __( 'Manage all your titles & metas for post types, taxonomies, archives...', 'wpgraphql_seopress' ),
				'fields'      => array(
					'home_site_title'         => array(
						'type'        => 'String',
						'description' => 'description 1',
					),
					'home_site_desc'          => array(
						'type'        => 'String',
						'description' => 'description 1',
					),
					'separator'               => array(
						'type'        => 'String',
						'description' => 'Use this separator with %%sep%% in your title and meta description.',
					),
					'single_titles'           => array(
						'type'        => 'SEOPressSettings_SingleTitles',
						'description' => 'Formats of titles for posts and pages.',
					),
					'archives_author_title'   => array(
						'type'        => 'String',
						'description' => 'Format of author archive title.',
					),
					'archives_author_desc'    => array(
						'type'        => 'String',
						'description' => 'Format of author archive description.',
					),
					'archives_author_noindex' => array(
						'type'        => 'Boolean',
						'description' => 'Noindex tag should be included on author archive pages.',
					),
					'archives_author_disable' => array(
						'type'        => 'Boolean',
						'description' => 'Author archive pages should be disabled.',
					),
					'archives_date_title'     => array(
						'type'        => 'String',
						'description' => 'Format of archive date page titles.',
					),
					'archives_date_desc'      => array(
						'type'        => 'String',
						'description' => 'Format of archive date page descriptions.',
					),
					'archives_date_noindex'   => array(
						'type'        => 'Boolean',
						'description' => 'Noindex tag should be included on archive date pages.',
					),
					'archives_date_disable'   => array(
						'type'        => 'Boolean',
						'description' => 'Archive date pages should be disabled.',
					),
					'archives_search_title'   => array(
						'type'        => 'String',
						'description' => 'Format of search page titles.',
					),
					'archives_search_desc'    => array(
						'type'        => 'String',
						'description' => 'Format of search page descriptions.',
					),
					'archives_404_title'      => array(
						'type'        => 'String',
						'description' => 'Format of 404 page titles.',
					),
					'archives_404_desc'       => array(
						'type'        => 'String',
						'description' => 'Format of 404 page descriptions.',
					),
					'tax_titles'              => array(
						'type'        => 'SEOPressSettings_TaxTitles',
						'description' => 'Taxonomy page settings.',
					),
					'paged_rel'               => array(
						'type'        => 'Boolean',
						'description' => 'Indicate paginated content to Google?',
					),
				),
			)
		);

		 // XML - HTML Sitemap Settings
		register_graphql_object_type(
			'SEOPressSettings_ShouldBeIncluded',
			array(
				'description' => 'Title and Description Format.',
				'fields'      => array(
					'include' => array(
						'type'        => 'Boolean',
						'description' => 'Should this be included in the sitemap?',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_PostTypesList',
			array(
				'description' => 'Title and Description Format.',
				'fields'      => array(
					'post'       => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
					'page'       => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
					'attachment' => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TaxonomiesList',
			array(
				'description' => 'Title and Description Format.',
				'fields'      => array(
					'category' => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
					'postTag'  => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_XmlHtmlSitemap',
			array(
				'description' => 'All SEOPress admin settings pages.',
				'fields'      => array(
					'xmlSitemapGeneralEnabled' => array(
						'type'        => 'boolean',
						'description' => 'Is the main XML sitemap enabled?',
					),
					'xmlSitemapImageEnabled'   => array(
						'type'        => 'boolean',
						'description' => 'Is the XML sitemap for images enabled?',
					),
					'xmlSitemapVideoEnabled'   => array(
						'type'        => 'boolean',
						'description' => 'Is the XML sitemap for images enabled?',
					),
					'xmlSitemapAuthorEnabled'  => array(
						'type'        => 'boolean',
						'description' => 'Is the XML sitemap for authors enabled?',
					),
					'xmlSitemapHTMLEnabled'    => array(
						'type'        => 'boolean',
						'description' => 'Is the HTML sitemap version enabled?',
					),
					'sitemapPostTypes'         => array(
						'type'        => 'SEOPressSettings_PostTypesList',
						'description' => 'Which types of posts should be included in the sitemaps?',
					),
					'sitemapTaxonomies'        => array(
						'type'        => 'SEOPressSettings_TaxonomiesList',
						'description' => 'Which taxonomies should be included in the sitemaps?',
					),
					'htmlMapping'              => array(
						'type'        => 'String',
						'description' => 'Which post, page, or custom post type ID should be used to display the sitemap?',
					),
					'htmlExclude'              => array(
						'type'        => 'String',
						'description' => 'Which posts, pages, custom post types, or terms should be excluded from sitemap?',
					),
					'htmlOrder'                => array(
						'type'        => 'String',
						'description' => 'In which order should posts be sorted in HTML sitemap?',
					),
					'htmlOrderby'              => array(
						'type'        => 'String',
						'description' => 'What should posts be sorted by in HTML sitemap?',
					),
					'htmlDate'                 => array(
						'type'        => 'Boolean',
						'description' => 'Disable the display of the publication date in HTML sitemaps.',
					),
					'htmlArchiveLinks'         => array(
						'type'        => 'Boolean',
						'description' => 'Remove links from archive pages in HTML sitemaps.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings',
			array(
				'description' => 'All SEOPress admin settings pages.',
				'fields'      => array(
					'hasProLicense'  => array(
						'type'        => 'boolean',
						'description' => 'Does the site have an SEOPress Pro license',
					),
					'titlesMetas'    => array(
						'type' => 'SEOPressSettings_TitlesMetas',
					),
					'xmlHtmlSitemap' => array(
						'type' => 'SEOPressSettings_XmlHtmlSitemap',
					),
				),
			)
		);

		register_graphql_field(
			'RootQuery',
			'seoPressSettings',
			array(
				'type'    => 'SEOPressSettings',
				'resolve' => function () {
					$seopress_titles_options = get_option( 'seopress_titles_option_name' );
					$seopress_titles_settings = array(
						'home_site_title'         => $seopress_titles_options['seopress_titles_home_site_title'],
						'home_site_desc'          => $seopress_titles_options['seopress_titles_home_site_desc'],
						'separator'               => $seopress_titles_options['seopress_titles_sep'],
						'single_titles'           => array(
							'post' => array(
								'title'       => $seopress_titles_options['seopress_titles_single_titles']['post']['title'],
								'description' => $seopress_titles_options['seopress_titles_single_titles']['post']['description'],
							),
							'page' => array(
								'title'       => $seopress_titles_options['seopress_titles_single_titles']['page']['title'],
								'description' => $seopress_titles_options['seopress_titles_single_titles']['page']['description'],
							),
						),
						'archives_author_title'   => $seopress_titles_options['seopress_titles_archives_author_title'],
						'archives_author_desc'    => $seopress_titles_options['seopress_titles_archives_author_desc'],
						'archives_author_noindex' => $seopress_titles_options['seopress_titles_archives_author_noindex'] ? true : false,
						'archives_author_disable' => $seopress_titles_options['seopress_titles_archives_author_disable'] ? true : false,
						'archives_date_title'     => $seopress_titles_options['seopress_titles_archives_date_title'],
						'archives_date_desc'      => $seopress_titles_options['seopress_titles_archives_date_desc'],
						'archives_date_noindex'   => $seopress_titles_options['seopress_titles_archives_date_noindex'] ? true : false,
						'archives_date_disable'   => $seopress_titles_options['seopress_titles_archives_date_disable'] ? true : false,
						'archives_search_title'   => $seopress_titles_options['seopress_titles_archives_search_title'],
						'archives_search_desc'    => $seopress_titles_options['seopress_titles_archives_search_desc'],
						'archives_404_title'      => $seopress_titles_options['seopress_titles_archives_404_title'],
						'archives_404_desc'       => $seopress_titles_options['seopress_titles_archives_404_desc'],
						'tax_titles'              => array(
							'category' => array(
								'title'       => $seopress_titles_options['seopress_titles_tax_titles']['category']['title'],
								'description' => $seopress_titles_options['seopress_titles_tax_titles']['category']['description'],
								'noindex'     => $seopress_titles_options['seopress_titles_tax_titles']['category']['noindex'] ? true : false,
							),
							'post_tag' => array(
								'title'       => $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['title'],
								'description' => $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['description'],
								'noindex'     => $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['noindex'] ? true : false,
							),
						),
						'paged_rel'               => $seopress_titles_options['seopress_titles_paged_rel'] ? true : false,
					);
					$seopress_xml_html_sitemap_options = get_option( 'seopress_xml_sitemap_option_name' );
					$seopress_xml_html_sitemap_settings = array(
						'xmlSitemapGeneralEnabled' => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_general_enable'] ? true : false,
						'xmlSitemapImageEnabled'   => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_img_enable'] ? true : false,
						'xmlSitemapVideoEnabled'   => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_video_enable'] ? true : false,
						'xmlSitemapAuthorEnabled'  => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_author_enable'] ? true : false,
						'xmlSitemapHTMLEnabled'    => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_enable'] ? true : false,
						'sitemapPostTypes'         => array(
							'post'       => array(
								'include' => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_post_types_list']['post']['include'] ? true : false,
							),
							'page'       => array(
								'include' => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_post_types_list']['page']['include'] ? true : false,
							),
							'attachment' => array(
								'include' => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_post_types_list']['attachment']['include'] ? true : false,
							),
						),
						'sitemapTaxonomies'        => array(
							'category' => array(
								'include' => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_taxonomies_list']['category']['include'] ? true : false,
							),
							'post_tag' => array(
								'include' => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_taxonomies_list']['post_tag']['include'] ? true : false,
							),
						),
						'htmlMapping'              => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_mapping'],
						'htmlExclude'              => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_exclude'],
						'htmlOrder'                => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_order'],
						'htmlOrderby'              => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_orderby'],
						'htmlDate'                 => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_date'] ? true : false,
						'htmlArchiveLinks'         => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_archive_links'] ? true : false,
					);
					return array(
						'hasProLicense'  => get_option( 'seopress_pro_license_status' ) === 'valid',
						'titlesMetas'    => $seopress_titles_settings,
						'xmlHtmlSitemap' => $seopress_xml_html_sitemap_settings,
					);
				},
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
									'metaRobotsNoindex'    => trim( get_post_meta( $post->ID, '_seopress_robots_index', true ) ) === 'yes',
									'metaRobotsNofollow'   => trim( get_post_meta( $post->ID, '_seopress_robots_follow', true ) ) === 'yes',
									'opengraphTitle'       => trim( get_post_meta( $post->ID, '_seopress_social_fb_title', true ) ),
									'opengraphDescription' => trim( get_post_meta( $post->ID, '_seopress_social_fb_desc', true ) ),
									'opengraphImage'       => $context->get_loader( 'post' )->load_deferred( get_post_meta( $post->ID, '_seopress_social_fb_img', true ) ),
									'twitterTitle'         => trim( get_post_meta( $post->ID, '_seopress_social_twitter_title', true ) ),
									'twitterDescription'   => trim( get_post_meta( $post->ID, '_seopress_social_twitter_desc', true ) ),
									'twitterImage'         => $context->get_loader( 'post' )->load_deferred( get_post_meta( $post->ID, '_seopress_social_twitter_img', true ) ),
									'metaRobotsOdp'        => trim( get_post_meta( $post->ID, '_seopress_robots_odp', true ) ) === 'yes',
									'metaRobotsImageIndex' => trim( get_post_meta( $post->ID, '_seopress_robots_imageindex', true ) ) === 'yes',
									'metaRobotsArchive'    => trim( get_post_meta( $post->ID, '_seopress_robots_archive', true ) ) === 'yes',
									'metaRobotsSnippet'    => trim( get_post_meta( $post->ID, '_seopress_robots_snippet', true ) ) === 'yes',
									'metaRobotsPrimaryCategory' => ! filter_var( get_post_meta( $post->ID, '_seopress_robots_primary_cat', true ), FILTER_VALIDATE_INT ) ? 0 : get_post_meta( $post->ID, '_seopress_robots_primary_cat', true ),
									'metaRobotsBreadcrumbs' => get_post_meta( $post->ID, '_seopress_robots_breadcrumbs', true ),
									'redirectionsEnabled'  => get_post_meta( $post->ID, '_seopress_redirections_enabled', true ) === 'yes',
									'redirectionsType'     => get_post_meta( $post->ID, '_seopress_redirections_type', true ),
									'redirectionsURL'      => get_post_meta( $post->ID, '_seopress_redirections_value', true ),
									'metaNewsDisabled'     => get_post_meta( $post->ID, '_seopress_news_disabled', true ) === 'yes',
									'metaVideoDisabled'    => get_post_meta( $post->ID, '_seopress_video_disabled', true ) === 'yes',
									'metaVideo'            => wp_json_encode( get_post_meta( $post->ID, '_seopress_video', true ) ),
									'targetKeywords'       => get_post_meta( $post->ID, '_seopress_analysis_target_kw', true ),
								);
								if ( get_option( 'seopress_pro_license_status' ) === 'valid' ) {
									$seo['proSchemas'] = wp_json_encode( get_post_meta( $post->ID, '_seopress_pro_schemas', true ) );
									$seo['proSchemasManual'] = wp_json_encode( get_post_meta( $post->ID, '_seopress_pro_schemas_manual', true ) );
									$seo['has_pro_license'] = true;
								} else {
									$seo['has_pro_license'] = false;
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
									'metaTitle'            => trim( get_term_meta( $term->term_id, '_seopress_titles_title', true ) ),
									'canonicalUrl'         => trim( get_term_meta( $term->term_id, '_seopress_robots_canonical', true ) ),
									'metaDesc'             => trim( get_term_meta( $term->term_id, '_seopress_titles_desc', true ) ),
									'metaRobotsNoindex'    => trim( get_term_meta( $term->term_id, '_seopress_robots_index', true ) ) === 'yes',
									'metaRobotsNofollow'   => trim( get_term_meta( $term->term_id, '_seopress_robots_follow', true ) ) === 'yes',
									'opengraphTitle'       => trim( get_term_meta( $term->term_id, '_seopress_social_fb_title', true ) ),
									'opengraphDescription' => trim( get_term_meta( $term->term_id, '_seopress_social_fb_desc', true ) ),
									'opengraphImage'       => $context->get_loader( 'post' )->load_deferred( get_term_meta( $term->term_id, '_seopress_social_fb_img', true ), $context ),
									'twitterTitle'         => trim( get_term_meta( $term->term_id, '_seopress_social_twitter_title', true ) ),
									'twitterDescription'   => trim( get_term_meta( $term->term_id, '_seopress_social_twitter_desc', true ) ),
									'twitterImage'         => $context->get_loader( 'post' )->load_deferred( get_term_meta( $term->term_id, '_seopress_social_twitter_img', true ), $context ),
									'metaRobotsOdp'        => trim( get_post_meta( $term->ID, '_seopress_robots_odp', true ) ) === 'yes',
									'metaRobotsImageIndex' => trim( get_post_meta( $term->ID, '_seopress_robots_imageindex', true ) ) === 'yes',
									'metaRobotsArchive'    => trim( get_post_meta( $term->ID, '_seopress_robots_archive', true ) ) === 'yes',
									'metaRobotsSnippet'    => trim( get_post_meta( $term->ID, '_seopress_robots_snippet', true ) ) === 'yes',
									'metaRobotsPrimaryCategory' => ! filter_var( get_post_meta( $term->ID, '_seopress_robots_primary_cat', true ), FILTER_VALIDATE_INT ) ? 0 : get_post_meta( $term->ID, '_seopress_robots_primary_cat', true ),
									'metaRobotsBreadcrumbs' => get_post_meta( $term->ID, '_seopress_robots_breadcrumbs', true ),
									'redirectionsEnabled'  => get_post_meta( $term->ID, '_seopress_redirections_enabled', true ) === 'yes',
									'redirectionsType'     => get_post_meta( $term->ID, '_seopress_redirections_type', true ),
									'redirectionsURL'      => get_post_meta( $term->ID, '_seopress_redirections_value', true ),
									'metaNewsDisabled'     => get_post_meta( $term->ID, '_seopress_news_disabled', true ) === 'yes',
									'metaVideoDisabled'    => get_post_meta( $term->ID, '_seopress_video_disabled', true ) === 'yes',
									'metaVideo'            => wp_json_encode( get_post_meta( $term->ID, '_seopress_video', true ) ),
									'targetKeywords'       => get_post_meta( $term->ID, '_seopress_analysis_target_kw', true ),
								);
								if ( get_option( 'seopress_pro_license_status' ) === 'valid' ) {
									$seo['proSchemas'] = wp_json_encode( get_post_meta( $term->ID, '_seopress_pro_schemas', true ) );
									$seo['proSchemasManual'] = wp_json_encode( get_post_meta( $term->ID, '_seopress_pro_schemas_manual', true ) );
									$seo['has_pro_license'] = true;
								} else {
									$seo['has_pro_license'] = false;
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
