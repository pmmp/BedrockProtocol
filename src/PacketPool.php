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

use pocketmine\utils\Binary;
use pocketmine\utils\BinaryDataException;

class PacketPool{
	protected static ?PacketPool $instance = null;

	public static function getInstance() : self{
		if(self::$instance === null){
			self::$instance = new self;
		}
		return self::$instance;
	}

	/** @var \SplFixedArray<Packet> */
	protected \SplFixedArray $pool;

	public function __construct(){
		$this->pool = new \SplFixedArray(512);

		$this->registerPacket(new LoginPacket());
		$this->registerPacket(new PlayStatusPacket());
		$this->registerPacket(new ServerToClientHandshakePacket());
		$this->registerPacket(new ClientToServerHandshakePacket());
		$this->registerPacket(new DisconnectPacket());
		$this->registerPacket(new ResourcePacksInfoPacket());
		$this->registerPacket(new ResourcePackStackPacket());
		$this->registerPacket(new ResourcePackClientResponsePacket());
		$this->registerPacket(new TextPacket());
		$this->registerPacket(new SetTimePacket());
		$this->registerPacket(new StartGamePacket());
		$this->registerPacket(new AddPlayerPacket());
		$this->registerPacket(new AddActorPacket());
		$this->registerPacket(new RemoveActorPacket());
		$this->registerPacket(new AddItemActorPacket());
		$this->registerPacket(new ServerPlayerPostMovePositionPacket());
		$this->registerPacket(new TakeItemActorPacket());
		$this->registerPacket(new MoveActorAbsolutePacket());
		$this->registerPacket(new MovePlayerPacket());
		$this->registerPacket(new PassengerJumpPacket());
		$this->registerPacket(new UpdateBlockPacket());
		$this->registerPacket(new AddPaintingPacket());
		$this->registerPacket(new LevelSoundEventPacketV1());
		$this->registerPacket(new LevelEventPacket());
		$this->registerPacket(new BlockEventPacket());
		$this->registerPacket(new ActorEventPacket());
		$this->registerPacket(new MobEffectPacket());
		$this->registerPacket(new UpdateAttributesPacket());
		$this->registerPacket(new InventoryTransactionPacket());
		$this->registerPacket(new MobEquipmentPacket());
		$this->registerPacket(new MobArmorEquipmentPacket());
		$this->registerPacket(new InteractPacket());
		$this->registerPacket(new BlockPickRequestPacket());
		$this->registerPacket(new ActorPickRequestPacket());
		$this->registerPacket(new PlayerActionPacket());
		$this->registerPacket(new HurtArmorPacket());
		$this->registerPacket(new SetActorDataPacket());
		$this->registerPacket(new SetActorMotionPacket());
		$this->registerPacket(new SetActorLinkPacket());
		$this->registerPacket(new SetHealthPacket());
		$this->registerPacket(new SetSpawnPositionPacket());
		$this->registerPacket(new AnimatePacket());
		$this->registerPacket(new RespawnPacket());
		$this->registerPacket(new ContainerOpenPacket());
		$this->registerPacket(new ContainerClosePacket());
		$this->registerPacket(new PlayerHotbarPacket());
		$this->registerPacket(new InventoryContentPacket());
		$this->registerPacket(new InventorySlotPacket());
		$this->registerPacket(new ContainerSetDataPacket());
		$this->registerPacket(new CraftingDataPacket());
		$this->registerPacket(new GuiDataPickItemPacket());
		$this->registerPacket(new BlockActorDataPacket());
		$this->registerPacket(new PlayerInputPacket());
		$this->registerPacket(new LevelChunkPacket());
		$this->registerPacket(new SetCommandsEnabledPacket());
		$this->registerPacket(new SetDifficultyPacket());
		$this->registerPacket(new ChangeDimensionPacket());
		$this->registerPacket(new SetPlayerGameTypePacket());
		$this->registerPacket(new PlayerListPacket());
		$this->registerPacket(new SimpleEventPacket());
		$this->registerPacket(new LegacyTelemetryEventPacket());
		$this->registerPacket(new SpawnExperienceOrbPacket());
		$this->registerPacket(new ClientboundMapItemDataPacket());
		$this->registerPacket(new MapInfoRequestPacket());
		$this->registerPacket(new RequestChunkRadiusPacket());
		$this->registerPacket(new ChunkRadiusUpdatedPacket());
		$this->registerPacket(new GameRulesChangedPacket());
		$this->registerPacket(new CameraPacket());
		$this->registerPacket(new BossEventPacket());
		$this->registerPacket(new ShowCreditsPacket());
		$this->registerPacket(new AvailableCommandsPacket());
		$this->registerPacket(new CommandRequestPacket());
		$this->registerPacket(new CommandBlockUpdatePacket());
		$this->registerPacket(new CommandOutputPacket());
		$this->registerPacket(new UpdateTradePacket());
		$this->registerPacket(new UpdateEquipPacket());
		$this->registerPacket(new ResourcePackDataInfoPacket());
		$this->registerPacket(new ResourcePackChunkDataPacket());
		$this->registerPacket(new ResourcePackChunkRequestPacket());
		$this->registerPacket(new TransferPacket());
		$this->registerPacket(new PlaySoundPacket());
		$this->registerPacket(new StopSoundPacket());
		$this->registerPacket(new SetTitlePacket());
		$this->registerPacket(new AddBehaviorTreePacket());
		$this->registerPacket(new StructureBlockUpdatePacket());
		$this->registerPacket(new ShowStoreOfferPacket());
		$this->registerPacket(new PurchaseReceiptPacket());
		$this->registerPacket(new PlayerSkinPacket());
		$this->registerPacket(new SubClientLoginPacket());
		$this->registerPacket(new AutomationClientConnectPacket());
		$this->registerPacket(new SetLastHurtByPacket());
		$this->registerPacket(new BookEditPacket());
		$this->registerPacket(new NpcRequestPacket());
		$this->registerPacket(new PhotoTransferPacket());
		$this->registerPacket(new ModalFormRequestPacket());
		$this->registerPacket(new ModalFormResponsePacket());
		$this->registerPacket(new ServerSettingsRequestPacket());
		$this->registerPacket(new ServerSettingsResponsePacket());
		$this->registerPacket(new ShowProfilePacket());
		$this->registerPacket(new SetDefaultGameTypePacket());
		$this->registerPacket(new RemoveObjectivePacket());
		$this->registerPacket(new SetDisplayObjectivePacket());
		$this->registerPacket(new SetScorePacket());
		$this->registerPacket(new LabTablePacket());
		$this->registerPacket(new UpdateBlockSyncedPacket());
		$this->registerPacket(new MoveActorDeltaPacket());
		$this->registerPacket(new SetScoreboardIdentityPacket());
		$this->registerPacket(new SetLocalPlayerAsInitializedPacket());
		$this->registerPacket(new UpdateSoftEnumPacket());
		$this->registerPacket(new NetworkStackLatencyPacket());
		$this->registerPacket(new SpawnParticleEffectPacket());
		$this->registerPacket(new AvailableActorIdentifiersPacket());
		$this->registerPacket(new LevelSoundEventPacketV2());
		$this->registerPacket(new NetworkChunkPublisherUpdatePacket());
		$this->registerPacket(new BiomeDefinitionListPacket());
		$this->registerPacket(new LevelSoundEventPacket());
		$this->registerPacket(new LevelEventGenericPacket());
		$this->registerPacket(new LecternUpdatePacket());
		$this->registerPacket(new ClientCacheStatusPacket());
		$this->registerPacket(new OnScreenTextureAnimationPacket());
		$this->registerPacket(new MapCreateLockedCopyPacket());
		$this->registerPacket(new StructureTemplateDataRequestPacket());
		$this->registerPacket(new StructureTemplateDataResponsePacket());
		$this->registerPacket(new ClientCacheBlobStatusPacket());
		$this->registerPacket(new ClientCacheMissResponsePacket());
		$this->registerPacket(new EducationSettingsPacket());
		$this->registerPacket(new EmotePacket());
		$this->registerPacket(new MultiplayerSettingsPacket());
		$this->registerPacket(new SettingsCommandPacket());
		$this->registerPacket(new AnvilDamagePacket());
		$this->registerPacket(new CompletedUsingItemPacket());
		$this->registerPacket(new NetworkSettingsPacket());
		$this->registerPacket(new PlayerAuthInputPacket());
		$this->registerPacket(new CreativeContentPacket());
		$this->registerPacket(new PlayerEnchantOptionsPacket());
		$this->registerPacket(new ItemStackRequestPacket());
		$this->registerPacket(new ItemStackResponsePacket());
		$this->registerPacket(new PlayerArmorDamagePacket());
		$this->registerPacket(new CodeBuilderPacket());
		$this->registerPacket(new UpdatePlayerGameTypePacket());
		$this->registerPacket(new EmoteListPacket());
		$this->registerPacket(new PositionTrackingDBServerBroadcastPacket());
		$this->registerPacket(new PositionTrackingDBClientRequestPacket());
		$this->registerPacket(new DebugInfoPacket());
		$this->registerPacket(new PacketViolationWarningPacket());
		$this->registerPacket(new MotionPredictionHintsPacket());
		$this->registerPacket(new AnimateEntityPacket());
		$this->registerPacket(new CameraShakePacket());
		$this->registerPacket(new PlayerFogPacket());
		$this->registerPacket(new CorrectPlayerMovePredictionPacket());
		$this->registerPacket(new ItemComponentPacket());
		$this->registerPacket(new ClientboundDebugRendererPacket());
		$this->registerPacket(new SyncActorPropertyPacket());
		$this->registerPacket(new AddVolumeEntityPacket());
		$this->registerPacket(new RemoveVolumeEntityPacket());
		$this->registerPacket(new SimulationTypePacket());
		$this->registerPacket(new NpcDialoguePacket());
		$this->registerPacket(new EduUriResourcePacket());
		$this->registerPacket(new CreatePhotoPacket());
		$this->registerPacket(new UpdateSubChunkBlocksPacket());
		$this->registerPacket(new SubChunkPacket());
		$this->registerPacket(new SubChunkRequestPacket());
		$this->registerPacket(new PlayerStartItemCooldownPacket());
		$this->registerPacket(new ScriptMessagePacket());
		$this->registerPacket(new CodeBuilderSourcePacket());
		$this->registerPacket(new TickingAreasLoadStatusPacket());
		$this->registerPacket(new DimensionDataPacket());
		$this->registerPacket(new AgentActionEventPacket());
		$this->registerPacket(new ChangeMobPropertyPacket());
		$this->registerPacket(new LessonProgressPacket());
		$this->registerPacket(new RequestAbilityPacket());
		$this->registerPacket(new RequestPermissionsPacket());
		$this->registerPacket(new ToastRequestPacket());
		$this->registerPacket(new UpdateAbilitiesPacket());
		$this->registerPacket(new UpdateAdventureSettingsPacket());
		$this->registerPacket(new DeathInfoPacket());
		$this->registerPacket(new EditorNetworkPacket());
		$this->registerPacket(new FeatureRegistryPacket());
		$this->registerPacket(new ServerStatsPacket());
		$this->registerPacket(new RequestNetworkSettingsPacket());
		$this->registerPacket(new GameTestRequestPacket());
		$this->registerPacket(new GameTestResultsPacket());
		$this->registerPacket(new UpdateClientInputLocksPacket());
		$this->registerPacket(new CameraPresetsPacket());
		$this->registerPacket(new UnlockedRecipesPacket());
		$this->registerPacket(new CameraInstructionPacket());
		$this->registerPacket(new CompressedBiomeDefinitionListPacket());
		$this->registerPacket(new TrimDataPacket());
		$this->registerPacket(new OpenSignPacket());
		$this->registerPacket(new AgentAnimationPacket());
		$this->registerPacket(new RefreshEntitlementsPacket());
		$this->registerPacket(new PlayerToggleCrafterSlotRequestPacket());
		$this->registerPacket(new SetPlayerInventoryOptionsPacket());
		$this->registerPacket(new SetHudPacket());
		$this->registerPacket(new AwardAchievementPacket());
		$this->registerPacket(new ClientboundCloseFormPacket());
		$this->registerPacket(new ServerboundLoadingScreenPacket());
		$this->registerPacket(new JigsawStructureDataPacket());
		$this->registerPacket(new CurrentStructureFeaturePacket());
		$this->registerPacket(new ServerboundDiagnosticsPacket());
	}

	public function registerPacket(Packet $packet) : void{
		$this->pool[$packet->pid()] = clone $packet;
	}

	public function getPacketById(int $pid) : ?Packet{
		return isset($this->pool[$pid]) ? clone $this->pool[$pid] : null;
	}

	/**
	 * @throws BinaryDataException
	 */
	public function getPacket(string $buffer) : ?Packet{
		$offset = 0;
		return $this->getPacketById(Binary::readUnsignedVarInt($buffer, $offset) & DataPacket::PID_MASK);
	}
}
