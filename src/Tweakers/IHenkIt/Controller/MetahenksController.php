<?php

namespace Tweakers\IHenkIt\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Tweakers\IHenkIt\Exception\NotSupportedException;
use Tweakers\IHenkIt\Util\UrlParser;

class MetahenksController
{
	/**
	 * @var \Tweakers\IHenkIt\Service\HenkService
	 */
	private $henkService;

	public function __construct($henkService)
	{
		$this->henkService = $henkService;
	}

	public function listAction(Application $app, Request $request)
	{
		$url = $request->get('url');
		if (!$url)
			return $app->json(array('error' => array('code' => 1, 'msg' => 'no url given.')), 400);

		$parsedUrl = parse_url($url);
		if (!$parsedUrl)
			return $app->json(array('error' => array('code' => 3, 'msg' => 'bad url given.')), 400);

		if (!in_array($parsedUrl['host'], array('tweakers.net', 'gathering.tweakers.net')))
			return $app->json(array('error' => array('code' => 4, 'msg' => 'no url to tweakers given.')), 400);

		$userId = $request->get('userId');

		try
		{
			$parsedPath = UrlParser::parseUrlPath($parsedUrl['path']);
		}
		catch (NotSupportedException $nse)
		{
			return $app->json(array('error' => array('code' => 600, 'msg' => 'not supported yet.')), 400);
		}

		$nrOfHenks = $this->henkService->getNumberOfHenksByContentTypeAndId($parsedPath->getContentType(), $parsedPath->getContentId());

		$hasHenked = false;
		if ($userId)
			$hasHenked = $this->henkService->hasHenked($userId, $parsedPath->getContentType(), $parsedPath->getContentId());

		$returnValue = array(
			'contentId'		=> $parsedPath->getContentId(),
			'contentType'	=> $parsedPath->getContentType(),
			'henks'			=> $nrOfHenks,
			'hasHenked'		=> $hasHenked,
		);

		return $app->json(
			$returnValue,
			200,
			array(
				'Cache-Control' => 'public',
				'Access-Control-Allow-Origin' => '*'
			)
		);
	}
}
