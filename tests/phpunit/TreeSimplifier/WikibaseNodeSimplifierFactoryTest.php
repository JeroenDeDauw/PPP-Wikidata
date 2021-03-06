<?php

namespace PPP\Wikidata\TreeSimplifier;

/**
 * @covers PPP\Wikidata\TreeSimplifier\WikibaseNodeSimplifierFactory
 *
 * @licence GPLv2+
 * @author Thomas Pellissier Tanon
 */
class WikibaseNodeSimplifierFactoryTest extends \PHPUnit_Framework_TestCase {

	public function testNewSentenceTreeSimplifier() {
		$entityStoreMock = $this->getMock('Wikibase\EntityStore\EntityStore');
		$wikidataQueryApiMock = $this->getMockBuilder('WikidataQueryApi\WikidataQueryApi')
			->disableOriginalConstructor()
			->getMock();
		$factory = new WikibaseNodeSimplifierFactory($entityStoreMock, $wikidataQueryApiMock, 'en');

		$this->assertInstanceOf(
			'PPP\Module\TreeSimplifier\NodeSimplifier',
			$factory->newNodeSimplifier()
		);
	}
}
