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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\EducationSettingsAgentCapabilities;
use pocketmine\network\mcpe\protocol\types\EducationSettingsExternalLinkSettings;

class EducationSettingsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::EDUCATION_SETTINGS_PACKET;

	private string $codeBuilderDefaultUri;
	private string $codeBuilderTitle;
	private bool $canResizeCodeBuilder;
	private bool $disableLegacyTitleBar;
	private string $postProcessFilter;
	private string $screenshotBorderResourcePath;
	private ?EducationSettingsAgentCapabilities $agentCapabilities;
	private ?string $codeBuilderOverrideUri;
	private bool $hasQuiz;
	private ?EducationSettingsExternalLinkSettings $linkSettings;

	/**
	 * @generate-create-func
	 */
	public static function create(
		string $codeBuilderDefaultUri,
		string $codeBuilderTitle,
		bool $canResizeCodeBuilder,
		bool $disableLegacyTitleBar,
		string $postProcessFilter,
		string $screenshotBorderResourcePath,
		?EducationSettingsAgentCapabilities $agentCapabilities,
		?string $codeBuilderOverrideUri,
		bool $hasQuiz,
		?EducationSettingsExternalLinkSettings $linkSettings,
	) : self{
		$result = new self;
		$result->codeBuilderDefaultUri = $codeBuilderDefaultUri;
		$result->codeBuilderTitle = $codeBuilderTitle;
		$result->canResizeCodeBuilder = $canResizeCodeBuilder;
		$result->disableLegacyTitleBar = $disableLegacyTitleBar;
		$result->postProcessFilter = $postProcessFilter;
		$result->screenshotBorderResourcePath = $screenshotBorderResourcePath;
		$result->agentCapabilities = $agentCapabilities;
		$result->codeBuilderOverrideUri = $codeBuilderOverrideUri;
		$result->hasQuiz = $hasQuiz;
		$result->linkSettings = $linkSettings;
		return $result;
	}

	public function getCodeBuilderDefaultUri() : string{
		return $this->codeBuilderDefaultUri;
	}

	public function getCodeBuilderTitle() : string{
		return $this->codeBuilderTitle;
	}

	public function canResizeCodeBuilder() : bool{
		return $this->canResizeCodeBuilder;
	}

	public function disableLegacyTitleBar() : bool{ return $this->disableLegacyTitleBar; }

	public function getPostProcessFilter() : string{ return $this->postProcessFilter; }

	public function getScreenshotBorderResourcePath() : string{ return $this->screenshotBorderResourcePath; }

	public function getAgentCapabilities() : ?EducationSettingsAgentCapabilities{ return $this->agentCapabilities; }

	public function getCodeBuilderOverrideUri() : ?string{
		return $this->codeBuilderOverrideUri;
	}

	public function getHasQuiz() : bool{
		return $this->hasQuiz;
	}

	public function getLinkSettings() : ?EducationSettingsExternalLinkSettings{ return $this->linkSettings; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->codeBuilderDefaultUri = CommonTypes::getString($in);
		$this->codeBuilderTitle = CommonTypes::getString($in);
		$this->canResizeCodeBuilder = CommonTypes::getBool($in);
		$this->disableLegacyTitleBar = CommonTypes::getBool($in);
		$this->postProcessFilter = CommonTypes::getString($in);
		$this->screenshotBorderResourcePath = CommonTypes::getString($in);
		$this->agentCapabilities = CommonTypes::readOptional($in, EducationSettingsAgentCapabilities::read(...));
		$this->codeBuilderOverrideUri = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$this->hasQuiz = CommonTypes::getBool($in);
		$this->linkSettings = CommonTypes::readOptional($in, EducationSettingsExternalLinkSettings::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->codeBuilderDefaultUri);
		CommonTypes::putString($out, $this->codeBuilderTitle);
		CommonTypes::putBool($out, $this->canResizeCodeBuilder);
		CommonTypes::putBool($out, $this->disableLegacyTitleBar);
		CommonTypes::putString($out, $this->postProcessFilter);
		CommonTypes::putString($out, $this->screenshotBorderResourcePath);
		CommonTypes::writeOptional($out, $this->agentCapabilities, fn(ByteBufferWriter $out, EducationSettingsAgentCapabilities $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->codeBuilderOverrideUri, CommonTypes::putString(...));
		CommonTypes::putBool($out, $this->hasQuiz);
		CommonTypes::writeOptional($out, $this->linkSettings, fn(ByteBufferWriter $out, EducationSettingsExternalLinkSettings $v) => $v->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleEducationSettings($this);
	}
}
