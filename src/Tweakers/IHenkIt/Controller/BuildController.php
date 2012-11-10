<?php

namespace Tweakers\IHenkIt\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BuildController //extends AnalyticsController
{
	/**
	 * @var \Tweakers\IHenkIt\Service\HenkService
	 */
	private $henkService;

	public function __construct($henkService)
	{
		$this->henkService = $henkService;
	}

	public function indexAction(\Silex\Application $app, Request $request)
	{
//		$this->trackRequest($request, '/index', 'Index');

		$groupedHenks = $this->henkService->getListOfHenkedContent();
		$lastHenked = $this->henkService->getLastHenked();

		$content = $app['twig']->render('index.html.twig', array(
			'groupedHenks' => $groupedHenks,
			'lastHenked' => $lastHenked
		));

		$response = new Response(
			$content,
			200,
			array(
				'Cache-Control' => 'public',
			)
		);

		return $response;
	}

	public function buttonAction(\Silex\Application $app, Request $request)
	{
//		$this->trackRequest($request, '/list', 'Button');

		$url = $request->get('url');
		if (!$url)
			return $app->json(array('error' => array('code' => 1, 'msg' => 'no url given.')), 400);

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
				'Cache-Control' => 'public',
				'Access-Control-Allow-Origin' => '*'
			)
		);
	}
}
