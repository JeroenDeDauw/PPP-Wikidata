<?php

namespace PPP\Wikidata\ValueFormatters;

use InvalidArgumentException;
use OutOfBoundsException;
use PPP\Wikidata\DataModel\WikibaseEntityResourceNode;
use PPP\Wikidata\WikibaseEntityProvider;
use ValueFormatters\FormatterOptions;
use ValueFormatters\ValueFormatter;
use ValueFormatters\ValueFormatterBase;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\EntityIdValue;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Term\Fingerprint;

/**
 * Returns the label of a given Wikibase entity id
 *
 * @licence GPLv2+
 * @author Thomas Pellissier Tanon
 *
 * @todo Add description to the serialisation?
 */
class WikibaseEntityIdFormatter extends ValueFormatterBase {

	/**
	 * @var WikibaseEntityProvider
	 */
	private $entityProvider;

	/**
	 * @param WikibaseEntityProvider $entityProvider
	 * @param FormatterOptions $options
	 */
	public function __construct(WikibaseEntityProvider $entityProvider, FormatterOptions $options) {
		$this->entityProvider = $entityProvider;
		parent::__construct($options);
	}

	/**
	 * @see ValueFormatter::format
	 */
	public function format($value) {
		if(!($value instanceof EntityIdValue)) {
			throw new InvalidArgumentException('$value should be a DataValue');
		}

		$fingerprint = $this->getFingerprintForEntityId($value->getEntityId());
		return new WikibaseEntityResourceNode(
			$this->getLabelFromFingerprint($fingerprint),
			$value->getEntityId(),
			$this->getDescriptionFromFingerprint($fingerprint)
		);
	}

	/**
	 * @return Fingerprint
	 */
	private function getFingerprintForEntityId(EntityId $entityId) {
		if($entityId instanceof ItemId) {
			return $this->entityProvider->getItem($entityId)->getFingerprint();
		} elseif($entityId instanceof PropertyId) {
			return $this->entityProvider->getProperty($entityId)->getFingerprint();
		} else {
			throw new InvalidArgumentException('Unknown entity type:' .  $entityId->getEntityType());
		}
	}

	private function getLabelFromFingerprint(Fingerprint $fingerprint) {
		try {
			return $fingerprint->getLabel($this->getOption(ValueFormatter::OPT_LANG))->getText();
		} catch(OutOfBoundsException $e) {
			return '';
		}
	}

	private function getDescriptionFromFingerprint(Fingerprint $fingerprint) {
		try {
			return $fingerprint->getDescription($this->getOption(ValueFormatter::OPT_LANG))->getText();
		} catch(OutOfBoundsException $e) {
			return '';
		}
	}
}
