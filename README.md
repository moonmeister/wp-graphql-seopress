HEY YOU THERE! Do you use this package? Want to make it better? Let me know and I'll probably add you as a contributor cause I don't have the time.

# WPGraphQL SEOPress Plugin

This is an extension to the WPGraphQL plugin (https://github.com/wp-graphql/wp-graphql) that returns SEOPress data.

Currently returning SEO data for pages, posts, custom post types, categories and custom taxonomies.

> Using this plugin? I would love to see what you make with it. [@moon_meister](https://twitter.com/moon_meister)

## Upstream Source

This is a direct result of [@ash_hitchcock](https://twitter.com/ash_hitchcock) on the original [Yoast SEO plugin](https://github.com/ashhitch/wp-graphql-yoast-seo). Let him know you like his work!

## Quick Install

1. Install & activate [WPGraphQL](https://www.wpgraphql.com/)
2. Clone or download the zip of this repository into your WordPress plugin directory & activate the **WPGraphQL for SEOPress** plugin

## Composer

```
composer require moonmeister/wp-graphql-seopress
```

## Usage

To query for the SEOPress Data as the seo object to your query:

```graphql
{
  pages(first: 10) {
    edges {
      node {
        id
        title
        seo {
          metaTitle
          metaDesc
          canonicalUrl
          metaRobotsNoindex
          metaRobotsNofollow
          metaRobotsArchive
          metaRobotsBreadcrumbs
          metaRobotsImageIndex
          metaRobotsOdp
          metaRobotsPrimaryCategory
          metaRobotsSnippet
          metaNewsDisabled
          metaVideo
          metaVideoDisabled
          redirectionsEnabled
          redirectionsType
          redirectionsURL
          targetKeywords
          proSchemas
          proSchemasManual
          hasProLicense
          opengraphTitle
          opengraphDescription
          opengraphImage {
            altText
            sourceUrl
            srcSet
          }
          twitterTitle
          twitterDescription
          twitterImage {
            altText
            sourceUrl
            srcSet
          }
        }
      }
    }
  }
}

```

## Notes

This can be used in production, however it is still under active development.

## Support

[Open an issue](https://github.com/moonmeister/wp-graphql-seopress/issues)
