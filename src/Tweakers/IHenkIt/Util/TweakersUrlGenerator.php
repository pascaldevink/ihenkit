<?php

namespace Tweakers\IHenkIt\Util;

class TweakersUrlGenerator
{
	public function generateTweakersUrl($contentType, $contentId)
	{
		$baseUrl = 'http://tweakers.net/';

		switch($contentType)
		{
			case 'News':
				return $baseUrl . 'nieuws/' . $contentId;
			default:
				return $baseUrl;
		}
	}
}
