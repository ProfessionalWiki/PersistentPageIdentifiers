# Persistent Page Identifiers

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/ProfessionalWiki/PersistentPageIdentifiers/ci.yml?branch=master)](https://github.com/ProfessionalWiki/PersistentPageIdentifiers/actions?query=workflow%3ACI)
[![Type Coverage](https://shepherd.dev/github/ProfessionalWiki/PersistentPageIdentifiers/coverage.svg)](https://shepherd.dev/github/ProfessionalWiki/PersistentPageIdentifiers)
[![Psalm level](https://shepherd.dev/github/ProfessionalWiki/PersistentPageIdentifiers/level.svg)](psalm.xml)
[![Latest Stable Version](https://poser.pugx.org/professional-wiki/persistent-page-identifiers/v/stable)](https://packagist.org/packages/professional-wiki/persistent-page-identifiers)
[![Download count](https://poser.pugx.org/professional-wiki/persistent-page-identifiers/downloads)](https://packagist.org/packages/professional-wiki/persistent-page-identifiers)

## Generate missing persistent identifiers for pages

To generate persistent identifiers for pages without them, you can run the maintenance script:
```bash
php maintenance/GenerateMissingIdentifiers.php
```

## Use or display persistent page id

Use the `ppid` parser function:
```
{{#ppid:}}
```
