# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0] - 2021-12-07
- Added all SEOPress metas listed [in their documentation](https://www.seopress.org/support/guides/list-of-all-post-metas-generated-by-seopress/)
	- *NOTE:* Below schemas are available if the site has SEOPress Free Version. Make sure your code checks if it exists before using pro metas, you can also check `hasProLicense` to check if site is using Pro version.
		- `proSchemas`
	  - `proSchemasManual`
- Changed `metaRobotsNoindex` and `metaRobotsNofollow` to`boolean` type
- Added GraphQL descriptions in schema

## [1.2] - 2021-07-07
- Fix to work with latest WP and SEOPress

## [1.1.0] - 2020-12-02

## Changed

Add SEOPRESS Canonical Url to WPGraphQL

## [1.0.2] - 2019-18-01

## Changed

- Make GraphQL type for SEO field distinct from original plugin.

## [1.0.1] - 2019-18-01

## Changed

- Initial version after conversion from Yoast version of plugin
