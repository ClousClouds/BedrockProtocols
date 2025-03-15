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
use pocketmine\network\mcpe\protocol\types\GraphicsMode;

class UpdateClientOptionsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_CLIENT_OPTIONS_PACKET;

	public ?GraphicsMode $graphicsMode = null;

	/**
	 * @generate-create-func
	 */
	public static function create(?\pocketmine\network\mcpe\protocol\types\GraphicsMode $graphicsMode) : self{
		$result = new self;
		$result->graphicsMode = $graphicsMode;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->graphicsMode = $in->getBool() ? GraphicsMode::from($in->getVarInt()) : null;
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBool($this->graphicsMode !== null);
		if($this->graphicsMode !== null){
			$out->putVarInt($this->graphicsMode->value);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateClientOptions($this);
	}
}
