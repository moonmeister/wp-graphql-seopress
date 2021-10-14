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

To query for the SEOPress Data on posts and pages, you can add these to your post/page query:

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

Settings for SEOPress are as follows:

```graphql
{
  seoPressSettings {
    hasProLicense
    advanced {
      addEditorToTaxonomy
      addSiteVerificationBing
      addSiteVerificationGoogle
      addSiteVerificationPinterest
      addSiteVerificationYandex
      automaticImageAltText
      automaticImageAltTextFromKeywords
      automaticImageCaption
      automaticImageDescription
      automaticImageTitle
      enableCanonicalUrlColumn
      enableContentAnalysisScoreColumn
      enableMetaDescriptionColumn
      enableNofollowColumn
      enableNoindexColumn
      enablePageSpeedColumn
      enableRedirectUrlColumn
      enableRedirectionEnableColumn
      enableTargetKeywordColumn
      enableTitleTagColumn
      enableTotalWordsColumn
      enableUniversalSeoMetabox
      metaboxSeoPosition
      metaboxStructuredDataDefaultTab
      permissionAdvancedPageSettings
      permissionAnalyticsPageSettings
      permissionBotPageSettings
      permissionMetaboxContentAnalysis
      permissionMetaboxSEO
      permissionProPageSettings
      permissionSocialNetworksPageSettings
      permissionStructuredDataTypesMetabox
      permissionTitlesMetasPageSettings
      permissionToolsPageSettings
      permissionXmlHtmlSitemapPageSettings
      redirectAttachmentPagesToFile
      redirectAttachmentPagesToParent
      removeAdviceStructuredDataMetabox
      removeCategoryInUrl
      removeCommentAuthorUrl
      removeCommentFormWebsiteInput
      removeContentAnalysisMetabox
      removeGeneratorMeta
      removeGenesisSeoFromAdminBar
      removeGenesisSeoMetabox
      removeHentryPostClass
      removeNoindexFromAdminBar
      removeNotificationCenter
      removeProductCategoryInUrl
      removeReallySimpleDiscoveryMeta
      removeReplytocom
      removeSeoAdminBar
      removeSeoNews
      removeSeoTools
      removeShortlinkMeta
      removeTrailingSlashMetas
      removeUniversalSeoMetabox
      removeWindowsLiveWriterMeta
    }
    analytics {
      additionalTrackingBody
      additionalTrackingFooter
      additionalTrackingHead
      cookieConsentAcceptButtonBackgroundColor
      cookieConsentAcceptButtonBackgroundColorHover
      cookieConsentAcceptButtonColor
      cookieConsentAcceptButtonColorHover
      cookieConsentAutomatic
      cookieConsentBackdropColor
      cookieConsentBackdropCustomized
      cookieConsentBackgroundColor
      cookieConsentBarWidth
      cookieConsentChangeChoice
      cookieConsentCloseBackgroundColor
      cookieConsentCloseBackgroundColorHover
      cookieConsentCloseColor
      cookieConsentCloseColorHover
      cookieConsentCookieValidity
      cookieConsentLinkColor
      cookieConsentOptOutClose
      cookieConsentOptOutEdit
      cookieConsentOptOutMessage
      cookieConsentOptOutMessageOk
      cookieConsentPosition
      cookieConsentRequired
      cookieConsentTextAlign
      cookieConsentTextColor
      googleAnalyticsAdWords
      googleAnalyticsAffiliateTracking
      googleAnalyticsAffiliateTrackingEnable
      googleAnalyticsApiClientId
      googleAnalyticsApiSecretId
      googleAnalyticsCrossDomainList
      googleAnalyticsCrossEnable
      googleAnalyticsCustomDimensionAuthor
      googleAnalyticsCustomDimensionCategory
      googleAnalyticsCustomDimensionLoggedIn
      googleAnalyticsCustomDimensionPostType
      googleAnalyticsCustomDimensionTag
      googleAnalyticsDashboardWidget
      googleAnalyticsDownloadTracking
      googleAnalyticsDownloadTrackingEnable
      googleAnalyticsEnable
      googleAnalyticsEventAddToCart
      googleAnalyticsEventPurchases
      googleAnalyticsEventRemoveFromCart
      googleAnalyticsGA4Code
      googleAnalyticsHtmlLocation
      googleAnalyticsIpAnonymization
      googleAnalyticsLinkAttribution
      googleAnalyticsLinkTrackingEnable
      googleAnalyticsNoTrackRoles
      googleAnalyticsOptimize
      googleAnalyticsRemarketing
      googleAnalyticsUACode
      matomoCrossDomainTracking
      matomoCrossDomainTrackingList
      matomoEnable
      matomoHonorDoNotTrack
      matomoHost
      matomoLinkDownloadTracking
      matomoNoCookies
      matomoNoHeatmaps
      matomoPrependDomain
      matomoSiteId
      matomoSubdomainTracking
      matomoTrackWithoutJavascript
    }
    pro {
      breadcrumbsEnabled
      breadcrumbsI18n404
      breadcrumbsI18nAuthor
      breadcrumbsI18nHere
      breadcrumbsI18nHome
      breadcrumbsI18nNoResults
      breadcrumbsI18nSearch
      breadcrumbsJsonEnabled
      breadcrumbsPostTypeShownCustomPostType {
        category
        postTag
        productCategory
        productTag
      }
      breadcrumbsPostTypeShownTaxonomy {
        page
        post
        product
      }
      breadcrumbsRemoveDefaultSeparator
      breadcrumbsRemoveStaticPosts
      breadcrumbsRemoveStaticShopPage
      breadcrumbsSeparator
      dublinCoreEnabled
      easyDigitalDownloadsAddProductPriceAmountMeta
      easyDigitalDownloadsAddProductPriceCurrencyMeta
      easyDigitalDownloadsRemoveGeneratorMeta
      googleNewsEnabled
      googleNewsName
      googleNewsPostTypes
      localBusinessCity
      localBusinessCountry
      localBusinessCuisine
      localBusinessLatitude
      localBusinessLongitude
      localBusinessOpeningHours {
        monday
      }
      localBusinessPhone
      localBusinessPlaceId
      localBusinessPostalCode
      localBusinessPriceRange
      localBusinessSchemaPage
      localBusinessState
      localBusinessStreetAddress
      localBusinessType
      localBusinessUrl
      notFoundCleaningEnabled
      notFoundEmailNotificationAddress
      notFoundEmailNotificationEnabled
      notFoundEmailNotificationNoAutomaticRedirect
      notFoundIpLogging
      notFoundMonitoringEnabled
      notFoundRedirectTo
      notFoundRedirectToCustomUrl
      notFoundRedirectionStatusCode
      rewriteSearchUrl
      richSnippetsEnabled
      richSnippetsPublisherLogo
      richSnippetsPublisherLogoHeight
      richSnippetsPublisherLogoWidth
      richSnippetsSiteNavigation
      robotsFileContents
      robotsFileEnabled
      rssAllDisabled
      rssCommentsDisabled
      rssExtraDisabled
      rssHtmlPrefix
      rssHtmlSuffix
      rssPostsDisabled
      whiteLabelFilterSeoDashiconsClass
      whiteLabelRemoveAdminHeader
      whiteLabelRemoveCredits
      whiteLabelRemoveHeaderIcons
      whiteLabelRemoveHelpDocumentationIcons
      whiteLabelRemoveViewDetails
      whiteLabelSEOPressAdminBarImage
      whiteLabelSEOPressAdminBarTitle
      whiteLabelSEOPressAuthor
      whiteLabelSEOPressDescription
      whiteLabelSEOPressMainMenuTitle
      whiteLabelSEOPressName
      whiteLabelSEOPressProDescription
      whiteLabelSEOPressProName
      whiteLabelSEOPressWebsite
      woocommerceAddProductPriceAmountMeta
      woocommerceAddProductPriceCurrencyMeta
      woocommerceNoindexAccountPage
      woocommerceNoindexCartPage
      woocommerceNoindexCheckoutPage
      woocommerceRemoveBreadcrumbsSchema
      woocommerceRemoveDefaultJsonLdSchema
      woocommerceRemoveGeneratorMeta
    }
    social {
      accountFacebook
      accountInstagram
      accountLinkedIn
      accountPinterest
      accountTwitter
      accountYoutube
      facebookAdminId
      facebookAppId
      facebookImg {
        altText
        sourceUrl
        srcSet
      }
      facebookImgCustomPostTypes {
        product {
          url
        }
      }
      facebookImgDefault
      facebookLinkOwnershipId
      facebookOg
      knowledgeContactOption
      knowledgeContactType
      knowledgeImg {
        altText
        sourceUrl
        srcSet
      }
      knowledgeName
      knowledgePhone
      knowledgeType
      twitterCard
      twitterCardImg {
        altText
        sourceUrl
        srcSet
      }
      twitterCardImgSize
      twitterCardOg
    }
    titlesMetas {
      archive_titles {
        product {
          description
          nofollow
          noindex
          title
        }
      }
      archives_404_desc
      archives_404_title
      archives_author_desc
      archives_author_disable
      archives_author_noindex
      archives_author_title
      archives_date_desc
      archives_date_disable
      archives_date_noindex
      archives_date_title
      archives_search_desc
      archives_search_noindex
      archives_search_title
      attachments_noindex
      home_site_desc
      home_site_title
      noarchive
      nofollow
      noimageindex
      noindex
      noodp
      nositelinkssearchbox
      nosnippet
      paged_noindex
      paged_rel
      separator
      single_titles {
        page {
          date
          description
          nofollow
          noindex
          thumb_gcs
          title
        }
        post {
          date
          description
          nofollow
          noindex
          thumb_gcs
          title
        }
        product {
          date
          description
          nofollow
          noindex
          thumb_gcs
          title
        }
      }
      tax_titles {
        category {
          description
          disable
          nofollow
          noindex
          title
        }
        post_tag {
          description
          disable
          nofollow
          noindex
          title
        }
        productCategory {
          description
          disable
          nofollow
          noindex
          title
        }
        productTag {
          description
          disable
          nofollow
          noindex
          title
        }
      }
    }
    tools {
      compatibilityAviaLayout
      compatibilityDivi
      compatibilityFusion
      compatibilityOxygen
      compatibilityWpBakery
    }
    xmlHtmlSitemap {
      htmlArchiveLinks
      htmlDate
      htmlExclude
      htmlMapping
      htmlOrder
      htmlOrderby
      sitemapPostTypes {
        attachment {
          include
        }
        page {
          include
        }
        post {
          include
        }
        product {
          include
        }
      }
      sitemapTaxonomies {
        category {
          include
        }
        postTag {
          include
        }
        productCategory {
          include
        }
        productTag {
          include
        }
      }
      xmlSitemapAuthorEnabled
      xmlSitemapGeneralEnabled
      xmlSitemapHTMLEnabled
      xmlSitemapImageEnabled
      xmlSitemapVideoEnabled
    }
  }
}


```

## Notes

This can be used in production, however it is still under active development.

Though this is a long list, it is **not** a complete list. Refer to the Graph*i*QL IDE in WordPress for more queries and
documentation on queries.

## Support

[Open an issue](https://github.com/moonmeister/wp-graphql-seopress/issues)
