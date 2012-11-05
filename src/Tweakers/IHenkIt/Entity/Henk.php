<?php

namespace Tweakers\IHenkIt\Entity;

/**
 * @Entity @Table(name="henk")
 **/
class Henk
{
	/**
	 * @Id @Column(type="string")
	 * @var string
	 **/
	private $contentType;

	/**
	 * @Id @Column(type="integer")
	 * @var int
	 **/
	private $contentId;

	/**
	 * @Id @Column(type="integer")
	 * @var string
	 **/
	private $userId;

	/**
	 * @Column(type="datetime")
	 * @var \DateTime
	 **/
	private $created;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $url;

	public function setContentId($contentId)
	{
		$this->contentId = $contentId;
	}

	public function getContentId()
	{
		return $this->contentId;
	}

	public function setContentType($contentType)
	{
		$this->contentType = $contentType;
	}

	public function getContentType()
	{
		return $this->contentType;
	}

	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function getCreated()
	{
		return $this->created;
	}

	public function setCreated($created)
	{
		$this->created = $created;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}
}
