# BedrockProtocol
[![CI](https://github.com/pmmp/BedrockProtocol/actions/workflows/ci.yml/badge.svg)](https://github.com/pmmp/BedrockProtocol/actions/workflows/ci.yml)

An implementation of the Minecraft: Bedrock Edition protocol in PHP

This library implements all of the packets in the Minecraft: Bedrock Edition protocol, as well as a few extra things needed to support them.
However, at the time of writing, it does _not_ include the following:
- Anything related to JWT handling/verification
- Anything related to encryption
- Anything related to compression

## Decoding packets
Assuming you've decrypted and decompressed a Minecraft packet successfully, you're next going to want to decode it.
With this library, that's currently done using `PacketBatch`, like so:

```php
foreach(PacketBatchUtils::decodePackets(new BinaryStream($payload), $protocolContext, PacketPool::getInstance()) as $packetObject){
    var_dump($packetObject); //tada
}
```

## Encoding packets
This is easy:

```php
/** @var Packet[] $packets */
$stream = new BinaryStream();
PacketBatchUtils::encodePackets($stream, $protocolContext, $packets);
$batchPayload = $stream->getBuffer();
```

## Footnotes
This library is a little rough around the edges, since it's only ever been intended for PocketMine-MP usage. It's only recently that this mess has been separated from the core to allow it to be used by other things.
This means that API changes might be in order, and your feedback would be nice to drive them.
If you want to improve BedrockProtocol, please open issues with suggestions, or better, make pull requests.
