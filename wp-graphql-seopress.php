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

		// Social Networks Settings.
		register_graphql_object_type(
			'SEOPressSettings_SocialNetworks',
			array(
				'description' => 'All SEOPress admin settings pages.',
				'fields'      => array(
					'knowledgeType'           => array(
						'type'        => 'String',
						'description' => 'The type of object for knowledge graph. (Ref: https://schema.org/Thing#subtypes)',
					),
					'knowledgeName'           => array(
						'type'        => 'String',
						'description' => 'Name of knowledge graph object.',
					),
					'knowledgeImg'            => array(
						'type'        => 'String',
						'description' => 'Photo or logo for knowledge graph object.',
					),
					'knowledgePhone'          => array(
						'type'        => 'String',
						'description' => 'Organization phone number for knowledge graph (only for organizations).',
					),
					'knowledgeContactType'    => array(
						'type'        => 'String',
						'description' => 'Contact type for knowledge graph (only for organizations). (Ref: https://schema.org/contactType)',
					),
					'knowledgeContactOption'  => array(
						'type'        => 'String',
						'description' => 'Contact option for knowledge graph (only for organizations). (Ref: https://schema.org/ContactPointOption)',
					),
					'accountFacebook'         => array(
						'type'        => 'String',
						'description' => 'Facebook Page URL.',
					),
					'accountTwitter'          => array(
						'type'        => 'String',
						'description' => 'Twitter Page URL.',
					),
					'accountPinterest'        => array(
						'type'        => 'String',
						'description' => 'Pinterest Page URL.',
					),
					'accountInstagram'        => array(
						'type'        => 'String',
						'description' => 'Instagram Page URL.',
					),
					'accountYoutube'          => array(
						'type'        => 'String',
						'description' => 'YouTube Page URL.',
					),
					'accountLinkedIn'         => array(
						'type'        => 'String',
						'description' => 'LinkedIn Page URL.',
					),
					'facebookOg'              => array(
						'type'        => 'Boolean',
						'description' => 'Enable Open Graph Data.',
					),
					'facebookImg'             => array(
						'type'        => 'String',
						'description' => 'Default Image for Open Graph.',
					),
					'facebookImgDefault'      => array(
						'type'        => 'Boolean',
						'description' => 'Use same image for all Open Graph tags.',
					),
					'facebookLinkOwnershipId' => array(
						'type'        => 'String',
						'description' => 'One or more Facebook Page IDs that are associated with a URL in order to enable link editing and instant article publishing. (Ref: https://www.facebook.com/help/1503421039731588)',
					),
					'facebookAdminId'         => array(
						'type'        => 'String',
						'description' => 'The ID (or comma-separated list for properties that can accept multiple IDs) of an app, person using the app, or Page Graph API object.',
					),
					'facebookAppId'           => array(
						'type'        => 'String',
						'description' => 'The Facebook app ID of the site\'s app. In order to use Facebook Insights you must add the app ID to your page.',
					),
					'twitterCard'             => array(
						'type'        => 'Boolean',
						'description' => 'Enable Twitter card',
					),
					'twitterCardOg'           => array(
						'type'        => 'Boolean',
						'description' => 'Use Open Graph if no Twitter cards.',
					),
					'twitterCardImg'          => array(
						'type'        => 'String',
						'description' => 'Default Twitter card image,',
					),
					'twitterCardImgSize'      => array(
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
				),
			)
		);

		register_graphql_object_type(
			'SEOPressSettings_Analytics',
			array(
				'description' => 'All SEOPress admin settings pages.',
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
							'category' => array(
								'title'       => $seopress_titles_options['seopress_titles_tax_titles']['category']['title'],
								'description' => $seopress_titles_options['seopress_titles_tax_titles']['category']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['category']['noindex'],
							),
							'post_tag' => array(
								'title'       => $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['title'],
								'description' => $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['description'],
								'noindex'     => (bool) $seopress_titles_options['seopress_titles_tax_titles']['post_tag']['noindex'],
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
						),
						'sitemapTaxonomies'        => array(
							'category' => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_taxonomies_list']['category']['include'],
							),
							'post_tag' => array(
								'include' => (bool) $seopress_xml_html_sitemap_options['seopress_xml_sitemap_taxonomies_list']['post_tag']['include'],
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
						'knowledgeType'           => $seopress_social_network_options['seopress_social_knowledge_type'],
						'knowledgeName'           => $seopress_social_network_options['seopress_social_knowledge_name'],
						'knowledgeImg'            => $seopress_social_network_options['seopress_social_knowledge_img'],
						'knowledgePhone'          => $seopress_social_network_options['seopress_social_knowledge_phone'],
						'knowledgeContactType'    => $seopress_social_network_options['seopress_social_knowledge_contact_type'],
						'knowledgeContactOption'  => $seopress_social_network_options['seopress_social_knowledge_contact_option'],
						'accountFacebook'         => $seopress_social_network_options['seopress_social_accounts_facebook'],
						'accountTwitter'          => $seopress_social_network_options['seopress_social_accounts_twitter'],
						'accountPinterest'        => $seopress_social_network_options['seopress_social_accounts_pinterest'],
						'accountInstagram'        => $seopress_social_network_options['seopress_social_accounts_instagram'],
						'accountYoutube'          => $seopress_social_network_options['seopress_social_accounts_youtube'],
						'accountLinkedIn'         => $seopress_social_network_options['seopress_social_accounts_linkedin'],
						'facebookOg'              => (bool) $seopress_social_network_options['seopress_social_facebook_og'],
						'facebookImg'             => $seopress_social_network_options['seopress_social_facebook_img'],
						'facebookImgDefault'      => (bool) $seopress_social_network_options['seopress_social_facebook_img_default'],
						'facebookLinkOwnershipId' => $seopress_social_network_options['seopress_social_facebook_link_ownership_id'],
						'facebookAdminId'         => $seopress_social_network_options['seopress_social_facebook_admin_id'],
						'facebookAppId'           => $seopress_social_network_options['seopress_social_facebook_app_id'],
						'twitterCard'             => (bool) $seopress_social_network_options['seopress_social_twitter_card'],
						'twitterCardOg'           => (bool) $seopress_social_network_options['seopress_social_twitter_card_og'],
						'twitterCardImg'          => $seopress_social_network_options['seopress_social_twitter_card_img'],
						'twitterCardImgSize'      => $seopress_social_network_options['seopress_social_twitter_card_img_size'],
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
					return array(
						'hasProLicense'  => get_option( 'seopress_pro_license_status' ) === 'valid',
						'titlesMetas'    => $seopress_titles_settings,
						'xmlHtmlSitemap' => $seopress_xml_html_sitemap_settings,
						'social'         => $seopress_social_network_settings,
						'analytics'      => $seopress_google_analytics_settings,
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
