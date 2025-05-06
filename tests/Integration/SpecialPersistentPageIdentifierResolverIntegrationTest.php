<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\PersistentPageIdentifiers\Tests\Integration;

use ProfessionalWiki\PersistentPageIdentifiers\Adapters\DatabasePersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\Application\PersistentPageIdentifiersRepo;
use ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\SpecialPersistentPageIdentifierResolver;
use ProfessionalWiki\PersistentPageIdentifiers\Tests\PersistentPageIdentifiersIntegrationTest;
use Status;

/**
 * @covers \ProfessionalWiki\PersistentPageIdentifiers\EntryPoints\SpecialPersistentPageIdentifierResolver
 * @group Database
 */
class SpecialPersistentPageIdentifierResolverIntegrationTest extends PersistentPageIdentifiersIntegrationTest {

	private PersistentPageIdentifiersRepo $repo;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'persistent_page_ids';
		$this->repo = new DatabasePersistentPageIdentifiersRepo( $this->db );
	}

	protected function newSpecialPage(): SpecialPersistentPageIdentifierResolver {
		return new SpecialPersistentPageIdentifierResolver();
	}

	public function testExecuteWithValidIdRedirects(): void {
		$page = $this->createPageWithText();

		$resolver = $this->newSpecialPage();
		$resolver->execute( $this->repo->getPersistentId( $page->getId() ) );

		$this->assertSame(
			$page->getTitle()->getFullURL(),
			$resolver->getOutput()->getRedirect(),
			'Should redirect to the page associated with the persistent ID.'
		);
	}

	public function testExecuteWithNonExistentIdReturns(): void {
		$resolver = $this->newSpecialPage();
		$resolver->execute( 'non-existent-id' );

		$this->assertSame(
			'',
			$resolver->getOutput()->getRedirect(),
			'Should not redirect for a non-existent persistent ID.'
		);
	}

	public function testExecuteWithEmptySubPageReturns(): void {
		$resolver = $this->newSpecialPage();
		$resolver->execute( '' );

		$this->assertSame(
			'',
			$resolver->getOutput()->getRedirect(),
			'Should not redirect for an empty subpage.'
		);
	}

	public function testExecuteWithNullSubPageReturns(): void {
		$resolver = $this->newSpecialPage();
		$resolver->execute( null );

		$this->assertSame(
			'',
			$resolver->getOutput()->getRedirect(),
			'Should not redirect for a null subpage.'
		);
	}

	public function testOnSubmitWithValidIdRedirects(): void {
		$page = $this->createPageWithText();

		$resolver = $this->newSpecialPage();
		$status = $resolver->onSubmit( [ 'persistentpageidentifier' => $this->repo->getPersistentId( $page->getId() ) ] );

		$this->assertTrue( $status, 'onSubmit should return true on success.' );
		$this->assertSame(
			$page->getTitle()->getFullURL(),
			$resolver->getOutput()->getRedirect(),
			'Should redirect to the page associated with the persistent ID on form submission.'
		);
	}

	public function testOnSubmitWithNonExistentIdReturnsFatalStatus(): void {
		$resolver = $this->newSpecialPage();
		$status = $resolver->onSubmit( [ 'persistentpageidentifier' => 'non-existent-id' ] );

		$this->assertInstanceOf(
			Status::class,
			$status,
			'onSubmit should return a Status object for a non-existent ID.'
		);
		$this->assertFalse( $status->isGood(), 'Status should be fatal.' );

		$errors = $status->getErrors();
		$this->assertNotEmpty( $errors, 'Status should contain errors.' );
		$this->assertEquals(
			'persistentpageidentifierresolver-not-exists',
			$errors[0]['message'],
			'Status message should indicate the ID does not exist.'
		);

		$this->assertSame(
			'',
			$resolver->getOutput()->getRedirect(),
			'Should not redirect when ID does not exist on form submission.'
		);
	}

}
