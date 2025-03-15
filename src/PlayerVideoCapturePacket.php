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

class PlayerVideoCapturePacket extends DataPacket implements ServerboundPacket{
    public const NETWORK_ID = ProtocolInfo::PLAYER_VIDEO_CAPTURE_PACKET;

    public bool $action;
    public int $frameRate;
    public string $filePrefix;

    /**
     * @generate-create-func
     */
    public static function create(bool $action, int $frameRate, string $filePrefix) : self{
        $result = new self;
        $result->action = $action;
        $result->frameRate = $frameRate;
        $result->filePrefix = $filePrefix;
        return $result;
    }

    protected function decodePayload(PacketSerializer $in) : void{
        $this->action = $in->getBool();
        $this->frameRate = $in->getUnsignedVarInt();
        $this->filePrefix = $in->getString();
    }

    protected function encodePayload(PacketSerializer $out) : void{
        $out->putBool($this->action);
        $out->putUnsignedVarInt($this->frameRate);
        $out->putString($this->filePrefix);
    }

    public function handle(PacketHandlerInterface $handler) : bool{
        return $handler->handlePlayerVideoCapture($this);
    }
}
