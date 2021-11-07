<?php

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\types\inventory\InventoryTransactionChangedSlotsHack;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemTransactionData;

final class ItemInteractionData{

	private int $requestId;
	/** @var InventoryTransactionChangedSlotsHack[] */
	private array $requestChangedSlots;
	private UseItemTransactionData $transactionData;

	/**
	 * @param InventoryTransactionChangedSlotsHack[] $requestChangedSlots
	 */
	public function __construct(int $requestId, array $requestChangedSlots, UseItemTransactionData $transactionData){
		$this->requestId = $requestId;
		$this->requestChangedSlots = $requestChangedSlots;
		$this->transactionData = $transactionData;
	}

	public function getRequestId() : int{
		return $this->requestId;
	}

	/**
	 * @return InventoryTransactionChangedSlotsHack[]
	 */
	public function getRequestChangedSlots() : array{
		return $this->requestChangedSlots;
	}

	public function getTransactionData() : UseItemTransactionData{
		return $this->transactionData;
	}

}
