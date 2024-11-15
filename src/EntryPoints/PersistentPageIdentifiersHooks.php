<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\EntryPoints;

use CommentStoreComment;
use DatabaseUpdater;
use IContextSource;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use Parser;
use ProfessionalWiki\PersistentPageIdentifiers\PersistentPageIdentifiersExtension;
use StripState;
use WikiPage;

class PersistentPageIdentifiersHooks {

	public static function onInfoAction( IContextSource $context, array &$pageInfo ): void {
		$pageInfo['header-basic'][] = [
			$context->msg( 'persistentpageidentifiers-info-label' ),
			htmlspecialchars( self::formatPersistentId( self::getPersistentIdForPage( $context->getWikiPage() ) ) )
		];
	}

	private static function formatPersistentId( ?string $id ): string {
		return PersistentPageIdentifiersExtension::getInstance()->newPersistentPageIdFormatter()->format( $id );
	}

	private static function getPersistentIdForPage( WikiPage $page ): ?string {
		return PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdentifiersRepo()->getPersistentId(
			$page->getId()
		);
	}

	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ): void {
		$updater->addExtensionTable(
			'persistent_page_ids',
			__DIR__ . '/../../sql/persistent_page_ids.sql'
		);
	}

	public static function onParserFirstCallInit( Parser $parser ): void {
		$parser->setFunctionHook(
			'ppid',
			PersistentPageIdentifiersExtension::getInstance()->newPersistentPageIdFunction(
			)->handleParserFunctionCall( ... )
		);
	}

	public static function onRevisionFromEditComplete(
		WikiPage $wikiPage
	): void {
		if ( !$wikiPage->isNew() ) {
			return;
		}

		$repo = PersistentPageIdentifiersExtension::getInstance()->getPersistentPageIdentifiersRepo();
		$pageId = $wikiPage->getId();

		if ( $repo->getPersistentId( $pageId ) !== null ) {
			return;
		}

		$repo->savePersistentIds(
			[ $pageId => PersistentPageIdentifiersExtension::getInstance()->getIdGenerator()->generate() ]
		);
	}

}
