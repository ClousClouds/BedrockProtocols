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
use function json_decode;
use function json_last_error;

class ModalFormRequestPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MODAL_FORM_REQUEST_PACKET;

	public int $formId;
	public string $formData; //json

	/**
	 * @generate-create-func
	 */
	public static function create(int $formId, string $formData) : self{
		$result = new self;
		$result->formId = $formId;
		$result->formData = $formData;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->formId = $in->getUnsignedVarInt();
		$this->formData = self::validateJson($in->getString());
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt($this->formId);
		$out->putString($this->formData);
	}

	private static function validateJson(string $json) : string{
	json_decode($json);
	return json_last_error() === JSON_ERROR_NONE ? $json : '{}';
}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleModalFormRequest($this);
	}
}
