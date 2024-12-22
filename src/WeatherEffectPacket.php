<?php

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class WeatherEffectPacket extends DataPacket implements ClientboundPacket, ServerboundPacket {
    public const NETWORK_ID = ProtocolInfo::WEATHER_EFFECT_PACKET;

    public int $weatherType;

    public int $duration;

    public int $intensity;

    public bool $isLocalEffect;

    public float $x, $y, $z;

    /**
     * @generate-create-func
     */
    public static function create(
        int $weatherType,
        int $duration,
        int $intensity,
        bool $isLocalEffect,
        float $x,
        float $y,
        float $z
    ) : self {
        $result = new self;
        $result->weatherType = $weatherType;
        $result->duration = $duration;
        $result->intensity = $intensity;
        $result->isLocalEffect = $isLocalEffect;
        $result->x = $x;
        $result->y = $y;
        $result->z = $z;
        return $result;
    }

    protected function decodePayload(PacketSerializer $in) : void {
        $this->weatherType = $in->getByte();
        $this->duration = $in->getVarInt();
        $this->intensity = $in->getVarInt();
        $this->isLocalEffect = $in->getBool();
        $this->x = $in->getFloat();
        $this->y = $in->getFloat();
        $this->z = $in->getFloat();
    }

    protected function encodePayload(PacketSerializer $out) : void {
        $out->putByte($this->weatherType);
        $out->putVarInt($this->duration);
        $out->putVarInt($this->intensity);
        $out->putBool($this->isLocalEffect);
        $out->putFloat($this->x);
        $out->putFloat($this->y);
        $out->putFloat($this->z);
    }

    public function handle(PacketHandlerInterface $handler) : bool {
        return $handler->handleWeatherEffect($this);
    }
}
