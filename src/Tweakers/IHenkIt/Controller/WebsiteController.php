<?php

namespace Tweakers\IHenkIt\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebsiteController
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
		$groupedHenks = $this->henkService->getListOfHenkedContent();
		$lastHenked = $this->henkService->getLastHenked();
		$totalNumberOfHenks = $this->henkService->getTotalNumberOfHenks();
		$numberOfHenkedContent = $this->henkService->getTotalNumberOfHenkedContent();

		$content = $app['twig']->render('index.html.twig', array(
			'groupedHenks'			=> $groupedHenks,
			'lastHenked'			=> $lastHenked,
			'numberOfHenks'			=> $totalNumberOfHenks,
			'numberOfHenkedContent'	=> $numberOfHenkedContent,
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
}
