<?php

/**
 * Plugin Name:     WPGraphQL for SEOPress
 * Plugin URI:      https://github.com/moonmeister/wp-graphql-seopress
 * Description:     A WPGraphQL Extension that adds support for SEOPress
 * Author:          Alex Moon
 * Author URI:      https://www.moonmeister.net
 * Text Domain:     wp-graphql-seopres
 * Domain Path:     /languages
 * Version:         1.0.1
 *
 * @package         WP_Graphql_SEOPRESS
 */
if (!defined('ABSPATH')) {
  exit();
}

use WPGraphQL\AppContext;
use WPGraphQL\Data\DataSource;

add_action('graphql_register_types', function () {
  $post_types = \WPGraphQL::get_allowed_post_types();
  $taxonomies = \WPGraphQL::get_allowed_taxonomies();

  register_graphql_object_type('SEO', [
    'fields' => [
      'metaTitle' => ['type' => 'String'],
      'metaDesc' => ['type' => 'String'],
      'metaRobotsNoindex' => ['type' => 'String'],
      'metaRobotsNofollow' => ['type' => 'String'],
      'opengraphTitle' => ['type' => 'String'],
      'opengraphDescription' => ['type' => 'String'],
      'opengraphImage' => ['type' => 'MediaItem'],
      'twitterTitle' => ['type' => 'String'],
      'twitterDescription' => ['type' => 'String'],
      'twitterImage' => ['type' => 'MediaItem']
    ]
  ]);

  if (!empty($post_types) && is_array($post_types)) {
    foreach ($post_types as $post_type) {
      $post_type_object = get_post_type_object($post_type);

      if (isset($post_type_object->graphql_single_name)) :
        register_graphql_field($post_type_object->graphql_single_name, 'seo', [
          'type' => 'SEO',
          'description' => __('The SEOPress data of the ' . $post_type_object->graphql_single_name, 'wp-graphql'),
          'resolve' => function ($post, array $args, AppContext $context) {

            // Base array
            $seo = array();

            query_posts(array(
              'p' => $$term_obj->term_id,
              'post_type' => 'any'
            ));
            the_post();

            // Get data
            $seo = array(
              'metaTitle' => trim(get_post_meta($post->ID, '_seopress_titles_title', true)),
              'metaDesc' => trim(get_post_meta($post->ID, '_seopress_titles_desc', true)),
              'metaRobotsNoindex' => trim(get_post_meta($post->ID, '_seopress_robots_index', true)),
              'metaRobotsNofollow' => trim(get_post_meta($post->ID, '_seopress_robots_follow', true)),
              'opengraphTitle' => trim(get_post_meta($post->ID, '_seopress_social_fb_title', true)),
              'opengraphDescription' => trim(get_post_meta($post->ID, '_seopress_social_fb_desc', true)),
              'opengraphImage' => DataSource::resolve_post_object(get_post_meta($post->ID, '_seopress_social_fb_img', true), $context),
              'twitterTitle' => trim(get_post_meta($post->ID, '_seopress_social_twitter_title', true)),
              'twitterDescription' => trim(get_post_meta($post->ID, '_seopress_social_twitter_desc', true)),
              'twitterImage' =>  DataSource::resolve_post_object(get_post_meta($post->ID, '_seopress_social_twitter_img', true), $context)
            );
            wp_reset_query();

            return !empty($seo) ? $seo : null;
          }
        ]);
      endif;
    }
  }

  if (!empty($taxonomies) && is_array($taxonomies)) {
    foreach ($taxonomies as $tax) {

      $taxonomy = get_taxonomy($tax);

      if (empty($taxonomy) || !isset($taxonomy->graphql_single_name)) {
        return;
      }


      register_graphql_field($taxonomy->graphql_single_name, 'seo', [
        'type' => 'SEO',
        'description' => __('The SEOPress data of the ' . $taxonomy->label . ' taxonomy.', 'wp-graphql'),
        'resolve' => function ($term, array $args, AppContext $context) {

          $term_obj = get_term($term->term_id);

          query_posts(
            array(
              'tax_query' => array(
                array(
                  'taxonomy' => $term_obj->taxonomy,
                  'terms' => $term_obj->term_id,
                  'field' => 'term_id'
                )
              )
            )
          );
          the_post();

          // Get data
          $seo = array(
            'metaTitle' => trim(get_term_meta($term_obj->term_id, '_seopress_titles_title', true)),
            'metaDesc' => trim(get_term_meta($term_obj->term_id, '_seopress_titles_desc', true)),
            'metaRobotsNoindex' => trim(get_term_meta($term_obj->term_id, '_seopress_robots_index', true)),
            'metaRobotsNofollow' => trim(get_term_meta($term_obj->term_id, '_seopress_robots_follow', true)),
            'opengraphTitle' => trim(get_term_meta($term_obj->term_id, '_seopress_social_fb_title', true)),
            'opengraphDescription' => trim(get_term_meta($term_obj->term_id, '_seopress_social_fb_desc', true)),
            'opengraphImage' => DataSource::resolve_term_object(get_term_meta($term_obj->term_id, '_seopress_social_fb_img', true), $context),
            'twitterTitle' => trim(get_term_meta($term_obj->term_id, '_seopress_social_twitter_title', true)),
            'twitterDescription' => trim(get_term_meta($term_obj->term_id, '_seopress_social_twitter_desc', true)),
            'twitterImage' =>  DataSource::resolve_term_object(get_term_meta($term_obj->term_id, '_seopress_social_twitter_img', true), $context)
          );
          wp_reset_query();

          return !empty($seo) ? $seo : null;
        }
      ]);
    }
  }
});
