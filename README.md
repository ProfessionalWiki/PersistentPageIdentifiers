# Persistent Page Identifiers

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/ProfessionalWiki/PersistentPageIdentifiers/ci.yml?branch=master)](https://github.com/ProfessionalWiki/PersistentPageIdentifiers/actions?query=workflow%3ACI)
[![Latest Stable Version](https://poser.pugx.org/professional-wiki/persistent-page-identifiers/v/stable)](https://packagist.org/packages/professional-wiki/persistent-page-identifiers)
[![Download count](https://poser.pugx.org/professional-wiki/persistent-page-identifiers/downloads)](https://packagist.org/packages/professional-wiki/persistent-page-identifiers)

Stable unique identifiers for your wiki pages. Maintain persistent references across MediaWiki page changes.
Read more in the [Persistent Page Identifiers documentation](https://professional.wiki/en/extension/persistent-page-identifiers).

**Table of Contents**

- [Usage](#usage-documentation)
- [Installation](#installation)
- [PHP Configuration](#php-configuration)
- [Development](#development)
- [Release notes](#release-notes)


[Professional Wiki] created this extension and provides
[MediaWiki Development], [MediaWiki Hosting], and [MediaWiki Consulting] services.

## Usage Documentation

See the [Persistent Page Identifiers usage documentation](https://professional.wiki/en/extension/persistent-page-identifiers#Usage).

## Installation

See the [Persistent Page Identifiers installation instructions](https://professional.wiki/en/extension/persistent-page-identifiers#Installation).

## PHP Configuration

See the [Persistent Page Identifiers configuration reference](https://professional.wiki/en/extension/persistent-page-identifiers#Configuration).

## Development

Run `composer install` in `extensions/PersistentPageIdentifiers/` to make the code quality tools available.

### Running Tests and CI Checks

You can use the `Makefile` by running make commands in the `PersistentPageIdentifiers` directory.

* `make ci`: Run everything
* `make test`: Run all tests
* `make phpunit --filter FooBar`: run only PHPUnit tests with FooBar in their name
* `make phpcs`: Run all style checks
* `make cs`: Run all style checks and static analysis

### Updating Baseline Files

Sometimes PHPStan generate errors or warnings we do not wish to fix.
These can be ignored by adding them to the respective baseline file. You can update
these files with `make stan-baseline`.

## Release Notes

### Version 1.0.0 - TODO

* Generate a unique persistent identifier for each page
* Maintenance script to generate persistent identifiers for all pages without them
* Parser function to display the persistent identifier
* Include the persistent identifier in page information (`&action=info`)
* API endpoint to get the persistent identifiers for pages
* Configurable format for the persistent identifier
* Compatibility with MediaWiki 1.39 up to 1.43
* Compatibility with PHP 8.1 up to 8.3

[Professional Wiki]: https://professional.wiki
[MediaWiki Hosting]: https://pro.wiki
[MediaWiki Development]: https://professional.wiki/en/mediawiki-development
[MediaWiki Consulting]: https://professional.wiki/en/mediawiki-consulting-services
