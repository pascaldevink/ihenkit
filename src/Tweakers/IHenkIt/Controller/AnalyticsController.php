<?php

namespace Tweakers\IHenkIt\Controller;

class AnalyticsController
{
	protected function trackRequest(\Symfony\Component\HttpFoundation\Request $request, $page, $pageTitle)
	{
		// Initilize GA Tracker
		$tracker = new \UnitedPrototype\GoogleAnalytics\Tracker('UA-36250000-1', 'ihenk.it');

		// Assemble Visitor information
		// (could also get unserialized from database)
		$visitor = new \UnitedPrototype\GoogleAnalytics\Visitor();
		$visitor->setIpAddress($request->getClientIp());
		$visitor->setUserAgent($request->server->get('HTTP_USER_AGENT'));
//		$visitor->setScreenResolution('1024x768');

		// Assemble Session information
		// (could also get unserialized from PHP session)
		$session = new \UnitedPrototype\GoogleAnalytics\Session();

		// Assemble Page information
		$page = new \UnitedPrototype\GoogleAnalytics\Page($page);
		$page->setTitle($pageTitle);

		// Track page view
		$tracker->trackPageview($page, $session, $visitor);
	}
}
