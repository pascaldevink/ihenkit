<?php

namespace Tweakers\IHenkIt\Controller;

use Symfony\Component\HttpFoundation\Request;

class HenkController
{
	/**
	 * @var \Tweakers\IHenkIt\Service\HenkService
	 */
	private $henkService;

	public function __construct($henkService)
	{
		$this->henkService = $henkService;
	}

	public function registerAction(\Silex\Application $app, Request $request)
	{
		$url = $request->get('url');
		if (!$url)
			return $app->json(array('error' => array('code' => 1, 'msg' => 'no url given.')), 400);

		$userId = $request->get('userId');
		if (!$userId)
			return $app->json(array('error' => array('code' => 2, 'msg' => 'no userId given.')), 400);

		$parsedUrl = parse_url($url);
		if (!$parsedUrl)
			return $app->json(array('error' => array('code' => 3, 'msg' => 'bad url given.')), 400);

		if (!in_array($parsedUrl['host'], array('tweakers.net', 'gathering.tweakers.net')))
			return $app->json(array('error' => array('code' => 4, 'msg' => 'no url to tweakers given.')), 400);

		try
		{
			$parsedPath = \Tweakers\IHenkIt\Util\UrlParser::parseUrlPath($parsedUrl['path']);
		}
		catch (\Tweakers\IHenkIt\Exception\NotSupportedException $nse)
		{
			return $app->json(array('error' => array('code' => 600, 'msg' => 'not supported yet.')), 400);
		}

		$couldHenk = $this->henkService->addHenk($parsedPath->getContentType(), $parsedPath->getContentId(), $userId, $url);

		if (!$couldHenk)
			return $app->json(array('error' => array('code' => 5, 'msg' => 'user already henked this content.')), 400);

		$nrOfHenks = $this->henkService->getNumberOfHenksByContentTypeAndId($parsedPath->getContentType(), $parsedPath->getContentId());

		$returnValue = array(
			'contentId'		=> $parsedPath->getContentId(),
			'contentType'	=> $parsedPath->getContentType(),
			'henks'			=> $nrOfHenks
		);

		return $app->json(
			$returnValue,
			200,
			array(
				'Access-Control-Allow-Origin' => '*'
			));
	}
}
