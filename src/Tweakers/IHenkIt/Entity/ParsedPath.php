<?php

namespace Tweakers\IHenkIt\Entity;

class ParsedPath
{
	/**
	 * @var string
	 */
	private $contentType;

	/**
	 * @var int
	 */
	private $contentId;

	/**
	 * @param int $contentId
	 */
	public function setContentId($contentId)
	{
		$this->contentId = $contentId;
	}

	/**
	 * @return int
	 */
	public function getContentId()
	{
		return $this->contentId;
	}

	/**
	 * @param string $contentType
	 */
	public function setContentType($contentType)
	{
		$this->contentType = $contentType;
	}

	/**
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}
}
