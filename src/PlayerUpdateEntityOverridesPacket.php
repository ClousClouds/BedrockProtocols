<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\UpdateType;

class PlayerUpdateEntityOverridesPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_UPDATE_ENTITY_OVERRIDES_PACKET;

	public int $targetId;
	public int $propertyIndex;
	public UpdateType $updateType;
	public int|float $value;

	/**
	 * @generate-create-func
	 */
	public static function create(int $targetId, int $propertyIndex, UpdateType $updateType, int|float $value) : self{
		$result = new self;
		$result->targetId = $targetId;
		$result->propertyIndex = $propertyIndex;
		$result->updateType = $updateType;
		$result->value = $value;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->targetId = $in->getActorUniqueId();
		$this->propertyIndex = $in->getVarInt();
		$this->updateType = UpdateType::from($in->getVarInt());
		$this->value = match($this->updateType){
			UpdateType::SET_INT_OVERRIDE => $in->getVarInt(),
			UpdateType::SET_FLOAT_OVERRIDE => $in->getLFloat(),
			UpdateType::CLEAR_OVERRIDE,
			UpdateType::REMOVE_OVERRIDE => throw new \UnexpectedValueException("Invalid UpdateType for this packet"),
		};
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->targetId);
		$out->putVarInt($this->propertyIndex);
		$out->putVarInt($this->updateType->value);
		match($this->updateType){
			UpdateType::SET_INT_OVERRIDE => $out->putVarInt((int) $this->value),
			UpdateType::SET_FLOAT_OVERRIDE => $out->putLFloat((float) $this->value),
			UpdateType::CLEAR_OVERRIDE,
			UpdateType::REMOVE_OVERRIDE => throw new \UnexpectedValueException("Invalid UpdateType for this packet"),
		};
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerUpdateEntityOverrides($this);
	}
}
