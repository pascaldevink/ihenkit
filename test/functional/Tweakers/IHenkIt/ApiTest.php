<?php

namespace Tweakers\IHenkIt;

use DoctrineExtensions\PHPUnit\Event\EntityManagerEventArgs;

class ApiTest extends \Silex\WebTestCase
{

	/**
	 * Creates the application.
	 *
	 * @return HttpKernel
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../../../../config/app-config.php';
		$app['debug'] = true;
		$app['exception_handler']->disable();

		$em = \Tweakers\IHenkItTest\Lib\OrmConnection::createInMemory();
		$app['orm.em'] = $em;

		$eventManager = $em->getEventManager();
		if ($eventManager->hasListeners('preTestSetUp')) {
			$eventManager->dispatchEvent('preTestSetUp', new EntityManagerEventArgs($em));
		}

		return $app;
	}

	public function testNotPreviouslyHenkedPage()
	{
		$client = $this->createClient();
		$crawler = $client->request(
			'GET',
			'/list',
			array(
				'userId'	=> 266225,
				'url'		=> 'http://tweakers.net/nieuws/85424/watch-dogs-komt-in-2013-op-de-markt.html'
			));

		$response = $client->getResponse();
		$this->assertTrue($response->isOk());
		$this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
		$this->assertEquals('{"contentId":"85424","contentType":"News","henks":0}', $response->getContent());
	}

	public function testWithPreviouslyHenkedPage()
	{
		$client = $this->createClient();
		$crawler = $client->request(
			'POST',
			'/henk',
			array(
				'userId'	=> 266225,
				'url'		=> 'http://tweakers.net/nieuws/85424/watch-dogs-komt-in-2013-op-de-markt.html'
			));

		$crawler = $client->request(
			'GET',
			'/list',
			array(
				'userId'	=> 266225,
				'url'		=> 'http://tweakers.net/nieuws/85424/watch-dogs-komt-in-2013-op-de-markt.html'
			));

		$response = $client->getResponse();
		$this->assertTrue($response->isOk());
		$this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
		$this->assertEquals('{"contentId":"85424","contentType":"News","henks":1}', $response->getContent());
	}
}
