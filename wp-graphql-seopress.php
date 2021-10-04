<?php // phpcs:ignore

/**
 * Plugin Name:     WPGraphQL for SEOPress
 * Plugin URI:      https://github.com/moonmeister/wp-graphql-seopress
 * Description:     A WPGraphQL Extension that adds support for SEOPress
 * Author:          Alex Moon
 * Author URI:      https://moonmeister.net
 * Text Domain:     wp-graphql-seopress
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

		// Titles & Metas Settings.
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
					'nofollow'    => array(
						'type'        => 'Boolean',
						'description' => 'Nofollow tag should be included.',
					),
					'date'        => array(
						'type'        => 'Boolean',
						'description' => '', // TODO: Find out what this is.
					),
					'thumb_gcs'   => array(
						'type'        => 'Boolean',
						'description' => '', // TODO: Find out what this is.
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TitleDescriptionNoindexNofollow',
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
					'nofollow'    => array(
						'type'        => 'Boolean',
						'description' => 'Nofollow tag should be included.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TitleDescriptionNoindexNofollowDisable',
			array(
				'description' => 'Title and Description Format.',
				'fields'      => array(
					'disable'     => array(
						'type'        => 'Boolean',
						'description' => 'Should SEO metaboxes be disabled for this Taxonomy.',
					),
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
					'nofollow'    => array(
						'type'        => 'Boolean',
						'description' => 'Nofollow tag should be included.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_SingleTitles',
			array(
				'description' => 'Title and description format for posts and pages.',
				'fields'      => array(
					'post'    => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindex',
					),
					'page'    => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindex',
					),
					'product' => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindex',
					),
					// TODO: Dynamically generate this list based on all custom post types registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TaxTitles',
			array(
				'description' => 'Title and description format and whether should be marked as noindex for categories and taxonomies.',
				'fields'      => array(
					'category'        => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindexNofollowDisable',
					),
					'post_tag'        => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindexNofollowDisable',
					),
					'productCategory' => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindexNofollowDisable',
					),
					'productTag'      => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindexNofollowDisable',
					),
					// TODO: Dynamically generate this list based on all custom taxonomies registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_ArchiveTitles',
			array(
				'description' => 'Title and description format and whether should be marked as noindex for categories and taxonomies.',
				'fields'      => array(
					'product' => array(
						'type' => 'SEOPressSettings_TitleDescriptionNoindexNofollow',
					),
					// TODO: Dynamically generate this list based on all custom post types registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TitlesMetas',
			array(
				'description' => __( 'SEOPress Titles and Metas settings pages.' ),
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
					'archive_titles'          => array(
						'type'        => 'SEOPressSettings_ArchiveTitles',
						'description' => 'Customized metas for all archives.',
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
					'archives_search_noindex' => array(
						'type'        => 'Boolean',
						'description' => 'Noindex tag should be included on archive date pages.',
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
					'noindex'                 => array(
						'type'        => 'Boolean',
						'description' => 'Noindex tag should be included on entire site.',
					),
					'nofollow'                => array(
						'type'        => 'Boolean',
						'description' => 'Nofollow tag should be included on entire site.',
					),
					'noodp'                   => array(
						'type'        => 'Boolean',
						'description' => 'Do not use Open Directory Project metadata on any pages.',
					),
					'noimageindex'            => array(
						'type'        => 'Boolean',
						'description' => 'Noimageindex tag should be included on entire site.',
					),
					'noarchive'               => array(
						'type'        => 'Boolean',
						'description' => 'Noarchive tag should be included on entire site.',
					),
					'nosnippet'               => array(
						'type'        => 'Boolean',
						'description' => 'Nosnippet tag should be included on entire site.',
					),
					'nositelinkssearchbox'    => array(
						'type'        => 'Boolean',
						'description' => 'Nositelinkssearchbox tag should be included on entire site.',
					),
					'paged_rel'               => array(
						'type'        => 'Boolean',
						'description' => 'Indicate paginated content to Google?',
					),
					'paged_noindex'           => array(
						'type'        => 'Boolean',
						'description' => 'Noindex tag should be included on paginated pages.',
					),
					'attachments_noindex'     => array(
						'type'        => 'Boolean',
						'description' => 'Noindex tag should be included on attachment pages.',
					),
				),
			)
		);

		// XML - HTML Sitemap Settings.
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
					'product'    => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
					// TODO: Dynamically generate this list based on all custom post types registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_TaxonomiesList',
			array(
				'description' => 'Title and Description Format.',
				'fields'      => array(
					'category'        => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
					'postTag'         => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
					'productCategory' => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
					'productTag'      => array(
						'type' => 'SEOPressSettings_ShouldBeIncluded',
					),
					// TODO: Dynamically generate this list based on all custom taxonomies registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_XmlHtmlSitemap',
			array(
				'description' => 'SEOPress XML - HTML Sitemap settings pages.',
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

		// Social Networks Settings.
		register_graphql_object_type(
			'SEOPressSettings_FacebookImgDefault',
			array(
				'description' => 'SEOPress Custom Post Type Default Facebook Images.',
				'fields'      => array(
					'url' => array(
						'type'        => 'String',
						'description' => 'Default image url',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_FacebookImgCustomPostTypes',
			array(
				'description' => 'SEOPress Custom Post Type Default Facebook Images.',
				'fields'      => array(
					'product' => array(
						'type'        => 'SEOPressSettings_FacebookImgDefault',
						'description' => 'Default image for the custom post type: `product`.',
					),
					// TODO: Dynamically generate this list based on all custom post types registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_SocialNetworks',
			array(
				'description' => 'SEOPress Social Networks settings pages.',
				'fields'      => array(
					'knowledgeType'              => array(
						'type'        => 'String',
						'description' => 'The type of object for knowledge graph. (Ref: https://schema.org/Thing#subtypes)',
					),
					'knowledgeName'              => array(
						'type'        => 'String',
						'description' => 'Name of knowledge graph object.',
					),
					'knowledgeImg'               => array(
						'type'        => 'String',
						'description' => 'Photo or logo for knowledge graph object.',
					),
					'knowledgePhone'             => array(
						'type'        => 'String',
						'description' => 'Organization phone number for knowledge graph (only for organizations).',
					),
					'knowledgeContactType'       => array(
						'type'        => 'String',
						'description' => 'Contact type for knowledge graph (only for organizations). (Ref: https://schema.org/contactType)',
					),
					'knowledgeContactOption'     => array(
						'type'        => 'String',
						'description' => 'Contact option for knowledge graph (only for organizations). (Ref: https://schema.org/ContactPointOption)',
					),
					'accountFacebook'            => array(
						'type'        => 'String',
						'description' => 'Facebook Page URL.',
					),
					'accountTwitter'             => array(
						'type'        => 'String',
						'description' => 'Twitter Page URL.',
					),
					'accountPinterest'           => array(
						'type'        => 'String',
						'description' => 'Pinterest Page URL.',
					),
					'accountInstagram'           => array(
						'type'        => 'String',
						'description' => 'Instagram Page URL.',
					),
					'accountYoutube'             => array(
						'type'        => 'String',
						'description' => 'YouTube Page URL.',
					),
					'accountLinkedIn'            => array(
						'type'        => 'String',
						'description' => 'LinkedIn Page URL.',
					),
					'facebookOg'                 => array(
						'type'        => 'Boolean',
						'description' => 'Enable Open Graph Data.',
					),
					'facebookImg'                => array(
						'type'        => 'String',
						'description' => 'Default Image for Open Graph.',
					),
					'facebookImgDefault'         => array(
						'type'        => 'Boolean',
						'description' => 'Use same image for all Open Graph tags.',
					),
					'facebookImgCustomPostTypes' => array(
						'type'        => 'SEOPressSettings_FacebookImgCustomPostTypes',
						'description' => 'Array of default facebook images all custom post types.',
					),
					'facebookLinkOwnershipId'    => array(
						'type'        => 'String',
						'description' => 'One or more Facebook Page IDs that are associated with a URL in order to enable link editing and instant article publishing. (Ref: https://www.facebook.com/help/1503421039731588)',
					),
					'facebookAdminId'            => array(
						'type'        => 'String',
						'description' => 'The ID (or comma-separated list for properties that can accept multiple IDs) of an app, person using the app, or Page Graph API object.',
					),
					'facebookAppId'              => array(
						'type'        => 'String',
						'description' => 'The Facebook app ID of the site\'s app. In order to use Facebook Insights you must add the app ID to your page.',
					),
					'twitterCard'                => array(
						'type'        => 'Boolean',
						'description' => 'Enable Twitter card',
					),
					'twitterCardOg'              => array(
						'type'        => 'Boolean',
						'description' => 'Use Open Graph if no Twitter cards.',
					),
					'twitterCardImg'             => array(
						'type'        => 'String',
						'description' => 'Default Twitter card image,',
					),
					'twitterCardImgSize'         => array(
						'type'        => 'String',
						'description' => 'Image size for twitter cards.',
					),
				),
			)
		);

		// Analytics Settings.
		register_graphql_object_type(
			'SEOPressSettings_WhoToTrack',
			array(
				'description' => 'Which types of users should be excluded from tracking.',
				'fields'      => array(
					'administrator' => array(
						'type'        => 'Boolean',
						'description' => 'Administrator should not be tracked.',
					),
					'editor'        => array(
						'type'        => 'Boolean',
						'description' => 'editor should not be tracked.',
					),
					'author'        => array(
						'type'        => 'Boolean',
						'description' => 'author should not be tracked.',
					),
					'contributor'   => array(
						'type'        => 'Boolean',
						'description' => 'contributor should not be tracked.',
					),
					'subscriber'    => array(
						'type'        => 'Boolean',
						'description' => 'subscriber should not be tracked.',
					),
					'customer'      => array(
						'type'        => 'Boolean',
						'description' => 'subscriber should not be tracked.',
					),
					'shop_manager'  => array(
						'type'        => 'Boolean',
						'description' => 'subscriber should not be tracked.',
					),
					// TODO: Dynamically generate this list based on all custom user roles registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_Analytics',
			array(
				'description' => 'SEOPress Analytics settings pages.',
				'fields'      => array(
					'googleAnalyticsEnable'                => array(
						'type'        => 'Boolean',
						'description' => 'Enable Google Analytics.',
					),
					'googleAnalyticsUACode'                => array(
						'type'        => 'String',
						'description' => 'Google Analytics Universal Analytics tracking ID.',
					),
					'googleAnalyticsGA4Code'               => array(
						'type'        => 'String',
						'description' => 'Google Analytics 4 measurement ID.',
					),
					'googleAnalyticsNoTrackRoles'          => array(
						'type' => 'SEOPressSettings_WhoToTrack',
					),
					'googleAnalyticsOptimize'              => array(
						'type'        => 'String',
						'description' => 'Google Analytics Optimize ID.',
					),
					'googleAnalyticsAdWords'               => array(
						'type'        => 'String',
						'description' => 'Google AdWords ID.',
					),
					'additionalTrackingHead'               => array(
						'type'        => 'String',
						'description' => 'Additional tracking code to add to head.',
					),
					'additionalTrackingBody'               => array(
						'type'        => 'String',
						'description' => 'Additional tracking code to add to top of body.',
					),
					'additionalTrackingFooter'             => array(
						'type'        => 'String',
						'description' => 'Additional tracking code to add to bottom of body.',
					),
					'googleAnalyticsRemarketing'           => array(
						'type'        => 'Boolean',
						'description' => 'Enable remarketing, demographics, and interests reporting in Google Analytics.',
					),
					'googleAnalyticsIpAnonymization'       => array(
						'type'        => 'Boolean',
						'description' => 'Enable IP Anonymization in Google Analytics.',
					),
					'googleAnalyticsLinkAttribution'       => array(
						'type'        => 'Boolean',
						'description' => 'Enhanced Link Attribution in Google Analytics',
					),
					'googleAnalyticsCrossEnable'           => array(
						'type'        => 'Boolean',
						'description' => 'Enable cross-domain tracking in Google Analytics.',
					),
					'googleAnalyticsCrossDomainList'       => array(
						'type'        => 'String',
						'description' => 'Other domains to register for cross-domain tracking in Google Analytics.',
					),
					'googleAnalyticsLinkTrackingEnable'    => array(
						'type'        => 'Boolean',
						'description' => 'Should external link tracking be sent to Google Analytics.',
					),
					'googleAnalyticsDownloadTrackingEnable' => array(
						'type'        => 'Boolean',
						'description' => 'Should download tracking be sent to Google Analytics.',
					),
					'googleAnalyticsDownloadTracking'      => array(
						'type'        => 'String',
						'description' => 'File types to track downloads of in Google Analytics.',
					),
					'googleAnalyticsAffiliateTrackingEnable' => array(
						'type'        => 'Boolean',
						'description' => 'Should affiliate link tracking be sent to Google Analytics.',
					),
					'googleAnalyticsAffiliateTracking'     => array(
						'type'        => 'String',
						'description' => 'Keywords for affiliate link tracking to send to Google Analytics.',
					),
					'googleAnalyticsCustomDimensionAuthor' => array(
						'type'        => 'String',
						'description' => 'Custom dimension for author pages.',
					),
					'googleAnalyticsCustomDimensionCategory' => array(
						'type'        => 'String',
						'description' => 'Custom dimension for category pages.',
					),
					'googleAnalyticsCustomDimensionTag'    => array(
						'type'        => 'String',
						'description' => 'Custom dimension for tag pages.',
					),
					'googleAnalyticsCustomDimensionPostType' => array(
						'type'        => 'String',
						'description' => 'Custom dimension for post type pages.',
					),
					'googleAnalyticsCustomDimensionLoggedIn' => array(
						'type'        => 'String',
						'description' => 'Custom dimension for logged in users.',
					),
					'googleAnalyticsApiClientId'           => array(
						'type'        => 'String',
						'description' => 'Google Analytics API client ID. (Ref: https://www.seopress.org/support/guides/connect-wordpress-site-google-analytics/)',
					),
					'googleAnalyticsApiSecretId'           => array(
						'type'        => 'String',
						'description' => 'Google Analytics API secret ID. (Ref: https://www.seopress.org/support/guides/connect-wordpress-site-google-analytics/)',
					),
					'googleAnalyticsDashboardWidget'       => array(
						'type'        => 'Boolean',
						'description' => 'Should Google Analytics dashboard widget be removed.',
					),
					'googleAnalyticsEventPurchases'        => array(
						'type'        => 'Boolean',
						'description' => 'Should purchases be tracked in Google Analytics.',
					),
					'googleAnalyticsEventAddToCart'        => array(
						'type'        => 'Boolean',
						'description' => 'Should "Add to cart events" be tracked in Google Analytics.',
					),
					'googleAnalyticsEventRemoveFromCart'   => array(
						'type'        => 'Boolean',
						'description' => 'Should "Remove from cart events" be tracked in Google Analytics.',
					),
					'googleAnalyticsHtmlLocation'          => array(
						'type'        => 'String',
						'description' => 'Where in the page to put the Google Analytics tracking code.',
					),
					'cookieConsentRequired'                => array(
						'type'        => 'Boolean',
						'description' => 'Should site request user\'s consent for analytics tracking.',
					),
					'cookieConsentAutomatic'               => array(
						'type'        => 'Boolean',
						'description' => 'Should site automatically accept user\'s consent for analytics tracking.',
					),
					'cookieConsentChangeChoice'            => array(
						'type'        => 'Boolean',
						'description' => 'Allow user to change its choice about cookies',
					),
					'cookieConsentOptOutMessage'           => array(
						'type'        => 'String',
						'description' => 'Consent message for user tracking.',
					),
					'cookieConsentOptOutMessageOk'         => array(
						'type'        => 'String',
						'description' => 'Button text to accept tracking.',
					),
					'cookieConsentOptOutClose'             => array(
						'type'        => 'String',
						'description' => 'Button text to close banner.',
					),
					'cookieConsentOptOutEdit'              => array(
						'type'        => 'String',
						'description' => 'Button text to edit tracking preferences.',
					),
					'cookieConsentCookieValidity'          => array(
						'type'        => 'Integer',
						'description' => 'User consent cookie expiration date.',
					),
					'cookieConsentPosition'                => array(
						'type'        => 'String',
						'description' => 'Where on the page to place the cookie banner.',
					),
					'cookieConsentTextAlign'               => array(
						'type'        => 'String',
						'description' => 'How to align text in the cookie banner.',
					),
					'cookieConsentBarWidth'                => array(
						'type'        => 'String',
						'description' => 'How wide the cookie bar should be. (Can return any CSS measurement)',
					),
					'cookieConsentBackdropCustomized'      => array(
						'type'        => 'Boolean',
						'description' => 'Customized cookie consent bar backdrop.',
					),
					'cookieConsentBackdropColor'           => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar backdrop color.',
					),
					'cookieConsentBackgroundColor'         => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar background color.',
					),
					'cookieConsentTextColor'               => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar text color.',
					),
					'cookieConsentLinkColor'               => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar link color.',
					),
					'cookieConsentAcceptButtonBackgroundColor' => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar accept button background color.',
					),
					'cookieConsentAcceptButtonBackgroundColorHover' => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar accept button background (:hover) color.',
					),
					'cookieConsentAcceptButtonColor'       => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar accept button text color.',
					),
					'cookieConsentAcceptButtonColorHover'  => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar accept button text (:hover) color.',
					),
					'cookieConsentCloseBackgroundColor'    => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar close button background color.',
					),
					'cookieConsentCloseColor'              => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar close button text color.',
					),
					'cookieConsentCloseBackgroundColorHover' => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar close button background (:hover) color.',
					),
					'cookieConsentCloseColorHover'         => array(
						'type'        => 'String',
						'description' => 'Cookie consent bar close button text (:hover) color.',
					),
					'matomoEnable'                         => array(
						'type'        => 'Boolean',
						'description' => 'Enable Matomo',
					),
					'matomoHost'                           => array(
						'type'        => 'String',
						'description' => 'Matomo host.',
					),
					'matomoSiteId'                         => array(
						'type'        => 'String',
						'description' => 'Matomo site ID.',
					),
					'matomoSubdomainTracking'              => array(
						'type'        => 'Boolean',
						'description' => 'Track visitors across all subdomains in Matomo.',
					),
					'matomoPrependDomain'                  => array(
						'type'        => 'Boolean',
						'description' => 'Prepend the site domain to the page title when tracking in Matomo.',
					),
					'matomoTrackWithoutJavascript'         => array(
						'type'        => 'Boolean',
						'description' => 'Track users with JavaScript disabled in Matomo.',
					),
					'matomoCrossDomainTracking'            => array(
						'type'        => 'Boolean',
						'description' => 'Enable cross domain tracking in Matomo.',
					),
					'matomoCrossDomainTrackingList'        => array(
						'type'        => 'String',
						'description' => 'Cross domain tracking site list for Matomo.',
					),
					'matomoHonorDoNotTrack'                => array(
						'type'        => 'Boolean',
						'description' => 'Enable DoNotTrack detection in Matomo.',
					),
					'matomoNoCookies'                      => array(
						'type'        => 'Boolean',
						'description' => 'Disable all tracking cookies in Matomo',
					),
					'matomoLinkDownloadTracking'           => array(
						'type'        => 'Boolean',
						'description' => 'Download & Outlink tracking in Matomo.',
					),
					'matomoNoHeatmaps'                     => array(
						'type'        => 'Boolean',
						'description' => 'Disable all heatmaps and session recordings in Matomo.',
					),
				),
			)
		);

		// Advanced Settings.
		register_graphql_object_type(
			'SEOPressSettings_AdvancedUserEnable',
			array(
				'description' => 'Set Permissions Options for Users',
				'fields'      => array(
					'administrator' => array(
						'type'        => 'Boolean',
						'description' => 'True if enabled.',
					),
					'editor'        => array(
						'type'        => 'Boolean',
						'description' => 'True if enabled.',
					),
					'author'        => array(
						'type'        => 'Boolean',
						'description' => 'True if enabled.',
					),
					'contributor'   => array(
						'type'        => 'Boolean',
						'description' => 'True if enabled.',
					),
					'subscriber'    => array(
						'type'        => 'Boolean',
						'description' => 'True if enabled.',
					),
					'customer'      => array(
						'type'        => 'Boolean',
						'description' => 'True if enabled.',
					),
					'shop_manager'  => array(
						'type'        => 'Boolean',
						'description' => 'True if enabled.',
					),
					// TODO: Dynamically generate this list based on all custom user roles registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_Advanced',
			array(
				'description' => 'SEOPress Advanced settings pages.',
				'fields'      => array(
					'redirectAttachmentPagesToParent'      => array(
						'type'        => 'Boolean',
						'description' => 'Redirect attachment pages to post parent',
					),
					'redirectAttachmentPagesToFile'        => array(
						'type'        => 'Boolean',
						'description' => 'Redirect attachment pages to their file URL',
					),
					'removeReplytocom'                     => array(
						'type'        => 'Boolean',
						'description' => 'Remove ?replytocom link to avoid duplicate content',
					),
					'automaticImageTitle'                  => array(
						'type'        => 'Boolean',
						'description' => 'Automatically set the image Title',
					),
					'automaticImageAltText'                => array(
						'type'        => 'Boolean',
						'description' => 'Automatically set the image Alt text',
					),
					'automaticImageAltTextFromKeywords'    => array(
						'type'        => 'Boolean',
						'description' => 'Automatically set the image Alt text from target keywords',
					),
					'automaticImageCaption'                => array(
						'type'        => 'Boolean',
						'description' => 'Automatically set the image Caption',
					),
					'automaticImageDescription'            => array(
						'type'        => 'Boolean',
						'description' => 'Automatically set the image Description',
					),
					'addEditorToTaxonomy'                  => array(
						'type'        => 'Boolean',
						'description' => 'Add WP Editor to taxonomy description textarea',
					),
					'removeCategoryInUrl'                  => array(
						'type'        => 'Boolean',
						'description' => 'Remove `/category/` in URL',
					),
					'removeProductCategoryInUrl'           => array(
						'type'        => 'Boolean',
						'description' => 'Remove `/product-category/` in URL',
					),
					'removeTrailingSlashMetas'             => array(
						'type'        => 'Boolean',
						'description' => 'Disable trailing slash for metas',
					),
					'removeGeneratorMeta'                  => array(
						'type'        => 'Boolean',
						'description' => 'Remove WordPress generator meta tag',
					),
					'removeHentryPostClass'                => array(
						'type'        => 'Boolean',
						'description' => 'Remove hentry post class',
					),
					'removeCommentAuthorUrl'               => array(
						'type'        => 'Boolean',
						'description' => 'Remove author URL',
					),
					'removeCommentFormWebsiteInput'        => array(
						'type'        => 'Boolean',
						'description' => 'Remove website field in comment form',
					),
					'removeShortlinkMeta'                  => array(
						'type'        => 'Boolean',
						'description' => 'Remove WordPress shortlink meta tag',
					),
					'removeWindowsLiveWriterMeta'          => array(
						'type'        => 'Boolean',
						'description' => 'Remove Windows Live Writer meta tag',
					),
					'removeReallySimpleDiscoveryMeta'      => array(
						'type'        => 'Boolean',
						'description' => 'Remove RSD meta tag',
					),
					'addSiteVerificationGoogle'            => array(
						'type'        => 'String',
						'description' => 'Google site verification string',
					),
					'addSiteVerificationBing'              => array(
						'type'        => 'String',
						'description' => 'Bing site verification string',
					),
					'addSiteVerificationPinterest'         => array(
						'type'        => 'String',
						'description' => 'Pinterest site verification string',
					),
					'addSiteVerificationYandex'            => array(
						'type'        => 'String',
						'description' => 'Yandex site verification string',
					),
					'removeSeoAdminBar'                    => array(
						'type'        => 'Boolean',
						'description' => 'SEO in admin bar',
					),
					'enableUniversalSeoMetabox'            => array(
						'type'        => 'Boolean',
						'description' => 'Universal Metabox (Gutenberg)',
					),
					'removeUniversalSeoMetabox'            => array(
						'type'        => 'Boolean',
						'description' => 'Disable Universal Metabox',
					),
					'removeNoindexFromAdminBar'            => array(
						'type'        => 'Boolean',
						'description' => 'Noindex in admin bar',
					),
					'metaboxSeoPosition'                   => array(
						'type'        => 'String',
						'description' => 'SEO metabox\'s position',
					),
					'metaboxStructuredDataDefaultTab'      => array(
						'type'        => 'String',
						'description' => 'Default tab for Structured data metabox',
					),
					'removeNotificationCenter'             => array(
						'type'        => 'Boolean',
						'description' => 'Hide Notifications Center',
					),
					'removeSeoNews'                        => array(
						'type'        => 'Boolean',
						'description' => 'Hide SEO News in SEO Dashboard Page',
					),
					'removeSeoTools'                       => array(
						'type'        => 'Boolean',
						'description' => 'Hide SEO tools in SEO Dashboard Page',
					),
					'enableTitleTagColumn'                 => array(
						'type'        => 'Boolean',
						'description' => 'Show Title tag column in post types',
					),
					'enableMetaDescriptionColumn'          => array(
						'type'        => 'Boolean',
						'description' => 'Show Meta description column in post types',
					),
					'enableRedirectionEnableColumn'        => array(
						'type'        => 'Boolean',
						'description' => 'Show Redirection Enable column in post types',
					),
					'enableRedirectUrlColumn'              => array(
						'type'        => 'Boolean',
						'description' => 'Show Redirect URL column in post types',
					),
					'enableCanonicalUrlColumn'             => array(
						'type'        => 'Boolean',
						'description' => 'Show canonical URL column in post types',
					),
					'enableTargetKeywordColumn'            => array(
						'type'        => 'Boolean',
						'description' => 'Show Target Keyword column in post types',
					),
					'enableNoindexColumn'                  => array(
						'type'        => 'Boolean',
						'description' => 'Show noindex column in post types',
					),
					'enableNofollowColumn'                 => array(
						'type'        => 'Boolean',
						'description' => 'Show nofollow column in post types',
					),
					'enableTotalWordsColumn'               => array(
						'type'        => 'Boolean',
						'description' => 'Show total number of words column in post types',
					),
					'enablePageSpeedColumn'                => array(
						'type'        => 'Boolean',
						'description' => 'Show Google Page Speed column in post types',
					),
					'enableContentAnalysisScoreColumn'     => array(
						'type'        => 'Boolean',
						'description' => 'Show content analysis score column in post types',
					),
					'removeContentAnalysisMetabox'         => array(
						'type'        => 'Boolean',
						'description' => 'Remove Content Analysis Metabox',
					),
					'removeGenesisSeoMetabox'              => array(
						'type'        => 'Boolean',
						'description' => 'Hide Genesis SEO Metabox',
					),
					'removeGenesisSeoFromAdminBar'         => array(
						'type'        => 'Boolean',
						'description' => 'Hide Genesis SEO Settings link',
					),
					'removeAdviceStructuredDataMetabox'    => array(
						'type'        => 'Boolean',
						'description' => 'Hide advice in Structured Data Types metabox',
					),
					'permissionStructuredDataTypesMetabox' => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Block access to Structured Data Types metaboxes in editor.',
					),
					'permissionMetaboxSEO'                 => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Block access to SEO metaboxes in editor.',
					),
					'permissionMetaboxContentAnalysis'     => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Block access to Content Analysis metaboxes in editor.',
					),
					'permissionTitlesMetasPageSettings'    => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Which user roles are allowed to access Titles & Metas settings page.',
					),
					'permissionXmlHtmlSitemapPageSettings' => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Which user roles are allowed to access XML - HTML Sitemap settings page.',
					),
					'permissionSocialNetworksPageSettings' => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Which user roles are allowed to access Social Networks settings page.',
					),
					'permissionAnalyticsPageSettings'      => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Which user roles are allowed to access Analytics settings page.',
					),
					'permissionAdvancedPageSettings'       => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Which user roles are allowed to access Advanced settings page.',
					),
					'permissionToolsPageSettings'          => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Which user roles are allowed to access Tools settings page.',
					),
					'permissionProPageSettings'            => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Which user roles are allowed to access PRO settings page.',
					),
					'permissionBotPageSettings'            => array(
						'type'        => 'SEOPressSettings_AdvancedUserEnable',
						'description' => 'Which user roles are allowed to access BOT settings page.',
					),
				),
			)
		);

		// Tools Settings.
		register_graphql_object_type(
			'SEOPressSettings_Tools',
			array(
				'description' => 'SEOPress Tools settings pages.',
				'fields'      => array(
					'compatibilityOxygen'     => array(
						'type'        => 'Boolean',
						'description' => 'Is compatibility for Oxygen Builder enabled.',
					),
					'compatibilityDivi'       => array(
						'type'        => 'Boolean',
						'description' => 'Is compatibility for Divi Builder enabled.',
					),
					'compatibilityWpBakery'   => array(
						'type'        => 'Boolean',
						'description' => 'Is compatibility for WP Bakery enabled.',
					),
					'compatibilityAviaLayout' => array(
						'type'        => 'Boolean',
						'description' => 'Is compatibility for Avia Layout Builder enabled.',
					),
					'compatibilityFusion'     => array(
						'type'        => 'Boolean',
						'description' => 'Is compatibility for Fusion Builder enabled.',
					),
				),
			)
		);

		// PRO Settings.
		register_graphql_object_type(
			'SEOPressSettings_Time',
			array(
				'description' => 'SEOPress hour and minute times.',
				'fields'      => array(
					'hour'   => array(
						'type'        => 'Integer',
						'description' => 'The number of the hour (0-23).',
					),
					'minute' => array(
						'type'        => 'Integer',
						'description' => 'The number of the minute (0-59).',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_OpenHours',
			array(
				'description' => 'Is business open, and start and end times.',
				'fields'      => array(
					'open'  => array(
						'type'        => 'String',
						'description' => 'The business is open.',
					),
					'start' => array(
						'type'        => 'SEOPressSettings_Time',
						'description' => 'Start time of open hours.',
					),
					'end'   => array(
						'type'        => 'SEOPressSettings_Time',
						'description' => 'End time of open hours.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_OpenHoursAmPm',
			array(
				'description' => 'Is business closed all day and when open.',
				'fields'      => array(
					'closed' => array(
						'type'        => 'Boolean',
						'description' => 'The business is closed on this day.',
					),
					'am'     => array(
						'type'        => 'SEOPressSettings_OpenHours',
						'description' => 'Hours open on Monday.',
					),
					'pm'     => array(
						'type'        => 'SEOPressSettings_OpenHours',
						'description' => 'Hours open on Tuesday.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_BusinessDays',
			array(
				'description' => 'List of days.',
				'fields'      => array(
					'monday'    => array(
						'type'        => 'SEOPressSettings_OpenHoursAmPm',
						'description' => 'Hours open on Monday.',
					),
					'tuesday'   => array(
						'type'        => 'SEOPressSettings_OpenHoursAmPm',
						'description' => 'Hours open on Tuesday.',
					),
					'wednesday' => array(
						'type'        => 'SEOPressSettings_OpenHoursAmPm',
						'description' => 'Hours open on Wednesday.',
					),
					'thursday'  => array(
						'type'        => 'SEOPressSettings_OpenHoursAmPm',
						'description' => 'Hours open on Thursday.',
					),
					'friday'    => array(
						'type'        => 'SEOPressSettings_OpenHoursAmPm',
						'description' => 'Hours open on Friday.',
					),
					'saturday'  => array(
						'type'        => 'SEOPressSettings_OpenHoursAmPm',
						'description' => 'Hours open on Saturday.',
					),
					'sunday'    => array(
						'type'        => 'SEOPressSettings_OpenHoursAmPm',
						'description' => 'Hours open on Sunday.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_BreadcrumbCptShow',
			array(
				'description' => 'Custom post type to show.',
				'fields'      => array(
					'customPostType' => array(
						'type'        => 'String',
						'description' => 'The custom post type to show in breadcrumbs.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_BreadcrumbCptList',
			array(
				'description' => 'Which custom post types to show in breadcrumbs.',
				'fields'      => array(
					'category'        => array(
						'type' => 'SEOPressSettings_BreadcrumbCptShow',
					),
					'postTag'         => array(
						'type' => 'SEOPressSettings_BreadcrumbCptShow',
					),
					'productCategory' => array(
						'type' => 'SEOPressSettings_BreadcrumbCptShow',
					),
					'productTag'      => array(
						'type' => 'SEOPressSettings_BreadcrumbCptShow',
					),
					// TODO: Dynamically generate this list based on all custom taxonomies registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_BreadcrumbTaxonomiesShow',
			array(
				'description' => 'Taxonomy to show.',
				'fields'      => array(
					'taxonomy' => array(
						'type'        => 'String',
						'description' => 'The custom post type to show in breadcrumbs.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_BreadcrumbTaxonomiesList',
			array(
				'description' => 'Which custom taxonomy to show in breadcrumbs.',
				'fields'      => array(
					'post'    => array(
						'type' => 'SEOPressSettings_BreadcrumbTaxonomiesShow',
					),
					'page'    => array(
						'type' => 'SEOPressSettings_BreadcrumbTaxonomiesShow',
					),
					'product' => array(
						'type' => 'SEOPressSettings_BreadcrumbTaxonomiesShow',
					),
					// TODO: Dynamically generate this list based on all custom post types registered.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_GoogleNewsPostTypesEnabled',
			array(
				'description' => 'Is this enabled',
				'fields'      => array(
					'enabled' => array(
						'type'         => 'Boolean',
						'descriptions' => 'This post type is enabled.',
					),
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_GoogleNewsPostTypes',
			array(
				'description' => 'Title and Description Format.',
				'fields'      => array(
					'post'            => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'page'            => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'attachment'      => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'wpBlock'         => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'product'         => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'shopOrder'       => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'shopCoupon'      => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'actionMonitor'   => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'seopressBot'     => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'seopress404'     => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					'seopressSchemas' => array(
						'type' => 'SEOPressSettings_GoogleNewsPostTypesEnabled',
					),
					// TODO: Dynamically generate this list.
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_Pro',
			array(
				'description' => 'SEOPress Tools settings pages.',
				'fields'      => array(
					'localBusinessSchemaPage'              => array(
						'type'        => 'String',
						'description' => 'Which page to display schema on.',
					),
					'localBusinessType'                    => array(
						'type'        => 'String',
						'description' => 'What type of local business schema type to use.',
					),
					'localBusinessStreetAddress'           => array(
						'type'        => 'String',
						'description' => 'Street address to use in local business schema.',
					),
					'localBusinessCity'                    => array(
						'type'        => 'String',
						'description' => 'City to use in local business schema.',
					),
					'localBusinessState'                   => array(
						'type'        => 'String',
						'description' => 'State to use in local business schema.',
					),
					'localBusinessPostalCode'              => array(
						'type'        => 'String',
						'description' => 'Postal code to use in local business schema.',
					),
					'localBusinessCountry'                 => array(
						'type'        => 'String',
						'description' => 'Country to use in local business schema.',
					),
					'localBusinessLatitude'                => array(
						'type'        => 'String',
						'description' => 'Latitude to use in local business schema.',
					),
					'localBusinessLongitude'               => array(
						'type'        => 'String',
						'description' => 'Longitude to use in local business schema.',
					),
					'localBusinessPlaceId'                 => array(
						'type'        => 'String',
						'description' => 'Place ID to use in local business schema. (Ref: https://developers.google.com/places/web-service/place-id)',
					),
					'localBusinessUrl'                     => array(
						'type'        => 'String',
						'description' => 'URL to use in local business schema.',
					),
					'localBusinessPhone'                   => array(
						'type'        => 'String',
						'description' => 'Phone to use in local business schema.',
					),
					'localBusinessPriceRange'              => array(
						'type'        => 'String',
						'description' => 'Price range to use in local business schema.',
					),
					'localBusinessCuisine'                 => array(
						'type'        => 'String',
						'description' => 'Cuisine to use in local business schema.',
					),
					'localBusinessOpeningHours'            => array(
						'type'        => 'SEOPressSettings_BusinessDays',
						'description' => 'Open Hours to use in local business schema.',
					),
					'woocommerceNoindexCartPage'           => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'woocommerceNoindexCheckoutPage'       => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'woocommerceNoindexAccountPage'        => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'woocommerceAddProductPriceAmountMeta' => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'woocommerceAddProductPriceCurrencyMeta' => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'woocommerceRemoveGeneratorMeta'       => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'woocommerceRemoveDefaultJsonLdSchema' => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'woocommerceRemoveBreadcrumbsSchema'   => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'easyDigitalDownloadsAddProductPriceAmountMeta' => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'easyDigitalDownloadsAddProductPriceCurrencyMeta' => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'easyDigitalDownloadsRemoveGeneratorMeta' => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'dublinCoreEnabled'                    => array(
						'type'        => 'Boolean',
						'description' => 'Dublin Core meta tags enabled.',
					),
					'richSnippetsEnabled'                  => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'richSnippetsPublisherLogo'            => array(
						'type'        => 'String',
						'description' => '',
					),
					'richSnippetsPublisherLogoWidth'       => array(
						'type'        => 'String',
						'description' => '',
					),
					'richSnippetsPublisherLogoHeight'      => array(
						'type'        => 'String',
						'description' => '',
					),
					'richSnippetsSiteNavigation'           => array(
						'type'        => 'String',
						'description' => '',
					),
					'breadcrumbsEnabled'                   => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'breadcrumbsJsonEnabled'               => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'breadcrumbsSeparator'                 => array(
						'type'        => 'String',
						'description' => '',
					),
					'breadcrumbsPostTypeShownCustomPostType' => array(
						'type'        => 'SEOPressSettings_BreadcrumbCptList',
						'description' => 'Post type to show in Breadcrumbs for custom post types.',
					),
					'breadcrumbsPostTypeShownTaxonomy'     => array(
						'type'        => 'SEOPressSettings_BreadcrumbTaxonomiesList',
						'description' => 'Post type to show in Breadcrumbs for taxonomies.',
					),
					'breadcrumbsI18nHere'                  => array(
						'type'        => 'String',
						'description' => 'HTML prefixing breadcrumbs.',
					),
					'breadcrumbsI18nHome'                  => array(
						'type'        => 'String',
						'description' => 'Translation for "Homepage".',
					),
					'breadcrumbsI18nAuthor'                => array(
						'type'        => 'String',
						'description' => 'Translation for "Author:".',
					),
					'breadcrumbsI18n404'                   => array(
						'type'        => 'String',
						'description' => 'Translation for "Error 404".',
					),
					'breadcrumbsI18nSearch'                => array(
						'type'        => 'String',
						'description' => 'Translation for "Search results for".',
					),
					'breadcrumbsI18nNoResults'             => array(
						'type'        => 'String',
						'description' => 'Translation for "No results".',
					),
					'breadcrumbsRemoveStaticPosts'         => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'breadcrumbsRemoveStaticShopPage'      => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'breadcrumbsRemoveDefaultSeparator'    => array(
						'type'        => 'Boolean',
						'description' => '',
					),
					'robotsFileEnabled'                    => array(
						'type'        => 'Boolean',
						'description' => 'Is the robots file enabled in SEOPress.',
					),
					'robotsFileContents'                   => array(
						'type'        => 'String',
						'description' => 'The contents of the `robots.txt` file that is controlled by SEOPress.',
					),
					'googleNewsEnabled'                    => array(
						'type'        => 'Boolean',
						'description' => 'Is the Google News sitemap enabled.',
					),
					'googleNewsName'                       => array(
						'type'        => 'String',
						'description' => 'The Google News publisher name.',
					),
					'googleNewsPostTypes'                  => array(
						'type'        => 'SEOPressSettings_GoogleNewsPostTypes',
						'description' => 'The list of post types that will be shown in Google News sitemap.',
					),
					'notFoundMonitoringEnabled'            => array(
						'type'        => 'Boolean',
						'description' => 'Enable 404 monitoring',
					),
					'notFoundCleaningEnabled'              => array(
						'type'        => 'Boolean',
						'description' => ' Automatically delete 404 after 30 days (useful if you have a lot of 404).',
					),
					'notFoundRedirectTo'                   => array(
						'type'        => 'String',
						'description' => 'Where to redirect 404s to (custom, home, or none).',
					),
					'notFoundRedirectToCustomUrl'          => array(
						'type'        => 'String',
						'description' => 'URL to redirect to in case of 404.',
					),
					'notFoundRedirectionStatusCode'        => array(
						'type'        => 'String',
						'description' => 'The HTTP status code to use on redirecting a 404.',
					),
					'notFoundEmailNotificationEnabled'     => array(
						'type'        => 'Boolean',
						'description' => 'Should emails be sent when a new 404 is created.',
					),
					'notFoundEmailNotificationAddress'     => array(
						'type'        => 'String',
						'description' => 'The address for email notification of a new 404.',
					),
					'notFoundEmailNotificationNoAutomaticRedirect' => array(
						'type'        => 'Boolean',
						'description' => 'Disable email for automatic redirections (if post URL changed).',
					),
					'notFoundIpLogging'                    => array(
						'type'        => 'String',
						'description' => 'How much of the IP should be logged when user hits a 404 (none, full, anon).',
					),
					'rssHtmlPrefix'                        => array(
						'type'        => 'String',
						'description' => 'HTML before each post in RSS feed.',
					),
					'rssHtmlSuffix'                        => array(
						'type'        => 'String',
						'description' => 'HTML after each post in RSS feed.',
					),
					'rssCommentsDisabled'                  => array(
						'type'        => 'Boolean',
						'description' => 'Remove link to comments RSS feed.',
					),
					'rssPostsDisabled'                     => array(
						'type'        => 'Boolean',
						'description' => 'Remove link to posts RSS feed.',
					),
					'rssExtraDisabled'                     => array(
						'type'        => 'Boolean',
						'description' => 'Remove link to extra RSS feed (used for: author, categories, custom taxonomies, custom post type, comments feed for a single post...).',
					),
					'rssAllDisabled'                       => array(
						'type'        => 'Boolean',
						'description' => 'Disable all RSS feeds.',
					),
					'rewriteSearchUrl'                     => array(
						'type'        => 'String',
						'description' => 'The custom URL for the search page.',
					),
					'whiteLabelRemoveAdminHeader'          => array(
						'type'        => 'Boolean',
						'description' => 'Remove the SEOPress admin header including Notifications Center, SEO tools and Useful links.',
					),
					'whiteLabelRemoveHeaderIcons'          => array(
						'type'        => 'Boolean',
						'description' => 'Remove SEOPress icons on the right in header (changelog, YouTube, Twitter...)',
					),
					'whiteLabelFilterSeoDashiconsClass'    => array(
						'type'        => 'String',
						'description' => 'Custom CSS class for Dashicons.',
					),
					'whiteLabelSEOPressAdminBarTitle'      => array(
						'type'        => 'String',
						'description' => 'The title to display for SEOPress in the top admin bar.',
					),
					'whiteLabelSEOPressMainMenuTitle'      => array(
						'type'        => 'String',
						'description' => 'The title to display for SEOPress in the dashboard sidebar.',
					),
					'whiteLabelSEOPressAdminBarImage'      => array(
						'type'        => 'String',
						'description' => 'The URL to the whitelabel image.',
					),
					'whiteLabelRemoveCredits'              => array(
						'type'        => 'Boolean',
						'description' => 'Is the footer credits to SEOPress removed.',
					),
					'whiteLabelRemoveHelpDocumentationIcons' => array(
						'type'        => 'Boolean',
						'description' => 'Hide SEOPress link/help icons.',
					),
					'whiteLabelSEOPressName'               => array(
						'type'        => 'String',
						'description' => 'The name for SEOPress to display as in the plugin list.',
					),
					'whiteLabelSEOPressProName'            => array(
						'type'        => 'String',
						'description' => 'The name for SEOPress Pro to display as in the plugin list.',
					),
					'whiteLabelSEOPressDescription'        => array(
						'type'        => 'String',
						'description' => 'The description for SEOPress to display with in the plugin list.',
					),
					'whiteLabelSEOPressProDescription'     => array(
						'type'        => 'String',
						'description' => 'The description for SEOPress Pro to display with in the plugin list.',
					),
					'whiteLabelSEOPressAuthor'             => array(
						'type'        => 'String',
						'description' => 'The author for SEOPress to display with in the plugin list.',
					),
					'whiteLabelSEOPressWebsite'            => array(
						'type'        => 'String',
						'description' => 'The author URL for SEOPress to display with in the plugin list.',
					),
					'whiteLabelRemoveViewDetails'          => array(
						'type'        => 'Boolean',
						'description' => 'Remove the "View Details" button in the plugin list.',
					),
				),
			)
		);

		// Object that includes all settings pages.
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
					'social'         => array(
						'type' => 'SEOPressSettings_SocialNetworks',
					),
					'analytics'      => array(
						'type' => 'SEOPressSettings_Analytics',
					),
					'advanced'       => array(
						'type' => 'SEOPressSettings_Advanced',
					),
					'tools'          => array(
						'type' => 'SEOPressSettings_Tools',
					),
					'pro'            => array(
						'type' => 'SEOPressSettings_Pro',
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
							'post'    => array(
								'title'       => $seopress_titles_options['seopress_titles_single_titles']['post']['title'],
								'description' => $seopress_titles_options['seopress_titles_single_titles']['post']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_single_titles']['post']['noindex'],
								'nofollow'    => (bool) $seopress_titles_options['seopress_titles_single_titles']['post']['nofollow'],
								'date'        => (bool) $seopress_titles_options['seopress_titles_single_titles']['post']['date'],
								'thumb_gcs'   => (bool) $seopress_titles_options['seopress_titles_single_titles']['post']['thumb_gcs'],
							),
							'page'    => array(
								'title'       => $seopress_titles_options['seopress_titles_single_titles']['page']['title'],
								'description' => $seopress_titles_options['seopress_titles_single_titles']['page']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_single_titles']['page']['noindex'],
								'nofollow'    => (bool) $seopress_titles_options['seopress_titles_single_titles']['page']['nofollow'],
								'date'        => (bool) $seopress_titles_options['seopress_titles_single_titles']['page']['date'],
								'thumb_gcs'   => (bool) $seopress_titles_options['seopress_titles_single_titles']['page']['thumb_gcs'],
							),
							'product' => array(
								'title'       => $seopress_titles_options['seopress_titles_single_titles']['product']['title'],
								'description' => $seopress_titles_options['seopress_titles_single_titles']['product']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_single_titles']['product']['noindex'],
								'nofollow'    => (bool) $seopress_titles_options['seopress_titles_single_titles']['product']['thumb_gcs'],
								'date'        => (bool) $seopress_titles_options['seopress_titles_single_titles']['product']['date'],
								'thumb_gcs'   => (bool) $seopress_titles_options['seopress_titles_single_titles']['product']['thumb_gcs'],
							),
						),
						'archive_titles'          => array(
							'product' => array(
								'title'       => $seopress_titles_options['seopress_titles_archive_titles']['product']['title'],
								'description' => $seopress_titles_options['seopress_titles_archive_titles']['product']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_archive_titles']['product']['noindex'],
								'nofollow'    => (bool) $seopress_titles_options['seopress_titles_archive_titles']['product']['nofollow'],
							),
						),
						'archives_author_title'   => $seopress_titles_options['seopress_titles_archives_author_title'],
						'archives_author_desc'    => $seopress_titles_options['seopress_titles_archives_author_desc'],
						'archives_author_noindex' => (bool) $seopress_titles_options['seopress_titles_archives_author_noindex'],
						'archives_author_disable' => (bool) $seopress_titles_options['seopress_titles_archives_author_disable'],
						'archives_date_title'     => $seopress_titles_options['seopress_titles_archives_date_title'],
						'archives_date_desc'      => $seopress_titles_options['seopress_titles_archives_date_desc'],
						'archives_date_noindex'   => (bool) $seopress_titles_options['seopress_titles_archives_date_noindex'],
						'archives_date_disable'   => (bool) $seopress_titles_options['seopress_titles_archives_date_disable'],
						'archives_search_title'   => $seopress_titles_options['seopress_titles_archives_search_title'],
						'archives_search_desc'    => $seopress_titles_options['seopress_titles_archives_search_desc'],
						'archives_search_noindex' => (bool) $seopress_titles_options['seopress_titles_archives_search_title_noindex'],
						'archives_404_title'      => $seopress_titles_options['seopress_titles_archives_404_title'],
						'archives_404_desc'       => $seopress_titles_options['seopress_titles_archives_404_desc'],
						'tax_titles'              => array(
							'category'        => array(
								'disable'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['category']['enable'],
								'title'       => $seopress_titles_options['seopress_titles_tax_titles']['category']['title'],
								'description' => $seopress_titles_options['seopress_titles_tax_titles']['category']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['category']['noindex'],
								'nofollow'    => (bool) $seopress_titles_options['seopress_titles_tax_titles']['category']['nofollow'],
							),
							'post_tag'        => array(
								'disable'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['enable'],
								'title'       => $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['title'],
								'description' => $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['noindex'],
								'nofollow'    => (bool) $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['nofollow'],
							),
							'productCategory' => array(
								'disable'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['product_cat']['enable'],
								'title'       => $seopress_titles_options['seopress_titles_tax_titles']['product_cat']['title'],
								'description' => $seopress_titles_options['seopress_titles_tax_titles']['product_cat']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['product_cat']['noindex'],
								'nofollow'    => (bool) $seopress_titles_options['seopress_titles_tax_titles']['product_cat']['nofollow'],
							),
							'productTag'      => array(
								'disable'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['product_tag']['enable'],
								'title'       => $seopress_titles_options['seopress_titles_tax_titles']['product_tag']['title'],
								'description' => $seopress_titles_options['seopress_titles_tax_titles']['product_tag']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['product_tag']['noindex'],
								'nofollow'    => (bool) $seopress_titles_options['seopress_titles_tax_titles']['product_tag']['nofollow'],
							),
						),
						'noindex'                 => (bool) $seopress_titles_options['seopress_titles_noindex'],
						'nofollow'                => (bool) $seopress_titles_options['seopress_titles_nofollow'],
						'noodp'                   => (bool) $seopress_titles_options['seopress_titles_noodp'],
						'noimageindex'            => (bool) $seopress_titles_options['seopress_titles_noimageindex'],
						'noarchive'               => (bool) $seopress_titles_options['seopress_titles_noarchive'],
						'nosnippet'               => (bool) $seopress_titles_options['seopress_titles_nosnippet'],
						'nositelinkssearchbox'    => (bool) $seopress_titles_options['seopress_titles_nositelinkssearchbox'],
						'paged_rel'               => (bool) $seopress_titles_options['seopress_titles_paged_rel'],
						'paged_noindex'           => (bool) $seopress_titles_options['seopress_titles_paged_noindex'],
						'attachments_noindex'     => (bool) $seopress_titles_options['seopress_titles_attachments_noindex'],
					);
					$seopress_xml_html_sitemap_options = get_option( 'seopress_xml_sitemap_option_name' );
					$seopress_xml_html_sitemap_settings = array(
						'xmlSitemapGeneralEnabled' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_general_enable'],
						'xmlSitemapImageEnabled'   => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_img_enable'],
						'xmlSitemapVideoEnabled'   => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_video_enable'],
						'xmlSitemapAuthorEnabled'  => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_author_enable'],
						'xmlSitemapHTMLEnabled'    => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_enable'],
						'sitemapPostTypes'         => array(
							'post'       => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_post_types_list']['post']['include'],
							),
							'page'       => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_post_types_list']['page']['include'],
							),
							'attachment' => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_post_types_list']['attachment']['include'],
							),
							'product'    => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_post_types_list']['product']['include'],
							),
						),
						'sitemapTaxonomies'        => array(
							'category'        => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_taxonomies_list']['category']['include'],
							),
							'postTag'         => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_taxonomies_list']['post_tag']['include'],
							),
							'productCategory' => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_taxonomies_list']['product_cat']['include'],
							),
							'productTag'      => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_taxonomies_list']['product_tag']['include'],
							),
						),
						'htmlMapping'              => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_mapping'],
						'htmlExclude'              => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_exclude'],
						'htmlOrder'                => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_order'],
						'htmlOrderby'              => $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_orderby'],
						'htmlDate'                 => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_date'],
						'htmlArchiveLinks'         => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_html_archive_links'],
					);
					$seopress_social_network_options = get_option( 'seopress_social_option_name' );
					$seopress_social_network_settings = array(
						'knowledgeType'              => $seopress_social_network_options['seopress_social_knowledge_type'],
						'knowledgeName'              => $seopress_social_network_options['seopress_social_knowledge_name'],
						'knowledgeImg'               => $seopress_social_network_options['seopress_social_knowledge_img'],
						'knowledgePhone'             => $seopress_social_network_options['seopress_social_knowledge_phone'],
						'knowledgeContactType'       => $seopress_social_network_options['seopress_social_knowledge_contact_type'],
						'knowledgeContactOption'     => $seopress_social_network_options['seopress_social_knowledge_contact_option'],
						'accountFacebook'            => $seopress_social_network_options['seopress_social_accounts_facebook'],
						'accountTwitter'             => $seopress_social_network_options['seopress_social_accounts_twitter'],
						'accountPinterest'           => $seopress_social_network_options['seopress_social_accounts_pinterest'],
						'accountInstagram'           => $seopress_social_network_options['seopress_social_accounts_instagram'],
						'accountYoutube'             => $seopress_social_network_options['seopress_social_accounts_youtube'],
						'accountLinkedIn'            => $seopress_social_network_options['seopress_social_accounts_linkedin'],
						'facebookOg'                 => (bool) $seopress_social_network_options['seopress_social_facebook_og'],
						'facebookImg'                => $seopress_social_network_options['seopress_social_facebook_img'],
						'facebookImgDefault'         => (bool) $seopress_social_network_options['seopress_social_facebook_img_default'],
						'facebookImgCustomPostTypes' => array(
							'product' => array(
								'url' => $seopress_social_network_options['seopress_social_facebook_img_cpt']['product']['url'],
							),
						),
						'facebookLinkOwnershipId'    => $seopress_social_network_options['seopress_social_facebook_link_ownership_id'],
						'facebookAdminId'            => $seopress_social_network_options['seopress_social_facebook_admin_id'],
						'facebookAppId'              => $seopress_social_network_options['seopress_social_facebook_app_id'],
						'twitterCard'                => (bool) $seopress_social_network_options['seopress_social_twitter_card'],
						'twitterCardOg'              => (bool) $seopress_social_network_options['seopress_social_twitter_card_og'],
						'twitterCardImg'             => $seopress_social_network_options['seopress_social_twitter_card_img'],
						'twitterCardImgSize'         => $seopress_social_network_options['seopress_social_twitter_card_img_size'],
					);
					$seopress_google_analytics_options = get_option( 'seopress_google_analytics_option_name' );
					$seopress_google_analytics_settings = array(
						'googleAnalyticsEnable'            => (bool) $seopress_google_analytics_options['seopress_google_analytics_enable'],
						'googleAnalyticsUACode'            => $seopress_google_analytics_options['seopress_google_analytics_ua'],
						'googleAnalyticsGA4Code'           => $seopress_google_analytics_options['seopress_google_analytics_ga4'],
						'googleAnalyticsNoTrackRoles'      => array(
							'administrator' => (bool) $seopress_google_analytics_options['seopress_google_analytics_roles']['administrator'],
							'editor'        => (bool) $seopress_google_analytics_options['seopress_google_analytics_roles']['editor'],
							'author'        => (bool) $seopress_google_analytics_options['seopress_google_analytics_roles']['author'],
							'contributor'   => (bool) $seopress_google_analytics_options['seopress_google_analytics_roles']['contributor'],
							'subscriber'    => (bool) $seopress_google_analytics_options['seopress_google_analytics_roles']['subscriber'],
							'customer'      => (bool) $seopress_google_analytics_options['seopress_google_analytics_roles']['customer'],
							'shop_manager'  => (bool) $seopress_google_analytics_options['seopress_google_analytics_roles']['shop_manager'],
						),
						'googleAnalyticsOptimize'          => $seopress_google_analytics_options['seopress_google_analytics_optimize'],
						'googleAnalyticsAdWords'           => $seopress_google_analytics_options['seopress_google_analytics_ads'],
						'additionalTrackingHead'           => $seopress_google_analytics_options['seopress_google_analytics_other_tracking'],
						'additionalTrackingBody'           => $seopress_google_analytics_options['seopress_google_analytics_other_tracking_body'],
						'additionalTrackingFooter'         => $seopress_google_analytics_options['seopress_google_analytics_other_tracking_footer'],
						'googleAnalyticsRemarketing'       => (bool) $seopress_google_analytics_options['seopress_google_analytics_remarketing'],
						'googleAnalyticsIpAnonymization'   => (bool) $seopress_google_analytics_options['seopress_google_analytics_ip_anonymization'],
						'googleAnalyticsLinkAttribution'   => (bool) $seopress_google_analytics_options['seopress_google_analytics_link_attribution'],
						'googleAnalyticsCrossEnable'       => (bool) $seopress_google_analytics_options['seopress_google_analytics_cross_enable'],
						'googleAnalyticsCrossDomainList'   => $seopress_google_analytics_options['seopress_google_analytics_cross_domain'],
						'googleAnalyticsLinkTrackingEnable' => (bool) $seopress_google_analytics_options['seopress_google_analytics_link_tracking_enable'],
						'googleAnalyticsDownloadTrackingEnable' => (bool) $seopress_google_analytics_options['seopress_google_analytics_download_tracking_enable'],
						'googleAnalyticsDownloadTracking'  => $seopress_google_analytics_options['seopress_google_analytics_download_tracking'],
						'googleAnalyticsAffiliateTrackingEnable' => (bool) $seopress_google_analytics_options['seopress_google_analytics_affiliate_tracking_enable'],
						'googleAnalyticsAffiliateTracking' => $seopress_google_analytics_options['seopress_google_analytics_affiliate_tracking'],
						'googleAnalyticsCustomDimensionAuthor' => $seopress_google_analytics_options['seopress_google_analytics_cd_author'],
						'googleAnalyticsCustomDimensionCategory' => $seopress_google_analytics_options['seopress_google_analytics_cd_category'],
						'googleAnalyticsCustomDimensionTag' => $seopress_google_analytics_options['seopress_google_analytics_cd_tag'],
						'googleAnalyticsCustomDimensionPostType' => $seopress_google_analytics_options['seopress_google_analytics_cd_post_type'],
						'googleAnalyticsCustomDimensionLoggedIn' => $seopress_google_analytics_options['seopress_google_analytics_cd_logged_in_user'],
						'googleAnalyticsApiClientId'       => $seopress_google_analytics_options['seopress_google_analytics_auth_client_id'],
						'googleAnalyticsApiSecretId'       => $seopress_google_analytics_options['seopress_google_analytics_auth_secret_id'],
						'googleAnalyticsDashboardWidget'   => (bool) $seopress_google_analytics_options['seopress_google_analytics_dashboard_widget'],
						'googleAnalyticsEventPurchases'    => (bool) $seopress_google_analytics_options['seopress_google_analytics_purchases'],
						'googleAnalyticsEventAddToCart'    => (bool) $seopress_google_analytics_options['seopress_google_analytics_add_to_cart'],
						'googleAnalyticsEventRemoveFromCart' => (bool) $seopress_google_analytics_options['seopress_google_analytics_remove_from_cart'],
						'googleAnalyticsHtmlLocation'      => $seopress_google_analytics_options['seopress_google_analytics_hook'],
						'cookieConsentRequired'            => (bool) $seopress_google_analytics_options['seopress_google_analytics_disable'],
						'cookieConsentAutomatic'           => (bool) $seopress_google_analytics_options['seopress_google_analytics_half_disable'],
						'cookieConsentChangeChoice'        => (bool) $seopress_google_analytics_options['seopress_google_analytics_opt_out_edit_choice'],
						'cookieConsentOptOutMessage'       => $seopress_google_analytics_options['seopress_google_analytics_opt_out_msg'],
						'cookieConsentOptOutMessageOk'     => $seopress_google_analytics_options['seopress_google_analytics_opt_out_msg_ok'],
						'cookieConsentOptOutClose'         => $seopress_google_analytics_options['seopress_google_analytics_opt_out_msg_close'],
						'cookieConsentOptOutEdit'          => $seopress_google_analytics_options['seopress_google_analytics_opt_out_msg_edit'],
						'cookieConsentCookieValidity'      => $seopress_google_analytics_options['seopress_google_analytics_cb_exp_date'] ? $seopress_google_analytics_options['seopress_google_analytics_cb_exp_date'] : 0,
						'cookieConsentPosition'            => $seopress_google_analytics_options['seopress_google_analytics_cb_pos'],
						'cookieConsentTextAlign'           => $seopress_google_analytics_options['seopress_google_analytics_cb_txt_align'],
						'cookieConsentBarWidth'            => $seopress_google_analytics_options['seopress_google_analytics_cb_width'],
						'cookieConsentBackdropCustomized'  => (bool) $seopress_google_analytics_options['seopress_google_analytics_cb_backdrop'],
						'cookieConsentBackdropColor'       => $seopress_google_analytics_options['seopress_google_analytics_cb_backdrop_bg'],
						'cookieConsentBackgroundColor'     => $seopress_google_analytics_options['seopress_google_analytics_cb_bg'],
						'cookieConsentTextColor'           => $seopress_google_analytics_options['seopress_google_analytics_cb_txt_col'],
						'cookieConsentLinkColor'           => $seopress_google_analytics_options['seopress_google_analytics_cb_lk_col'],
						'cookieConsentAcceptButtonBackgroundColor' => $seopress_google_analytics_options['seopress_google_analytics_cb_btn_bg'],
						'cookieConsentAcceptButtonBackgroundColorHover' => $seopress_google_analytics_options['seopress_google_analytics_cb_btn_bg_hov'],
						'cookieConsentAcceptButtonColor'   => $seopress_google_analytics_options['seopress_google_analytics_cb_btn_col'],
						'cookieConsentAcceptButtonColorHover' => $seopress_google_analytics_options['seopress_google_analytics_cb_btn_col_hov'],
						'cookieConsentCloseBackgroundColor' => $seopress_google_analytics_options['seopress_google_analytics_cb_btn_sec_bg'],
						'cookieConsentCloseColor'          => $seopress_google_analytics_options['seopress_google_analytics_cb_btn_sec_col'],
						'cookieConsentCloseBackgroundColorHover' => $seopress_google_analytics_options['seopress_google_analytics_cb_btn_sec_bg_hov'],
						'cookieConsentCloseColorHover'     => $seopress_google_analytics_options['seopress_google_analytics_cb_btn_sec_col_hov'],
						'matomoEnable'                     => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_enable'],
						'matomoHost'                       => $seopress_google_analytics_options['seopress_google_analytics_matomo_id'],
						'matomoSiteId'                     => $seopress_google_analytics_options['seopress_google_analytics_matomo_site_id'],
						'matomoSubdomainTracking'          => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_subdomains'],
						'matomoPrependDomain'              => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_site_domain'],
						'matomoTrackWithoutJavascript'     => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_no_js'],
						'matomoCrossDomainTracking'        => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_cross_domain'],
						'matomoCrossDomainTrackingList'    => $seopress_google_analytics_options['seopress_google_analytics_matomo_cross_domain_sites'],
						'matomoHonorDoNotTrack'            => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_dnt'],
						'matomoNoCookies'                  => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_no_cookies'],
						'matomoLinkDownloadTracking'       => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_link_tracking'],
						'matomoNoHeatmaps'                 => (bool) $seopress_google_analytics_options['seopress_google_analytics_matomo_no_heatmaps'],
					);
					$seopress_advanced_options = get_option( 'seopress_advanced_option_name' );
					$seopress_advanced_settings = array(
						'redirectAttachmentPagesToParent'  => (bool) $seopress_advanced_options['seopress_advanced_advanced_attachments'],
						'redirectAttachmentPagesToFile'    => (bool) $seopress_advanced_options['seopress_advanced_advanced_attachments_file'],
						'removeReplytocom'                 => (bool) $seopress_advanced_options['seopress_advanced_advanced_replytocom'],
						'automaticImageTitle'              => (bool) $seopress_advanced_options['seopress_advanced_advanced_image_auto_title_editor'],
						'automaticImageAltText'            => (bool) $seopress_advanced_options['seopress_advanced_advanced_image_auto_alt_editor'],
						'automaticImageAltTextFromKeywords' => (bool) $seopress_advanced_options['seopress_advanced_advanced_image_auto_alt_target_kw'],
						'automaticImageCaption'            => (bool) $seopress_advanced_options['seopress_advanced_advanced_image_auto_caption_editor'],
						'automaticImageDescription'        => (bool) $seopress_advanced_options['seopress_advanced_advanced_image_auto_desc_editor'],
						'addEditorToTaxonomy'              => (bool) $seopress_advanced_options['seopress_advanced_advanced_tax_desc_editor'],
						'removeCategoryInUrl'              => (bool) $seopress_advanced_options['seopress_advanced_advanced_category_url'],
						'removeProductCategoryInUrl'       => (bool) $seopress_advanced_options['seopress_advanced_advanced_product_cat_url'],
						'removeTrailingSlashMetas'         => (bool) $seopress_advanced_options['seopress_advanced_advanced_trailingslash'],
						'removeGeneratorMeta'              => (bool) $seopress_advanced_options['seopress_advanced_advanced_wp_generator'],
						'removeHentryPostClass'            => (bool) $seopress_advanced_options['seopress_advanced_advanced_hentry'],
						'removeCommentAuthorUrl'           => (bool) $seopress_advanced_options['seopress_advanced_advanced_comments_author_url'],
						'removeCommentFormWebsiteInput'    => (bool) $seopress_advanced_options['seopress_advanced_advanced_comments_website'],
						'removeShortlinkMeta'              => (bool) $seopress_advanced_options['seopress_advanced_advanced_wp_shortlink'],
						'removeWindowsLiveWriterMeta'      => (bool) $seopress_advanced_options['seopress_advanced_advanced_wp_wlw'],
						'removeReallySimpleDiscoveryMeta'  => (bool) $seopress_advanced_options['seopress_advanced_advanced_wp_rsd'],
						'addSiteVerificationGoogle'        => $seopress_advanced_options['seopress_advanced_advanced_google'],
						'addSiteVerificationBing'          => $seopress_advanced_options['seopress_advanced_advanced_bing'],
						'addSiteVerificationPinterest'     => $seopress_advanced_options['seopress_advanced_advanced_pinterest'],
						'addSiteVerificationYandex'        => $seopress_advanced_options['seopress_advanced_advanced_yandex'],
						'removeSeoAdminBar'                => (bool) $seopress_advanced_options['seopress_advanced_appearance_adminbar'],
						'enableUniversalSeoMetabox'        => (bool) $seopress_advanced_options['seopress_advanced_appearance_universal_metabox'],
						'removeUniversalSeoMetabox'        => (bool) $seopress_advanced_options['seopress_advanced_appearance_universal_metabox_disable'],
						'removeNoindexFromAdminBar'        => (bool) $seopress_advanced_options['seopress_advanced_appearance_adminbar_noindex'],
						'metaboxSeoPosition'               => $seopress_advanced_options['seopress_advanced_appearance_metaboxe_position'],
						'metaboxStructuredDataDefaultTab'  => $seopress_advanced_options['seopress_advanced_appearance_schema_default_tab'],
						'removeNotificationCenter'         => (bool) $seopress_advanced_options['seopress_advanced_appearance_notifications'],
						'removeSeoNews'                    => (bool) $seopress_advanced_options['seopress_advanced_appearance_news'],
						'removeSeoTools'                   => (bool) $seopress_advanced_options['seopress_advanced_appearance_seo_tools'],
						'enableTitleTagColumn'             => (bool) $seopress_advanced_options['seopress_advanced_appearance_title_col'],
						'enableMetaDescriptionColumn'      => (bool) $seopress_advanced_options['seopress_advanced_appearance_meta_desc_col'],
						'enableRedirectionEnableColumn'    => (bool) $seopress_advanced_options['seopress_advanced_appearance_redirect_enable_col'],
						'enableRedirectUrlColumn'          => (bool) $seopress_advanced_options['seopress_advanced_appearance_redirect_url_col'],
						'enableCanonicalUrlColumn'         => (bool) $seopress_advanced_options['seopress_advanced_appearance_canonical'],
						'enableTargetKeywordColumn'        => (bool) $seopress_advanced_options['seopress_advanced_appearance_target_kw_col'],
						'enableNoindexColumn'              => (bool) $seopress_advanced_options['seopress_advanced_appearance_noindex_col'],
						'enableNofollowColumn'             => (bool) $seopress_advanced_options['seopress_advanced_appearance_nofollow_col'],
						'enableTotalWordsColumn'           => (bool) $seopress_advanced_options['seopress_advanced_appearance_words_col'],
						'enablePageSpeedColumn'            => (bool) $seopress_advanced_options['seopress_advanced_appearance_ps_col'],
						'enableContentAnalysisScoreColumn' => (bool) $seopress_advanced_options['seopress_advanced_appearance_score_col'],
						'removeContentAnalysisMetabox'     => (bool) $seopress_advanced_options['seopress_advanced_appearance_ca_metaboxe'],
						'removeGenesisSeoMetabox'          => (bool) $seopress_advanced_options['seopress_advanced_appearance_genesis_seo_metaboxe'],
						'removeGenesisSeoFromAdminBar'     => (bool) $seopress_advanced_options['seopress_advanced_appearance_genesis_seo_menu'],
						'removeAdviceStructuredDataMetabox' => (bool) $seopress_advanced_options['seopress_advanced_appearance_advice_schema'],
						'permissionStructuredDataTypesMetabox' => array(
							'administrator' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_sdt_role']['administrator'],
							'editor'        => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_sdt_role']['editor'],
							'author'        => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_sdt_role']['author'],
							'contributor'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_sdt_role']['contributor'],
							'subscriber'    => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_sdt_role']['subscriber'],
							'customer'      => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_sdt_role']['customer'],
							'shop_manager'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_sdt_role']['shop_manager'],
						),
						'permissionMetaboxSEO'             => array(
							'administrator' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_role']['administrator'],
							'editor'        => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_role']['editor'],
							'author'        => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_role']['author'],
							'contributor'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_role']['contributor'],
							'subscriber'    => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_role']['subscriber'],
							'customer'      => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_role']['customer'],
							'shop_manager'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_role']['shop_manager'],
						),
						'permissionMetaboxContentAnalysis' => array(
							'administrator' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_ca_role']['administrator'],
							'editor'        => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_ca_role']['editor'],
							'author'        => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_ca_role']['author'],
							'contributor'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_ca_role']['contributor'],
							'subscriber'    => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_ca_role']['subscriber'],
							'customer'      => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_ca_role']['customer'],
							'shop_manager'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_ca_role']['shop_manager'],
						),
						'permissionTitlesMetasPageSettings' => array(
							'editor'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-titles']['editor'],
							'author'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-titles']['author'],
							'contributor'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-titles']['contributor'],
							'subscriber'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-titles']['subscriber'],
							'customer'     => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-titles']['customer'],
							'shop_manager' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-titles']['shop_manager'],
						),
						'permissionXmlHtmlSitemapPageSettings' => array(
							'editor'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-xml-sitemap']['editor'],
							'author'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-xml-sitemap']['author'],
							'contributor'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-xml-sitemap']['contributor'],
							'subscriber'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-xml-sitemap']['subscriber'],
							'customer'     => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-xml-sitemap']['customer'],
							'shop_manager' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-xml-sitemap']['shop_manager'],
						),
						'permissionSocialNetworksPageSettings' => array(
							'editor'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-social']['editor'],
							'author'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-social']['author'],
							'contributor'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-social']['contributor'],
							'subscriber'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-social']['subscriber'],
							'customer'     => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-social']['customer'],
							'shop_manager' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-social']['shop_manager'],
						),
						'permissionAnalyticsPageSettings'  => array(
							'editor'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-google-analytics']['editor'],
							'author'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-google-analytics']['author'],
							'contributor'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-google-analytics']['contributor'],
							'subscriber'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-google-analytics']['subscriber'],
							'customer'     => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-google-analytics']['customer'],
							'shop_manager' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-google-analytics']['shop_manager'],
						),
						'permissionAdvancedPageSettings'   => array(
							'editor'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-advanced']['editor'],
							'author'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-advanced']['author'],
							'contributor'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-advanced']['contributor'],
							'subscriber'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-advanced']['subscriber'],
							'customer'     => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-advanced']['customer'],
							'shop_manager' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-advanced']['shop_manager'],
						),
						'permissionToolsPageSettings'      => array(
							'editor'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-import-export']['editor'],
							'author'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-import-export']['author'],
							'contributor'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-import-export']['contributor'],
							'subscriber'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-import-export']['subscriber'],
							'customer'     => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-import-export']['customer'],
							'shop_manager' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-import-export']['shop_manager'],
						),
						'permissionProPageSettings'        => array(
							'editor'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-pro-page']['editor'],
							'author'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-pro-page']['author'],
							'contributor'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-pro-page']['contributor'],
							'subscriber'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-pro-page']['subscriber'],
							'customer'     => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-pro-page']['customer'],
							'shop_manager' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-pro-page']['shop_manager'],
						),
						'permissionBotPageSettings'        => array(
							'editor'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-bot-batch']['editor'],
							'author'       => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-bot-batch']['author'],
							'contributor'  => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-bot-batch']['contributor'],
							'subscriber'   => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-bot-batch']['subscriber'],
							'customer'     => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-bot-batch']['customer'],
							'shop_manager' => (bool) $seopress_advanced_options['seopress_advanced_security_metaboxe_seopress-bot-batch']['shop_manager'],
						),
					);
					$seopress_tools_option = get_option( 'seopress_tools_option_name' );
					$seopress_tools_settings = array(
						'compatibilityOxygen'     => (bool) $seopress_tools_option['seopress_setting_section_tools_compatibility_oxygen'],
						'compatibilityDivi'       => (bool) $seopress_tools_option['seopress_setting_section_tools_compatibility_divi'],
						'compatibilityWpBakery'   => (bool) $seopress_tools_option['seopress_setting_section_tools_compatibility_bakery'],
						'compatibilityAviaLayout' => (bool) $seopress_tools_option['seopress_setting_section_tools_compatibility_avia'],
						'compatibilityFusion'     => (bool) $seopress_tools_option['seopress_setting_section_tools_compatibility_fusion'],
					);
					$seopress_pro_options = get_option( 'seopress_pro_option_name' );
					$seopress_pro_settings = array(
						'localBusinessSchemaPage'          => $seopress_pro_options['seopress_local_business_page'],
						'localBusinessType'                => $seopress_pro_options['seopress_local_business_type'],
						'localBusinessStreetAddress'       => $seopress_pro_options['seopress_local_business_street_address'],
						'localBusinessCity'                => $seopress_pro_options['seopress_local_business_address_locality'],
						'localBusinessState'               => $seopress_pro_options['seopress_local_business_address_region'],
						'localBusinessPostalCode'          => $seopress_pro_options['seopress_local_business_postal_code'],
						'localBusinessCountry'             => $seopress_pro_options['seopress_local_business_address_country'],
						'localBusinessLatitude'            => $seopress_pro_options['seopress_local_business_lat'],
						'localBusinessLongitude'           => $seopress_pro_options['seopress_local_business_lon'],
						'localBusinessPlaceId'             => $seopress_pro_options['seopress_local_business_place_id'],
						'localBusinessUrl'                 => $seopress_pro_options['seopress_local_business_url'],
						'localBusinessPhone'               => $seopress_pro_options['seopress_local_business_phone'],
						'localBusinessPriceRange'          => $seopress_pro_options['seopress_local_business_price_range'],
						'localBusinessCuisine'             => $seopress_pro_options['seopress_local_business_cuisine'],
						'localBusinessOpeningHours'        => array(
							'monday'    => array(
								'closed' => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][0]['open'],
								'am'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][0]['am']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][0]['am']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][0]['am']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][0]['am']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][0]['am']['end']['mins'],
									),
								),
								'pm'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][0]['pm']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][0]['pm']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][0]['pm']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][0]['pm']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][0]['pm']['end']['mins'],
									),
								),
							),
							'tuesday'   => array(
								'closed' => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][1]['open'],
								'am'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][1]['am']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][1]['am']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][1]['am']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][1]['am']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][1]['am']['end']['mins'],
									),
								),
								'pm'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][1]['pm']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][1]['pm']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][1]['pm']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][1]['pm']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][1]['pm']['end']['mins'],
									),
								),
							),
							'wednesday' => array(
								'closed' => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][2]['open'],
								'am'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][2]['am']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][2]['am']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][2]['am']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][2]['am']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][2]['am']['end']['mins'],
									),
								),
								'pm'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][2]['pm']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][2]['pm']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][2]['pm']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][2]['pm']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][2]['pm']['end']['mins'],
									),
								),
							),
							'thursday'  => array(
								'closed' => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][3]['open'],
								'am'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][3]['am']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][3]['am']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][3]['am']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][3]['am']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][3]['am']['end']['mins'],
									),
								),
								'pm'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][3]['pm']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][3]['pm']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][3]['pm']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][3]['pm']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][3]['pm']['end']['mins'],
									),
								),
							),
							'friday'    => array(
								'closed' => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][4]['open'],
								'am'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][4]['am']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][4]['am']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][4]['am']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][4]['am']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][4]['am']['end']['mins'],
									),
								),
								'pm'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][4]['pm']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][4]['pm']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][4]['pm']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][4]['pm']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][4]['pm']['end']['mins'],
									),
								),
							),
							'saturday'  => array(
								'closed' => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][5]['open'],
								'am'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][5]['am']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][5]['am']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][5]['am']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][5]['am']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][5]['am']['end']['mins'],
									),
								),
								'pm'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][5]['pm']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][5]['pm']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][5]['pm']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][5]['pm']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][5]['pm']['end']['mins'],
									),
								),
							),
							'sunday'    => array(
								'closed' => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][6]['open'],
								'am'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][6]['am']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][6]['am']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][6]['am']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][6]['am']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][6]['am']['end']['mins'],
									),
								),
								'pm'     => array(
									'open'  => (bool) $seopress_pro_options['seopress_local_business_opening_hours'][6]['pm']['open'],
									'start' => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][6]['pm']['start']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][6]['pm']['start']['mins'],
									),
									'end'   => array(
										'hour'   => $seopress_pro_options['seopress_local_business_opening_hours'][6]['pm']['end']['hours'],
										'minute' => $seopress_pro_options['seopress_local_business_opening_hours'][6]['pm']['end']['mins'],
									),
								),
							),
						),
						'woocommerceNoindexCartPage'       => (bool) $seopress_pro_options['seopress_woocommerce_cart_page_no_index'],
						'woocommerceNoindexCheckoutPage'   => (bool) $seopress_pro_options['seopress_woocommerce_checkout_page_no_index'],
						'woocommerceNoindexAccountPage'    => (bool) $seopress_pro_options['seopress_woocommerce_customer_account_page_no_index'],
						'woocommerceAddProductPriceAmountMeta' => (bool) $seopress_pro_options['seopress_woocommerce_product_og_price'],
						'woocommerceAddProductPriceCurrencyMeta' => (bool) $seopress_pro_options['seopress_woocommerce_product_og_currency'],
						'woocommerceRemoveGeneratorMeta'   => (bool) $seopress_pro_options['seopress_woocommerce_meta_generator'],
						'woocommerceRemoveDefaultJsonLdSchema' => (bool) $seopress_pro_options['seopress_woocommerce_schema_output'],
						'woocommerceRemoveBreadcrumbsSchema' => (bool) $seopress_pro_options['seopress_woocommerce_schema_breadcrumbs_output'],
						'easyDigitalDownloadsAddProductPriceAmountMeta' => (bool) $seopress_pro_options['seopress_edd_product_og_price'],
						'easyDigitalDownloadsAddProductPriceCurrencyMeta' => (bool) $seopress_pro_options['seopress_edd_product_og_currency'],
						'easyDigitalDownloadsRemoveGeneratorMeta' => (bool) $seopress_pro_options['seopress_edd_meta_generator'],
						'dublinCoreEnabled'                => (bool) $seopress_pro_options['seopress_dublin_core_enable'],
						'richSnippetsEnabled'              => (bool) $seopress_pro_options['seopress_rich_snippets_enable'],
						'richSnippetsPublisherLogo'        => $seopress_pro_options['seopress_rich_snippets_publisher_logo'],
						'richSnippetsPublisherLogoWidth'   => $seopress_pro_options['seopress_rich_snippets_publisher_logo_width'],
						'richSnippetsPublisherLogoHeight'  => $seopress_pro_options['seopress_rich_snippets_publisher_logo_height'],
						'richSnippetsSiteNavigation'       => $seopress_pro_options['seopress_rich_snippets_site_nav'],
						'breadcrumbsEnabled'               => (bool) $seopress_pro_options['seopress_breadcrumbs_enable'],
						'breadcrumbsJsonEnabled'           => (bool) $seopress_pro_options['seopress_breadcrumbs_json_enable'],
						'breadcrumbsSeparator'             => $seopress_pro_options['seopress_breadcrumbs_separator'],
						'breadcrumbsPostTypeShownCustomPostType' => array(
							'category'        => array(
								'customPostType' => $seopress_pro_options['seopress_breadcrumbs_cpt']['category']['cpt'],
							),
							'postTag'         => array(
								'customPostType' => $seopress_pro_options['seopress_breadcrumbs_cpt']['post_tag']['cpt'],
							),
							'productCategory' => array(
								'customPostType' => $seopress_pro_options['seopress_breadcrumbs_cpt']['product_cat']['cpt'],
							),
							'productTag'      => array(
								'customPostType' => $seopress_pro_options['seopress_breadcrumbs_cpt']['product_tag']['cpt'],
							),
						),
						'breadcrumbsPostTypeShownTaxonomy' => array(
							'post'    => array(
								'taxonomy' => $seopress_pro_options['seopress_breadcrumbs_tax']['post']['tax'],
							),
							'page'    => array(
								'taxonomy' => $seopress_pro_options['seopress_breadcrumbs_tax']['page']['tax'],
							),
							'product' => array(
								'taxonomy' => $seopress_pro_options['seopress_breadcrumbs_tax']['product']['tax'],
							),
						),
						'breadcrumbsI18nHere'              => $seopress_pro_options['seopress_breadcrumbs_i18n_here'],
						'breadcrumbsI18nHome'              => $seopress_pro_options['seopress_breadcrumbs_i18n_home'],
						'breadcrumbsI18nAuthor'            => $seopress_pro_options['seopress_breadcrumbs_i18n_author'],
						'breadcrumbsI18n404'               => $seopress_pro_options['seopress_breadcrumbs_i18n_404'],
						'breadcrumbsI18nSearch'            => $seopress_pro_options['seopress_breadcrumbs_i18n_search'],
						'breadcrumbsI18nNoResults'         => $seopress_pro_options['seopress_breadcrumbs_i18n_no_results'],
						'breadcrumbsRemoveStaticPosts'     => (bool) $seopress_pro_options['seopress_breadcrumbs_remove_blog_page'],
						'breadcrumbsRemoveStaticShopPage'  => (bool) $seopress_pro_options['seopress_breadcrumbs_remove_shop_page'],
						'breadcrumbsRemoveDefaultSeparator' => (bool) $seopress_pro_options['seopress_breadcrumbs_separator_disable'],
						'robotsFileEnabled'                => (bool) $seopress_pro_options['seopress_robots_enable'],
						'robotsFileContents'               => $seopress_pro_options['seopress_robots_file'],
						'googleNewsEnabled'                => (bool) $seopress_pro_options['seopress_news_enable'],
						'googleNewsName'                   => $seopress_pro_options['seopress_news_name'],
						'googleNewsPostTypes'              => array(
							'post'            => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['post']['include'],
							),
							'page'            => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['page']['include'],
							),
							'attachment'      => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['attachment']['include'],
							),
							'wpBlock'         => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['wp_block']['include'],
							),
							'product'         => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['product']['include'],
							),
							'shopOrder'       => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['shop_order']['include'],
							),
							'shopCoupon'      => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['shop_coupon']['include'],
							),
							'actionMonitor'   => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['action_monitor']['include'],
							),
							'seopressBot'     => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['seopress_bot']['include'],
							),
							'seopress404'     => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['seopress_404']['include'],
							),
							'seopressSchemas' => array(
								'enabled' => (bool) $seopress_pro_options['seopress_news_name_post_types_list']['seopress_schemas']['include'],
							),
						),
						'notFoundMonitoringEnabled'        => (bool) $seopress_pro_options['seopress_404_enable'],
						'notFoundCleaningEnabled'          => (bool) $seopress_pro_options['seopress_404_cleaning'],
						'notFoundRedirectTo'               => $seopress_pro_options['seopress_404_redirect_home'],
						'notFoundRedirectToCustomUrl'      => $seopress_pro_options['seopress_404_redirect_custom_url'],
						'notFoundRedirectionStatusCode'    => $seopress_pro_options['seopress_404_redirect_status_code'],
						'notFoundEmailNotificationEnabled' => (bool) $seopress_pro_options['seopress_404_enable_mails'],
						'notFoundEmailNotificationAddress' => (bool) $seopress_pro_options['seopress_404_enable_mails_from'],
						'notFoundEmailNotificationNoAutomaticRedirect' => (bool) $seopress_pro_options['seopress_404_disable_automatic_redirects'],
						'notFoundIpLogging'                => $seopress_pro_options['seopress_404_ip_logging'],
						'rssHtmlPrefix'                    => $seopress_pro_options['seopress_rss_before_html'],
						'rssHtmlSuffix'                    => $seopress_pro_options['seopress_rss_after_html'],
						'rssCommentsDisabled'              => (bool) $seopress_pro_options['seopress_rss_disable_comments_feed'],
						'rssPostsDisabled'                 => (bool) $seopress_pro_options['seopress_rss_disable_posts_feed'],
						'rssExtraDisabled'                 => (bool) $seopress_pro_options['seopress_rss_disable_extra_feed'],
						'rssAllDisabled'                   => (bool) $seopress_pro_options['seopress_rss_disable_all_feeds'],
						'rewriteSearchUrl'                 => $seopress_pro_options['seopress_rewrite_search'],
						'whiteLabelRemoveAdminHeader'      => (bool) $seopress_pro_options['seopress_white_label_admin_header'],
						'whiteLabelRemoveHeaderIcons'      => (bool) $seopress_pro_options['seopress_white_label_admin_notices'],
						'whiteLabelFilterSeoDashiconsClass' => $seopress_pro_options['seopress_white_label_admin_menu'],
						'whiteLabelSEOPressAdminBarTitle'  => $seopress_pro_options['seopress_white_label_admin_bar_icon'],
						'whiteLabelSEOPressMainMenuTitle'  => $seopress_pro_options['seopress_white_label_admin_title'],
						'whiteLabelSEOPressAdminBarImage'  => $seopress_pro_options['seopress_white_label_admin_bar_logo'],
						'whiteLabelRemoveCredits'          => (bool) $seopress_pro_options['seopress_white_label_footer_credits'],
						'whiteLabelRemoveHelpDocumentationIcons' => (bool) $seopress_pro_options['seopress_white_label_help_links'],
						'whiteLabelSEOPressName'           => $seopress_pro_options['seopress_white_label_plugin_list_title'],
						'whiteLabelSEOPressProName'        => $seopress_pro_options['seopress_white_label_plugin_list_title_pro'],
						'whiteLabelSEOPressDescription'    => $seopress_pro_options['seopress_white_label_plugin_list_desc'],
						'whiteLabelSEOPressProDescription' => $seopress_pro_options['seopress_white_label_plugin_list_desc_pro'],
						'whiteLabelSEOPressAuthor'         => $seopress_pro_options['seopress_white_label_plugin_list_author'],
						'whiteLabelSEOPressWebsite'        => $seopress_pro_options['seopress_white_label_plugin_list_website'],
						'whiteLabelRemoveViewDetails'      => (bool) $seopress_pro_options['seopress_white_label_plugin_list_view_details'],
					);
					return array(
						'hasProLicense'  => get_option( 'seopress_pro_license_status' ) === 'valid',
						'titlesMetas'    => $seopress_titles_settings,
						'xmlHtmlSitemap' => $seopress_xml_html_sitemap_settings,
						'social'         => $seopress_social_network_settings,
						'analytics'      => $seopress_google_analytics_settings,
						'advanced'       => $seopress_advanced_settings,
						'tools'          => $seopress_tools_settings,
						'pro'            => $seopress_pro_settings,
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
