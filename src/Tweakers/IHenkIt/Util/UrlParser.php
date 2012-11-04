<?php

namespace Tweakers\IHenkIt\Util;

class UrlParser
{
	/**
	 * Parses the given path to retrieve the content type and content id.
	 *
	 * @param string $path
	 *
	 * @return \Tweakers\IHenkIt\Entity\ParsedPath
	 * @throws \Tweakers\IHenkIt\Exception\NotSupportedException
	 */
	public static function parseUrlPath($path)
	{
		$path = explode('/', $path);
		switch($path[1])
		{
			case 'forum':
				switch($path[2])
				{
					case 'list_messages':
						$contentType = 'ForumTopic';
						$contentId = $path[3];
						break;
					case 'list_message':
//						$contentType = 'ForumTopicMessage';
//						$contentId = $path[3];
//						break;
					default:
						throw new \Tweakers\IHenkIt\Exception\NotSupportedException();
						break;
				}
				break;
			case 'nieuws':
				$contentType = 'News';
				$contentId = $path[2];
				break;
			case 'reviews':
				$contentType = 'Reviews';
				$contentId = $path[2];
				break;
			case 'video':
				$contentType = 'Video';
				$contentId = $path[2];
				break;
			case 'meuktracker':
				$contentType = 'Download';
				$contentId = $path[2];
				break;
			default:
				throw new \Tweakers\IHenkIt\Exception\NotSupportedException();
		}

		if (!$contentType || !$contentId)
			throw new \Tweakers\IHenkIt\Exception\NotSupportedException();

		$parsedPath = new \Tweakers\IHenkIt\Entity\ParsedPath();
		$parsedPath->setContentId($contentId);
		$parsedPath->setContentType($contentType);

		return $parsedPath;
	}
}
